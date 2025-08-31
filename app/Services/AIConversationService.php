<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\ClientSource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIConversationService
{
    /**
     * Process incoming message and generate AI response
     */
    public function processMessage(string $platform, string $senderId, string $message, array $context = []): string
    {
        try {
            // Convert message to lowercase for better matching
            $lowerMessage = mb_strtolower($message, 'UTF-8');
            
            // Check if this is a new conversation
            $conversation = $this->getOrCreateConversation($platform, $senderId);
            
            // Analyze message intent
            $intent = $this->analyzeIntent($lowerMessage, $conversation);
            
            // Generate appropriate response
            $response = $this->generateResponse($intent, $conversation, $context);
            
            // Update conversation
            $this->updateConversation($conversation, $message, $response, $intent);
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error("AI Conversation Error: " . $e->getMessage());
            return "عذراً، حدث خطأ في النظام. يرجى المحاولة مرة أخرى أو التواصل معنا مباشرة.";
        }
    }

    /**
     * Analyze message intent using Arabic language patterns
     */
    protected function analyzeIntent(string $message, Conversation $conversation): array
    {
        $intent = [
            'type' => 'general_inquiry',
            'confidence' => 0.8,
            'entities' => [],
            'requires_action' => false
        ];

        // Check for greetings
        if ($this->isGreeting($message)) {
            $intent['type'] = 'greeting';
            $intent['confidence'] = 0.95;
        }
        
        // Check for service inquiries
        elseif ($this->isServiceInquiry($message)) {
            $intent['type'] = 'service_inquiry';
            $intent['confidence'] = 0.9;
            $intent['entities']['service_type'] = $this->extractServiceType($message);
        }
        
        // Check for appointment requests
        elseif ($this->isAppointmentRequest($message)) {
            $intent['type'] = 'appointment_request';
            $intent['confidence'] = 0.85;
            $intent['requires_action'] = true;
        }
        
        // Check for pricing inquiries
        elseif ($this->isPricingInquiry($message)) {
            $intent['type'] = 'pricing_inquiry';
            $intent['confidence'] = 0.9;
        }
        
        // Check for document requirements
        elseif ($this->isDocumentInquiry($message)) {
            $intent['type'] = 'document_inquiry';
            $intent['confidence'] = 0.9;
        }
        
        // Check for location/address inquiries
        elseif ($this->isLocationInquiry($message)) {
            $intent['type'] = 'location_inquiry';
            $intent['confidence'] = 0.9;
        }

        return $intent;
    }

    /**
     * Generate contextual response based on intent
     */
    protected function generateResponse(array $intent, Conversation $conversation, array $context = []): string
    {
        $client = $conversation->client;
        $step = $conversation->conversation_step ?? 'initial';

        switch ($intent['type']) {
            case 'greeting':
                return $this->generateGreetingResponse($client, $step);
                
            case 'service_inquiry':
                return $this->generateServiceResponse($intent['entities']['service_type'] ?? null);
                
            case 'appointment_request':
                return $this->generateAppointmentResponse($client, $step);
                
            case 'pricing_inquiry':
                return $this->generatePricingResponse($intent['entities']['service_type'] ?? null);
                
            case 'document_inquiry':
                return $this->generateDocumentResponse($intent['entities']['service_type'] ?? null);
                
            case 'location_inquiry':
                return $this->generateLocationResponse();
                
            default:
                return $this->generateGeneralResponse($client, $step);
        }
    }

    /**
     * Generate greeting response
     */
    protected function generateGreetingResponse(?Client $client, string $step): string
    {
        if (!$client) {
            return "مرحباً! أهلاً وسهلاً بكم في نظامنو للخدمات القانونية والتوثيق. 🏛️\n\n" .
                   "كيف يمكنني مساعدتكم اليوم؟\n\n" .
                   "يمكنني مساعدتكم في:\n" .
                   "• 📋 إنشاء حساب جديد\n" .
                   "• 🗓️ حجز موعد\n" .
                   "• 💰 معرفة الأسعار\n" .
                   "• 📄 المستندات المطلوبة\n" .
                   "• 📍 موقع المكتب\n\n" .
                   "أخبروني بما تحتاجون وسأساعدكم خطوة بخطوة! 😊";
        }

        if ($step === 'initial') {
            return "مرحباً {$client->name}! 👋\n\n" .
                   "أهلاً وسهلاً بكم مرة أخرى في نظامنو.\n\n" .
                   "كيف يمكنني مساعدتكم اليوم؟\n\n" .
                   "• 🗓️ حجز موعد جديد\n" .
                   "• 📋 تحديث معلومات\n" .
                   "• 💰 استفسار عن الأسعار\n" .
                   "• 📄 المستندات المطلوبة\n\n" .
                   "أخبروني بما تحتاجون! 😊";
        }

        return "مرحباً {$client->name}! 👋\n\n" .
               "كيف يمكنني مساعدتكم اليوم؟";
    }

    /**
     * Generate service information response
     */
    protected function generateServiceResponse(?string $serviceType): string
    {
        $services = Service::active()->get();
        
        if ($serviceType) {
            $service = $services->where('category', $serviceType)->first();
            if ($service) {
                return "معلومات عن خدمة {$service->name}:\n\n" .
                       "📝 الوصف: {$service->description}\n" .
                       "💰 السعر: {$service->price} {$service->currency}\n" .
                       "⏱️ المدة: {$service->formatted_duration}\n\n" .
                       "هل تريدون حجز موعد لهذه الخدمة؟";
            }
        }

        $response = "خدماتنا المتاحة:\n\n";
        foreach ($services as $service) {
            $response .= "• {$service->name} - {$service->price} {$service->currency}\n";
        }
        
        $response .= "\nأي خدمة تريدون معرفة المزيد عنها؟";
        return $response;
    }

    /**
     * Generate appointment response
     */
    protected function generateAppointmentResponse(?Client $client, string $step): string
    {
        if (!$client) {
            return "لحجز موعد، أحتاج أولاً لإنشاء حساب لكم. 📋\n\n" .
                   "أخبروني باسمكم الكامل:";
        }

        if ($step === 'initial' || $step === 'name_collected') {
            return "ممتاز! الآن أحتاج لمعرفة:\n\n" .
                   "📱 رقم الهاتف:\n" .
                   "📧 البريد الإلكتروني (اختياري):\n" .
                   "🏠 العنوان:\n\n" .
                   "أخبروني برقم الهاتف أولاً:";
        }

        if ($step === 'phone_collected') {
            return "ممتاز! الآن أخبروني بالبريد الإلكتروني (أو اكتبوا 'لا يوجد'):";
        }

        if ($step === 'email_collected') {
            return "ممتاز! الآن أخبروني بالعنوان:";
        }

        if ($step === 'address_collected') {
            return "ممتاز! الآن أخبروني بنوع الخدمة المطلوبة:\n\n" .
                   "• زواج\n" .
                   "• طلاق\n" .
                   "• توثيق\n" .
                   "• ترجمة\n" .
                   "• استشارة\n\n" .
                   "أي خدمة تريدون؟";
        }

        if ($step === 'service_collected') {
            return "ممتاز! الآن أخبروني بالتاريخ المفضل للموعد (مثال: غداً، الأسبوع القادم، أو تاريخ محدد):";
        }

        return "ممتاز! سأقوم بإنشاء الموعد لكم. هل تريدون إضافة أي ملاحظات أخرى؟";
    }

    /**
     * Generate pricing response
     */
    protected function generatePricingResponse(?string $serviceType): string
    {
        if ($serviceType) {
            $service = Service::where('category', $serviceType)->first();
            if ($service) {
                return "سعر خدمة {$service->name}:\n\n" .
                       "💰 السعر: {$service->price} {$service->currency}\n" .
                       "⏱️ المدة: {$service->formatted_duration}\n\n" .
                       "هل تريدون حجز موعد؟";
            }
        }

        $services = Service::active()->get();
        $response = "أسعار خدماتنا:\n\n";
        
        foreach ($services as $service) {
            $response .= "• {$service->name}: {$service->price} {$service->currency}\n";
        }
        
        $response .= "\nهل تريدون معرفة المزيد عن خدمة معينة؟";
        return $response;
    }

    /**
     * Generate document requirements response
     */
    protected function generateDocumentResponse(?string $serviceType): string
    {
        if ($serviceType) {
            $documents = $this->getRequiredDocuments($serviceType);
            $response = "المستندات المطلوبة لخدمة {$serviceType}:\n\n";
            
            foreach ($documents as $doc) {
                $response .= "• {$doc}\n";
            }
            
            return $response;
        }

        return "المستندات المطلوبة تختلف حسب نوع الخدمة:\n\n" .
               "• زواج: شهادة ميلاد، هوية وطنية، شهادة طلاق سابق (إن وجدت)\n" .
               "• طلاق: عقد الزواج، هوية وطنية\n" .
               "• توثيق: المستند الأصلي، هوية وطنية\n" .
               "• ترجمة: المستند الأصلي، هوية وطنية\n\n" .
               "أي خدمة تريدون معرفة مستنداتها؟";
    }

    /**
     * Generate location response
     */
    protected function generateLocationResponse(): string
    {
        return "موقع مكتبنا:\n\n" .
               "📍 العنوان: شارع الملك فهد، الرياض، المملكة العربية السعودية\n" .
               "📱 الهاتف: +966-11-123-4567\n" .
               "📧 البريد الإلكتروني: info@maazoun.com\n" .
               "🌐 الموقع الإلكتروني: www.maazoun.com\n\n" .
               "ساعات العمل:\n" .
               "الأحد - الخميس: 8:00 ص - 6:00 م\n" .
               "الجمعة: 9:00 ص - 1:00 م\n\n" .
               "هل تريدون تحديد موعد لزيارتنا؟";
    }

    /**
     * Generate general response
     */
    protected function generateGeneralResponse(?Client $client, string $step): string
    {
        if (!$client) {
            return "أعتذر، لم أفهم طلبكم. 🤔\n\n" .
                   "يمكنني مساعدتكم في:\n" .
                   "• 📋 إنشاء حساب جديد\n" .
                   "• 🗓️ حجز موعد\n" .
                   "• 💰 معرفة الأسعار\n" .
                   "• 📄 المستندات المطلوبة\n" .
                   "• 📍 موقع المكتب\n\n" .
                   "أخبروني بما تحتاجون بوضوح وسأساعدكم! 😊";
        }

        return "أعتذر، لم أفهم طلبكم. 🤔\n\n" .
               "يمكنني مساعدتكم في:\n" .
               "• 🗓️ حجز موعد جديد\n" .
               "• 📋 تحديث معلومات\n" .
               "• 💰 استفسار عن الأسعار\n" .
               "• 📄 المستندات المطلوبة\n\n" .
               "أخبروني بما تحتاجون بوضوح! 😊";
    }

    /**
     * Get or create conversation
     */
    protected function getOrCreateConversation(string $platform, string $senderId): Conversation
    {
        $conversation = Conversation::where('platform_id', $senderId)
            ->where('platform', $platform)
            ->latest()
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'platform' => $platform,
                'platform_id' => $senderId,
                'type' => $platform === 'whatsapp' ? 'whatsapp' : 'facebook',
                'direction' => 'incoming',
                'conversation_date' => now(),
                'conversation_step' => 'initial',
                'is_active' => true,
            ]);
        }

        return $conversation;
    }

    /**
     * Update conversation with new message and response
     */
    protected function updateConversation(Conversation $conversation, string $message, string $response, array $intent): void
    {
        // Update conversation step based on intent
        $newStep = $this->determineNextStep($conversation->conversation_step, $intent);
        
        $conversation->update([
            'conversation_step' => $newStep,
            'last_message_at' => now(),
        ]);

        // Create conversation record for incoming message
        Conversation::create([
            'platform' => $conversation->platform,
            'platform_id' => $conversation->platform_id,
            'type' => $conversation->type,
            'direction' => 'incoming',
            'content' => $message,
            'conversation_date' => now(),
            'conversation_step' => $conversation->conversation_step,
            'is_active' => true,
        ]);

        // Create conversation record for outgoing response
        Conversation::create([
            'platform' => $conversation->platform,
            'platform_id' => $conversation->platform_id,
            'type' => $conversation->type,
            'direction' => 'outgoing',
            'content' => $response,
            'conversation_date' => now(),
            'conversation_step' => $newStep,
            'is_active' => true,
        ]);
    }

    /**
     * Determine next conversation step
     */
    protected function determineNextStep(string $currentStep, array $intent): string
    {
        if ($intent['type'] === 'appointment_request') {
            switch ($currentStep) {
                case 'initial':
                    return 'name_collected';
                case 'name_collected':
                    return 'phone_collected';
                case 'phone_collected':
                    return 'email_collected';
                case 'email_collected':
                    return 'address_collected';
                case 'address_collected':
                    return 'service_collected';
                case 'service_collected':
                    return 'date_collected';
                case 'date_collected':
                    return 'completed';
                default:
                    return $currentStep;
            }
        }

        return $currentStep;
    }

    /**
     * Check if message is a greeting
     */
    protected function isGreeting(string $message): bool
    {
        $greetings = [
            'مرحبا', 'أهلا', 'السلام عليكم', 'صباح الخير', 'مساء الخير',
            'hello', 'hi', 'hey', 'good morning', 'good evening'
        ];

        foreach ($greetings as $greeting) {
            if (Str::contains($message, $greeting)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if message is a service inquiry
     */
    protected function isServiceInquiry(string $message): bool
    {
        $serviceKeywords = [
            'خدمة', 'خدمات', 'زواج', 'طلاق', 'توثيق', 'ترجمة', 'استشارة',
            'service', 'services', 'marriage', 'divorce', 'notarization', 'translation', 'consultation'
        ];

        foreach ($serviceKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if message is an appointment request
     */
    protected function isAppointmentRequest(string $message): bool
    {
        $appointmentKeywords = [
            'موعد', 'حجز', 'حجز موعد', 'تحديد موعد', 'appointment', 'book', 'schedule'
        ];

        foreach ($appointmentKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if message is a pricing inquiry
     */
    protected function isPricingInquiry(string $message): bool
    {
        $pricingKeywords = [
            'سعر', 'أسعار', 'تكلفة', 'كم يكلف', 'price', 'cost', 'how much'
        ];

        foreach ($pricingKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if message is a document inquiry
     */
    protected function isDocumentInquiry(string $message): bool
    {
        $documentKeywords = [
            'مستند', 'مستندات', 'أوراق', 'وثائق', 'document', 'documents', 'papers'
        ];

        foreach ($documentKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if message is a location inquiry
     */
    protected function isLocationInquiry(string $message): bool
    {
        $locationKeywords = [
            'أين', 'مكان', 'موقع', 'عنوان', 'where', 'location', 'address'
        ];

        foreach ($locationKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract service type from message
     */
    protected function extractServiceType(string $message): ?string
    {
        $serviceTypes = [
            'زواج' => 'marriage',
            'طلاق' => 'divorce',
            'توثيق' => 'notarization',
            'ترجمة' => 'translation',
            'استشارة' => 'consultation'
        ];

        foreach ($serviceTypes as $arabic => $english) {
            if (Str::contains($message, $arabic)) {
                return $english;
            }
        }

        return null;
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
}
