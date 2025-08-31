@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إدارة التكاملات</h1>
        <p class="text-gray-600 mt-2">إدارة تكاملات WhatsApp Business و Facebook Messenger</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-green-400 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">WhatsApp Business</h3>
                    <p class="text-green-100">إدارة تكاملات WhatsApp</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('integrations.whatsapp.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                    إضافة تكامل جديد
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-blue-400 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">Facebook Messenger</h3>
                    <p class="text-blue-100">إدارة تكاملات Facebook</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('integrations.facebook.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                    إضافة تكامل جديد
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-purple-400 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM10 7h10V1H10v6z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">الإشعارات التلقائية</h3>
                    <p class="text-purple-100">إدارة الإشعارات اليومية</p>
                </div>
            </div>
            <div class="mt-4">
                <button onclick="sendDailyNotifications()" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                    إرسال الإشعارات
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- WhatsApp Integrations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">تكاملات WhatsApp Business</h2>
                <span class="text-sm text-gray-500">{{ $whatsappIntegrations->count() }} تكامل</span>
            </div>
        </div>
        
        @if($whatsappIntegrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم العمل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر تحديث</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($whatsappIntegrations as $integration)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $integration->business_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $integration->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $integration->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($integration->status === 'pending_verification' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $integration->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $integration->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <a href="{{ route('integrations.whatsapp.edit', $integration) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                                    <a href="{{ route('integrations.templates', ['type' => 'whatsapp', 'id' => $integration->id]) }}" class="text-blue-600 hover:text-blue-900">القوالب</a>
                                    <button onclick="testIntegration('whatsapp', {{ $integration->id }})" class="text-blue-600 hover:text-blue-900">اختبار</button>
                                    <button onclick="toggleStatus('whatsapp', {{ $integration->id }})" class="text-blue-600 hover:text-blue-900">
                                        {{ $integration->is_active ? 'إيقاف' : 'تشغيل' }}
                                    </button>
                                    <form method="POST" action="{{ route('integrations.destroy', ['type' => 'whatsapp', 'id' => $integration->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا التكامل؟')">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد تكاملات WhatsApp</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء تكامل WhatsApp Business جديد.</p>
                <div class="mt-6">
                    <a href="{{ route('integrations.whatsapp.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-blue-100 bg-blue-600 hover:bg-blue-700 hover:text-white">
                        إضافة تكامل WhatsApp
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Facebook Integrations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">تكاملات Facebook Messenger</h2>
                <span class="text-sm text-gray-500">{{ $facebookIntegrations->count() }} تكامل</span>
            </div>
        </div>
        
        @if($facebookIntegrations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الصفحة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">معرف الصفحة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">آخر تحديث</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($facebookIntegrations as $integration)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $integration->page_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $integration->page_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $integration->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($integration->status === 'pending_verification' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $integration->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $integration->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <a href="{{ route('integrations.facebook.edit', $integration) }}" class="text-blue-600 hover:text-blue-900">تعديل</a>
                                    <a href="{{ route('integrations.templates', ['type' => 'facebook', 'id' => $integration->id]) }}" class="text-blue-600 hover:text-blue-900">القوالب</a>
                                    <button onclick="testIntegration('facebook', {{ $integration->id }})" class="text-blue-600 hover:text-blue-900">اختبار</button>
                                    <button onclick="toggleStatus('facebook', {{ $integration->id }})" class="text-blue-600 hover:text-blue-900">
                                        {{ $integration->is_active ? 'إيقاف' : 'تشغيل' }}
                                    </button>
                                    <form method="POST" action="{{ route('integrations.destroy', ['type' => 'facebook', 'id' => $integration->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا التكامل؟')">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد تكاملات Facebook</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء تكامل Facebook Messenger جديد.</p>
                <div class="mt-6">
                    <a href="{{ route('integrations.facebook.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        إضافة تكامل Facebook
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Test Integration Modal -->
<div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">اختبار التكامل</h3>
            <form id="testForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 text-right mb-2">رقم الهاتف / معرف الصفحة</label>
                    <input type="text" id="testIdentifier" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 text-right mb-2">رسالة الاختبار</label>
                    <textarea id="testMessage" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>مرحباً! هذه رسالة اختبار للتكامل.</textarea>
                </div>
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeTestModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        إرسال الاختبار
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentIntegrationType = '';
let currentIntegrationId = '';

function testIntegration(type, id) {
    currentIntegrationType = type;
    currentIntegrationId = id;
    document.getElementById('testModal').classList.remove('hidden');
}

function closeTestModal() {
    document.getElementById('testModal').classList.add('hidden');
}

document.getElementById('testForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const identifier = document.getElementById('testIdentifier').value;
    const message = document.getElementById('testMessage').value;
    
    fetch(`/integrations/${currentIntegrationType}/${currentIntegrationId}/test`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            test_phone: identifier,
            test_message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال رسالة الاختبار بنجاح!');
        } else {
            alert('فشل في إرسال رسالة الاختبار: ' + data.message);
        }
        closeTestModal();
    })
    .catch(error => {
        alert('حدث خطأ أثناء الاختبار');
        closeTestModal();
    });
});

function toggleStatus(type, id) {
    fetch(`/integrations/${type}/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('فشل في تحديث الحالة');
        }
    })
    .catch(error => {
        alert('حدث خطأ أثناء تحديث الحالة');
    });
}

function sendDailyNotifications() {
    if (confirm('هل تريد إرسال الإشعارات اليومية الآن؟')) {
        fetch('/integrations/send-daily-notifications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال الإشعارات اليومية بنجاح!');
            } else {
                alert('فشل في إرسال الإشعارات: ' + data.message);
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء إرسال الإشعارات');
        });
    }
}
</script>
@endsection
