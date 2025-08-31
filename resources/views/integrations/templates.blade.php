@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('integrations.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            العودة إلى التكاملات
        </a>
    </div>

    <div class="max-w-6xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">إدارة قوالب الرسائل</h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $type === 'whatsapp' ? 'WhatsApp Business' : 'Facebook Messenger' }} - {{ $integration->business_name ?? $integration->page_name }}
                </p>
            </div>

            <form method="POST" action="{{ route('integrations.update-templates', ['type' => $type, 'id' => $integration->id]) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Message Templates -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">قوالب الرسائل</h3>
                        
                        <!-- Welcome Message -->
                        <div class="mb-6">
                            <label for="welcome_message" class="block text-sm font-medium text-gray-700 mb-2">رسالة الترحيب</label>
                            <textarea name="templates[welcome]" id="welcome_message" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="مرحباً! كيف يمكنني مساعدتك اليوم؟">{{ $integration->message_templates['welcome'] ?? 'مرحباً! كيف يمكنني مساعدتك اليوم؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رسالة الترحيب للمستخدمين الجدد</p>
                        </div>

                        <!-- Service Inquiry Response -->
                        <div class="mb-6">
                            <label for="service_inquiry" class="block text-sm font-medium text-gray-700 mb-2">رد استفسار الخدمات</label>
                            <textarea name="templates[service_inquiry]" id="service_inquiry" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="نقدم العديد من الخدمات القانونية. هل يمكنك إخباري بمزيد من التفاصيل عن احتياجاتك؟">{{ $integration->message_templates['service_inquiry'] ?? 'نقدم العديد من الخدمات القانونية. هل يمكنك إخباري بمزيد من التفاصيل عن احتياجاتك؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد على استفسارات الخدمات</p>
                        </div>

                        <!-- Appointment Confirmation -->
                        <div class="mb-6">
                            <label for="appointment_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد الموعد</label>
                            <textarea name="templates[appointment_confirmation]" id="appointment_confirmation" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="تم تأكيد موعدك بنجاح! سنتواصل معك قريباً لتأكيد التفاصيل.">{{ $integration->message_templates['appointment_confirmation'] ?? 'تم تأكيد موعدك بنجاح! سنتواصل معك قريباً لتأكيد التفاصيل.' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رسالة تأكيد الموعد</p>
                        </div>

                        <!-- Pricing Inquiry -->
                        <div class="mb-6">
                            <label for="pricing_inquiry" class="block text-sm font-medium text-gray-700 mb-2">استفسار الأسعار</label>
                            <textarea name="templates[pricing_inquiry]" id="pricing_inquiry" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="تختلف الأسعار حسب نوع الخدمة والتعقيد. هل يمكنك إخباري بنوع الخدمة المطلوبة؟">{{ $integration->message_templates['pricing_inquiry'] ?? 'تختلف الأسعار حسب نوع الخدمة والتعقيد. هل يمكنك إخباري بنوع الخدمة المطلوبة؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد على استفسارات الأسعار</p>
                        </div>

                        <!-- Document Requirements -->
                        <div class="mb-6">
                            <label for="document_requirements" class="block text-sm font-medium text-gray-700 mb-2">المستندات المطلوبة</label>
                            <textarea name="templates[document_requirements]" id="document_requirements" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="ستحتاج إلى إحضار المستندات التالية: الهوية الوطنية، المستندات المتعلقة بالقضية، وأي مستندات أخرى ذات صلة.">{{ $integration->message_templates['document_requirements'] ?? 'ستحتاج إلى إحضار المستندات التالية: الهوية الوطنية، المستندات المتعلقة بالقضية، وأي مستندات أخرى ذات صلة.' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">معلومات المستندات المطلوبة</p>
                        </div>
                    </div>

                    <!-- Auto Replies -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">الردود التلقائية</h3>
                        
                        <!-- Greeting Auto Reply -->
                        <div class="mb-6">
                            <label for="greeting_auto_reply" class="block text-sm font-medium text-gray-700 mb-2">رد الترحيب التلقائي</label>
                            <textarea name="auto_replies[greeting]" id="greeting_auto_reply" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="مرحباً! أهلاً وسهلاً بك في مكتبنا القانوني. كيف يمكنني مساعدتك؟">{{ $integration->auto_replies['greeting'] ?? 'مرحباً! أهلاً وسهلاً بك في مكتبنا القانوني. كيف يمكنني مساعدتك؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد تلقائي على رسائل الترحيب</p>
                        </div>

                        <!-- Thank You Auto Reply -->
                        <div class="mb-6">
                            <label for="thank_you_auto_reply" class="block text-sm font-medium text-gray-700 mb-2">رد الشكر التلقائي</label>
                            <textarea name="auto_replies[thank_you]" id="thank_you_auto_reply" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="شكراً لك! نحن سعداء لمساعدتك. هل هناك أي شيء آخر تحتاج إليه؟">{{ $integration->auto_replies['thank_you'] ?? 'شكراً لك! نحن سعداء لمساعدتك. هل هناك أي شيء آخر تحتاج إليه؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد تلقائي على رسائل الشكر</p>
                        </div>

                        <!-- Goodbye Auto Reply -->
                        <div class="mb-6">
                            <label for="goodbye_auto_reply" class="block text-sm font-medium text-gray-700 mb-2">رد الوداع التلقائي</label>
                            <textarea name="auto_replies[goodbye]" id="goodbye_auto_reply" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="شكراً لك على التواصل معنا! نتمنى لك يوماً سعيداً. لا تتردد في التواصل معنا مرة أخرى.">{{ $integration->auto_replies['goodbye'] ?? 'شكراً لك على التواصل معنا! نتمنى لك يوماً سعيداً. لا تتردد في التواصل معنا مرة أخرى.' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد تلقائي على رسائل الوداع</p>
                        </div>

                        <!-- Unrecognized Message Auto Reply -->
                        <div class="mb-6">
                            <label for="unrecognized_auto_reply" class="block text-sm font-medium text-gray-700 mb-2">رد الرسائل غير المعروفة</label>
                            <textarea name="auto_replies[unrecognized]" id="unrecognized_auto_reply" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="عذراً، لم أفهم رسالتك. هل يمكنك إعادة صياغتها أو اختيار من القائمة أدناه؟">{{ $integration->auto_replies['unrecognized'] ?? 'عذراً، لم أفهم رسالتك. هل يمكنك إعادة صياغتها أو اختيار من القائمة أدناه؟' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد على الرسائل التي لا يمكن فهمها</p>
                        </div>

                        <!-- Business Hours Auto Reply -->
                        <div class="mb-6">
                            <label for="business_hours_auto_reply" class="block text-sm font-medium text-gray-700 mb-2">رد ساعات العمل</label>
                            <textarea name="auto_replies[business_hours]" id="business_hours_auto_reply" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="ساعات عملنا: الأحد - الخميس من 8:00 صباحاً إلى 5:00 مساءً. الجمعة والسبت عطلة.">{{ $integration->auto_replies['business_hours'] ?? 'ساعات عملنا: الأحد - الخميس من 8:00 صباحاً إلى 5:00 مساءً. الجمعة والسبت عطلة.' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">رد على استفسارات ساعات العمل</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('integrations.index') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        حفظ القوالب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
