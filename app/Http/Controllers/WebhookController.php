<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppBusinessService;
use App\Services\FacebookMessengerService;
use App\Services\AIConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $whatsappService;
    protected $facebookService;
    protected $aiService;

    public function __construct()
    {
        $this->whatsappService = app(WhatsAppBusinessService::class);
        $this->facebookService = app(FacebookMessengerService::class);
        $this->aiService = app(AIConversationService::class);
    }

    /**
     * Handle WhatsApp webhook verification
     */
    public function whatsappVerify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Get the verify token from your WhatsApp settings
        $whatsappSettings = \App\Models\WhatsAppSetting::active()->first();
        $verifyToken = $whatsappSettings ? $whatsappSettings->verify_token : null;

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info("WhatsApp webhook verified successfully");
            return response($challenge, 200);
        }

        Log::error("WhatsApp webhook verification failed");
        return response('Forbidden', 403);
    }

    /**
     * Handle WhatsApp webhook messages
     */
    public function whatsappWebhook(Request $request)
    {
        try {
            $body = $request->getContent();
            $signature = $request->header('X-Hub-Signature-256');

            // Verify webhook signature
            $whatsappSettings = \App\Models\WhatsAppSetting::active()->first();
            if ($whatsappSettings && $whatsappSettings->app_secret) {
                if (!$this->whatsappService->verifyWebhookSignature($signature, $body, $whatsappSettings->app_secret)) {
                    Log::error("WhatsApp webhook signature verification failed");
                    return response('Unauthorized', 401);
                }
            }

            $data = json_decode($body, true);
            
            if (isset($data['entry'][0]['changes'][0]['value']['messages'])) {
                $messages = $data['entry'][0]['changes'][0]['value']['messages'];
                
                foreach ($messages as $message) {
                    $this->processWhatsAppMessage($message);
                }
            }

            return response('OK', 200);
            
        } catch (\Exception $e) {
            Log::error("WhatsApp webhook error: " . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Handle Facebook Messenger webhook verification
     */
    public function facebookVerify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Get the verify token from your Facebook settings
        $facebookSettings = \App\Models\FacebookMessengerSetting::active()->first();
        $verifyToken = $facebookSettings ? $facebookSettings->verify_token : null;

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info("Facebook Messenger webhook verified successfully");
            return response($challenge, 200);
        }

        Log::error("Facebook Messenger webhook verification failed");
        return response('Forbidden', 403);
    }

    /**
     * Handle Facebook Messenger webhook messages
     */
    public function facebookWebhook(Request $request)
    {
        try {
            $body = $request->getContent();
            $signature = $request->header('X-Hub-Signature-256');

            // Verify webhook signature
            $facebookSettings = \App\Models\FacebookMessengerSetting::active()->first();
            if ($facebookSettings && $facebookSettings->app_secret) {
                if (!$this->facebookService->verifyWebhookSignature($signature, $body, $facebookSettings->app_secret)) {
                    Log::error("Facebook webhook signature verification failed");
                    return response('Unauthorized', 401);
                }
            }

            $data = json_decode($body, true);
            
            if (isset($data['entry'][0]['messaging'])) {
                $messaging = $data['entry'][0]['messaging'];
                
                foreach ($messaging as $message) {
                    $this->processFacebookMessage($message);
                }
            }

            return response('OK', 200);
            
        } catch (\Exception $e) {
            Log::error("Facebook webhook error: " . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Process incoming WhatsApp message
     */
    protected function processWhatsAppMessage(array $message): void
    {
        try {
            $senderId = $message['from'];
            $messageText = $message['text']['body'] ?? '';
            $timestamp = $message['timestamp'];
            $messageId = $message['id'];

            Log::info("Received WhatsApp message from {$senderId}: {$messageText}");

            // Check if this is a new conversation or existing client
            $client = $this->findOrCreateClientFromWhatsApp($senderId, $messageText);

            // Process message with AI service
            $response = $this->aiService->processMessage('whatsapp', $senderId, $messageText);

            // Send response back via WhatsApp
            $this->whatsappService->sendTextMessage($senderId, $response);

            Log::info("Sent WhatsApp response to {$senderId}");

        } catch (\Exception $e) {
            Log::error("Failed to process WhatsApp message: " . $e->getMessage());
        }
    }

    /**
     * Process incoming Facebook Messenger message
     */
    protected function processFacebookMessage(array $message): void
    {
        try {
            $senderId = $message['sender']['id'];
            $pageId = $message['recipient']['id'];
            $messageText = $message['message']['text'] ?? '';
            $timestamp = $message['timestamp'];
            $messageId = $message['mid'] ?? null;

            Log::info("Received Facebook message from {$senderId} on page {$pageId}: {$messageText}");

            // Check if this is a new conversation or existing client
            $client = $this->findOrCreateClientFromFacebook($senderId, $pageId, $messageText);

            // Process message with AI service
            $response = $this->aiService->processMessage('facebook', $senderId, $messageText);

            // Send response back via Facebook Messenger
            $this->facebookService->sendTextMessage($pageId, $response);

            Log::info("Sent Facebook response to {$senderId} on page {$pageId}");

        } catch (\Exception $e) {
            Log::error("Failed to process Facebook message: " . $e->getMessage());
        }
    }

    /**
     * Find or create client from WhatsApp message
     */
    protected function findOrCreateClientFromWhatsApp(string $phoneNumber, string $message): ?\App\Models\Client
    {
        try {
            // Try to find existing client by phone number
            $client = \App\Models\Client::where('phone', $phoneNumber)->first();

            if (!$client) {
                // Create new client if this is a new conversation
                $client = \App\Models\Client::create([
                    'name' => 'عميل WhatsApp',
                    'phone' => $phoneNumber,
                    'source_id' => $this->getWhatsAppSourceId(),
                    'notes' => "تم إنشاؤه تلقائياً من WhatsApp: {$message}",
                    'is_active' => true
                ]);

                Log::info("Created new client from WhatsApp: {$client->id}");
            }

            return $client;

        } catch (\Exception $e) {
            Log::error("Failed to find/create client from WhatsApp: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Find or create client from Facebook message
     */
    protected function findOrCreateClientFromFacebook(string $senderId, string $pageId, string $message): ?\App\Models\Client
    {
        try {
            // Try to find existing client by Facebook ID
            $client = \App\Models\Client::where('facebook_id', $senderId)->first();

            if (!$client) {
                // Create new client if this is a new conversation
                $client = \App\Models\Client::create([
                    'name' => 'عميل Facebook',
                    'facebook_id' => $senderId,
                    'facebook_page_id' => $pageId,
                    'source_id' => $this->getFacebookSourceId(),
                    'notes' => "تم إنشاؤه تلقائياً من Facebook Messenger: {$message}",
                    'is_active' => true
                ]);

                Log::info("Created new client from Facebook: {$client->id}");
            }

            return $client;

        } catch (\Exception $e) {
            Log::error("Failed to find/create client from Facebook: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get WhatsApp source ID
     */
    protected function getWhatsAppSourceId(): ?int
    {
        $source = \App\Models\ClientSource::where('name', 'WhatsApp')->first();
        return $source ? $source->id : null;
    }

    /**
     * Get Facebook source ID
     */
    protected function getFacebookSourceId(): ?int
    {
        $source = \App\Models\ClientSource::where('name', 'Facebook')->first();
        return $source ? $source->id : null;
    }

    /**
     * Handle webhook errors
     */
    public function handleError(\Exception $e)
    {
        Log::error("Webhook error: " . $e->getMessage());
        return response('Internal Server Error', 500);
    }
}
