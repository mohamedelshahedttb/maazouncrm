<?php

namespace App\Services;

use App\Models\FacebookMessengerSetting;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookMessengerService
{
    protected $baseUrl = 'https://graph.facebook.com/v18.0';
    protected $settings;

    public function __construct()
    {
        $this->settings = FacebookMessengerSetting::active()->first();
    }

    /**
     * Send text message via Facebook Messenger API
     */
    public function sendTextMessage(string $pageId, string $message, ?string $integrationId = null): bool
    {
        try {
            $settings = $integrationId ? 
                FacebookMessengerSetting::find($integrationId) : 
                $this->settings;

            if (!$settings || !$settings->canSendMessages()) {
                Log::error("Facebook Messenger settings not available or not active");
                return false;
            }

            $accessToken = $settings->access_token;

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/{$pageId}/messages", [
                'recipient' => [
                    'id' => $pageId
                ],
                'message' => [
                    'text' => $message
                ]
            ]);

            if ($response->successful()) {
                Log::info("Facebook Messenger message sent successfully to {$pageId}");
                return true;
            } else {
                Log::error("Facebook Messenger API error: " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Facebook Messenger service error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send appointment reminder to client
     */
    public function sendAppointmentReminder(Appointment $appointment): bool
    {
        try {
            $client = $appointment->client;
            $service = $appointment->service;
            
            $message = $this->generateAppointmentReminderMessage($appointment);
            
            // For Facebook, we need the page ID where the client messaged
            // This would typically be stored in the conversation or client record
            $pageId = $this->getClientPageId($client);
            
            if ($pageId) {
                return $this->sendTextMessage($pageId, $message);
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error("Failed to send appointment reminder: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send daily appointment notifications to admins
     */
    public function sendDailyAdminNotifications(): bool
    {
        try {
            $admins = User::where('role', 'admin')->where('is_active', true)->get();
            $tomorrow = now()->addDay()->format('Y-m-d');
            $today = now()->format('Y-m-d');
            
            // Get tomorrow's appointments
            $tomorrowAppointments = Appointment::whereDate('appointment_date', $tomorrow)
                ->with(['client', 'service'])
                ->get();
                
            // Get today's appointments
            $todayAppointments = Appointment::whereDate('appointment_date', $today)
                ->with(['client', 'service'])
                ->get();

            foreach ($admins as $admin) {
                if ($admin->phone) {
                    $message = $this->generateDailyAdminNotification($todayAppointments, $tomorrowAppointments);
                    // For admin notifications, we'll use WhatsApp instead
                    // as Facebook Messenger requires page-specific messaging
                }
            }

            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send daily admin notifications: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send appointment reminders to clients (1 day before)
     */
    public function sendClientAppointmentReminders(): bool
    {
        try {
            $tomorrow = now()->addDay()->format('Y-m-d');
            
            $appointments = Appointment::whereDate('appointment_date', $tomorrow)
                ->where('status', 'confirmed')
                ->with(['client', 'service'])
                ->get();

            $successCount = 0;
            foreach ($appointments as $appointment) {
                $pageId = $this->getClientPageId($appointment->client);
                if ($pageId) {
                    $message = $this->generateClientReminderMessage($appointment);
                    if ($this->sendTextMessage($pageId, $message)) {
                        $successCount++;
                    }
                }
            }

            Log::info("Sent appointment reminders to {$successCount} clients via Facebook Messenger");
            return $successCount > 0;
            
        } catch (\Exception $e) {
            Log::error("Failed to send client appointment reminders: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send document reminder to client
     */
    public function sendDocumentReminder(Appointment $appointment): bool
    {
        try {
            $client = $appointment->client;
            $service = $appointment->service;
            
            $message = $this->generateDocumentReminderMessage($appointment);
            
            $pageId = $this->getClientPageId($client);
            if ($pageId) {
                return $this->sendTextMessage($pageId, $message);
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error("Failed to send document reminder: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate appointment reminder message
     */
    protected function generateAppointmentReminderMessage(Appointment $appointment): string
    {
        $client = $appointment->client;
        $service = $appointment->service;
        $date = $appointment->appointment_date->format('Y-m-d');
        $time = $appointment->appointment_time;
        
        return "مرحباً {$client->name}\n\n" .
               "تذكير بموعدكم غداً:\n\n" .
               "التاريخ: {$date}\n" .
               "الوقت: {$time}\n" .
               "الخدمة: {$service->name}\n" .
               "المكان: مكتبنا الرئيسي\n\n" .
               "نرجو التأكيد على حضوركم.\n\n" .
               "هل تحتاجون لتأجيل الموعد؟";
    }

    /**
     * Generate daily admin notification message
     */
    protected function generateDailyAdminNotification($todayAppointments, $tomorrowAppointments): string
    {
        $message = "تقرير المواعيد اليومي\n\n";
        
        if ($todayAppointments->count() > 0) {
            $message .= "مواعيد اليوم ({$todayAppointments->count()}):\n";
            foreach ($todayAppointments->take(5) as $appointment) {
                $message .= "- {$appointment->client->name} - {$appointment->service->name} - {$appointment->appointment_time}\n";
            }
            if ($todayAppointments->count() > 5) {
                $remaining = $todayAppointments->count() - 5;
                $message .= "- و {$remaining} مواعيد أخرى\n";
            }
        } else {
            $message .= "لا توجد مواعيد اليوم\n";
        }
        
        $message .= "\n";
        
        if ($tomorrowAppointments->count() > 0) {
            $message .= "مواعيد الغد ({$tomorrowAppointments->count()}):\n";
            foreach ($tomorrowAppointments->take(5) as $appointment) {
                $message .= "- {$appointment->client->name} - {$appointment->service->name} - {$appointment->appointment_time}\n";
            }
            if ($tomorrowAppointments->count() > 5) {
                $remaining = $tomorrowAppointments->count() - 5;
                $message .= "- و {$remaining} مواعيد أخرى\n";
            }
        } else {
            $message .= "لا توجد مواعيد غداً\n";
        }
        
        return $message;
    }

    /**
     * Generate client reminder message
     */
    protected function generateClientReminderMessage(Appointment $appointment): string
    {
        $client = $appointment->client;
        $service = $appointment->service;
        $date = $appointment->appointment_date->format('Y-m-d');
        $time = $appointment->appointment_time;
        
        $message = "مرحباً {$client->name}\n\n" .
                  "تذكير بموعدكم غداً:\n\n" .
                  "التاريخ: {$date}\n" .
                  "الوقت: {$time}\n" .
                  "الخدمة: {$service->name}\n" .
                  "المكان: مكتبنا الرئيسي\n\n" .
                  "المستندات المطلوبة:\n";
        
        // Add required documents based on service
        $documents = $this->getRequiredDocuments($service->category);
        foreach ($documents as $doc) {
            $message .= "- {$doc}\n";
        }
        
        $message .= "\n" .
                   "نرجو التأكد من إحضار جميع المستندات المطلوبة.\n\n" .
                   "هل تحتاجون لتأجيل الموعد؟";
        
        return $message;
    }

    /**
     * Generate document reminder message
     */
    protected function generateDocumentReminderMessage(Appointment $appointment): string
    {
        $client = $appointment->client;
        $service = $appointment->service;
        
        $message = "مرحباً {$client->name}\n\n" .
                  "تذكير بموعدكم غداً:\n\n" .
                  "التاريخ: {$appointment->appointment_date->format('Y-m-d')}\n" .
                  "الوقت: {$appointment->appointment_time}\n" .
                  "الخدمة: {$service->name}\n\n" .
                  "المستندات المطلوبة:\n";
        
        // Add required documents based on service
        $documents = $this->getRequiredDocuments($service->category);
        foreach ($documents as $doc) {
            $message .= "- {$doc}\n";
        }
        
        $message .= "\n" .
                   "نرجو التأكد من إحضار جميع المستندات المطلوبة.\n\n" .
                   "هل تحتاجون لتأجيل الموعد؟";
        
        return $message;
    }

    /**
     * Get required documents for service type
     */
    protected function getRequiredDocuments(string $serviceType): array
    {
        $documents = [
            'marriage' => [
                'شهادة الميلاد للعريس',
                'شهادة الميلاد للعروس',
                'الهوية الوطنية للعريس',
                'الهوية الوطنية للعروس',
                'شهادة الطلاق السابق (إن وجدت)',
                'شهادة وفاة الزوج السابق (إن وجدت)'
            ],
            'divorce' => [
                'عقد الزواج الأصلي',
                'الهوية الوطنية للزوجين',
                'شهادة الميلاد للأبناء (إن وجدوا)'
            ],
            'notarization' => [
                'المستند الأصلي المراد توثيقه',
                'الهوية الوطنية',
                'أي مستندات داعمة'
            ],
            'translation' => [
                'المستند الأصلي المراد ترجمته',
                'الهوية الوطنية',
                'شهادة الترجمة (إن وجدت)'
            ],
            'consultation' => [
                'الهوية الوطنية',
                'أي مستندات متعلقة بالاستشارة'
            ]
        ];

        return $documents[$serviceType] ?? ['الهوية الوطنية'];
    }

    /**
     * Get client's Facebook page ID
     */
    protected function getClientPageId(Client $client): ?string
    {
        // This would typically be stored in the conversation or client record
        // For now, we'll return null as this needs to be implemented based on your data structure
        return null;
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $signature, string $body, string $appSecret): bool
    {
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $body, $appSecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process webhook data
     */
    public function processWebhook(array $data): void
    {
        try {
            if (isset($data['entry'][0]['messaging'])) {
                $messaging = $data['entry'][0]['messaging'];
                
                foreach ($messaging as $message) {
                    $this->processIncomingMessage($message);
                }
            }
        } catch (\Exception $e) {
            Log::error("Webhook processing error: " . $e->getMessage());
        }
    }

    /**
     * Process incoming message
     */
    protected function processIncomingMessage(array $message): void
    {
        try {
            $senderId = $message['sender']['id'];
            $pageId = $message['recipient']['id'];
            $messageText = $message['message']['text'] ?? '';
            $timestamp = $message['timestamp'];
            
            // Use AI service to generate response
            $aiService = app(AIConversationService::class);
            $response = $aiService->processMessage('facebook', $senderId, $messageText);
            
            // Send response back
            $this->sendTextMessage($pageId, $response);
            
        } catch (\Exception $e) {
            Log::error("Incoming message processing error: " . $e->getMessage());
        }
    }

    /**
     * Send quick reply message
     */
    public function sendQuickReply(string $pageId, string $message, array $quickReplies, ?string $integrationId = null): bool
    {
        try {
            $settings = $integrationId ? 
                FacebookMessengerSetting::find($integrationId) : 
                $this->settings;

            if (!$settings || !$settings->canSendMessages()) {
                return false;
            }

            $accessToken = $settings->access_token;

            $quickReplyElements = [];
            foreach ($quickReplies as $reply) {
                $quickReplyElements[] = [
                    'content_type' => 'text',
                    'title' => $reply['title'],
                    'payload' => $reply['payload']
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/{$pageId}/messages", [
                'recipient' => [
                    'id' => $pageId
                ],
                'message' => [
                    'text' => $message,
                    'quick_replies' => $quickReplyElements
                ]
            ]);

            return $response->successful();
            
        } catch (\Exception $e) {
            Log::error("Failed to send quick reply: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send button template message
     */
    public function sendButtonTemplate(string $pageId, string $message, array $buttons, ?string $integrationId = null): bool
    {
        try {
            $settings = $integrationId ? 
                FacebookMessengerSetting::find($integrationId) : 
                $this->settings;

            if (!$settings || !$settings->canSendMessages()) {
                return false;
            }

            $accessToken = $settings->access_token;

            $buttonElements = [];
            foreach ($buttons as $button) {
                $buttonElements[] = [
                    'type' => $button['type'],
                    'title' => $button['title'],
                    'payload' => $button['payload'] ?? null,
                    'url' => $button['url'] ?? null
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/{$pageId}/messages", [
                'recipient' => [
                    'id' => $pageId
                ],
                'message' => [
                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'button',
                            'text' => $message,
                            'buttons' => $buttonElements
                        ]
                    ]
                ]
            ]);

            return $response->successful();
            
        } catch (\Exception $e) {
            Log::error("Failed to send button template: " . $e->getMessage());
            return false;
        }
    }
}
