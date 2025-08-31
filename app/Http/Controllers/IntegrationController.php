<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppSetting;
use App\Models\FacebookMessengerSetting;
use App\Services\WhatsAppBusinessService;
use App\Services\FacebookMessengerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    protected $whatsappService;
    protected $facebookService;

    public function __construct()
    {
        $this->whatsappService = app(WhatsAppBusinessService::class);
        $this->facebookService = app(FacebookMessengerService::class);
    }

    /**
     * Display integrations dashboard
     */
    public function index()
    {
        $whatsappIntegrations = WhatsAppSetting::all();
        $facebookIntegrations = FacebookMessengerSetting::all();

        return view('integrations.index', compact('whatsappIntegrations', 'facebookIntegrations'));
    }

    /**
     * Show WhatsApp integration form
     */
    public function createWhatsApp()
    {
        return view('integrations.whatsapp.create');
    }

    /**
     * Store WhatsApp integration
     */
    public function storeWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'access_token' => 'required|string',
            'business_account_id' => 'required|string',
            'webhook_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,pending_verification',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $whatsapp = WhatsAppSetting::create($request->all());
            
            // Initialize default message templates and auto-replies
            $whatsapp->initializeDefaults();

            return redirect()->route('integrations.index')
                ->with('success', 'تم إنشاء تكامل WhatsApp بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to create WhatsApp integration: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في إنشاء التكامل. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * Show WhatsApp integration edit form
     */
    public function editWhatsApp(WhatsAppSetting $whatsapp)
    {
        return view('integrations.whatsapp.edit', compact('whatsapp'));
    }

    /**
     * Update WhatsApp integration
     */
    public function updateWhatsApp(Request $request, WhatsAppSetting $whatsapp)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'access_token' => 'required|string',
            'business_account_id' => 'required|string',
            'webhook_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,pending_verification',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $whatsapp->update($request->all());
            
            return redirect()->route('integrations.index')
                ->with('success', 'تم تحديث تكامل WhatsApp بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to update WhatsApp integration: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في تحديث التكامل. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * Show Facebook Messenger integration form
     */
    public function createFacebook()
    {
        return view('integrations.facebook.create');
    }

    /**
     * Store Facebook Messenger integration
     */
    public function storeFacebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string|max:255',
            'page_id' => 'required|string',
            'access_token' => 'required|string',
            'webhook_url' => 'nullable|url',
            'verify_token' => 'nullable|string',
            'status' => 'required|in:active,inactive,pending_verification',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $facebook = FacebookMessengerSetting::create($request->all());
            
            // Initialize default message templates and auto-replies
            $facebook->initializeDefaults();

            return redirect()->route('integrations.index')
                ->with('success', 'تم إنشاء تكامل Facebook Messenger بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to create Facebook integration: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في إنشاء التكامل. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * Show Facebook Messenger integration edit form
     */
    public function editFacebook(FacebookMessengerSetting $facebook)
    {
        return view('integrations.facebook.edit', compact('facebook'));
    }

    /**
     * Update Facebook Messenger integration
     */
    public function updateFacebook(Request $request, FacebookMessengerSetting $facebook)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string|max:255',
            'page_id' => 'required|string',
            'access_token' => 'required|string',
            'webhook_url' => 'nullable|url',
            'verify_token' => 'nullable|string',
            'status' => 'required|in:active,inactive,pending_verification',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $facebook->update($request->all());
            
            return redirect()->route('integrations.index')
                ->with('success', 'تم تحديث تكامل Facebook Messenger بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to update Facebook integration: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في تحديث التكامل. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * Test WhatsApp integration
     */
    public function testWhatsApp(WhatsAppSetting $whatsapp, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_phone' => 'required|string|max:20',
            'test_message' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة'
            ]);
        }

        try {
            $success = $this->whatsappService->sendTextMessage(
                $request->test_phone,
                $request->test_message,
                $whatsapp->id
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إرسال رسالة الاختبار بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إرسال رسالة الاختبار'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error("WhatsApp test failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الاختبار'
            ]);
        }
    }

    /**
     * Test Facebook Messenger integration
     */
    public function testFacebook(FacebookMessengerSetting $facebook, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_page_id' => 'required|string',
            'test_message' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة'
            ]);
        }

        try {
            $success = $this->facebookService->sendTextMessage(
                $request->test_page_id,
                $request->test_message,
                $facebook->id
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إرسال رسالة الاختبار بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إرسال رسالة الاختبار'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error("Facebook test failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الاختبار'
            ]);
        }
    }

    /**
     * Toggle integration status
     */
    public function toggleStatus(Request $request, $type, $id)
    {
        try {
            if ($type === 'whatsapp') {
                $integration = WhatsAppSetting::findOrFail($id);
            } elseif ($type === 'facebook') {
                $integration = FacebookMessengerSetting::findOrFail($id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع تكامل غير صحيح'
                ]);
            }

            $integration->update(['is_active' => !$integration->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة التكامل',
                'is_active' => $integration->is_active
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to toggle integration status: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث الحالة'
            ]);
        }
    }

    /**
     * Delete integration
     */
    public function destroy($type, $id)
    {
        try {
            if ($type === 'whatsapp') {
                $integration = WhatsAppSetting::findOrFail($id);
            } elseif ($type === 'facebook') {
                $integration = FacebookMessengerSetting::findOrFail($id);
            } else {
                return redirect()->back()->with('error', 'نوع تكامل غير صحيح');
            }

            $integration->delete();

            return redirect()->route('integrations.index')
                ->with('success', 'تم حذف التكامل بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to delete integration: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في حذف التكامل');
        }
    }

    /**
     * Show message templates management
     */
    public function messageTemplates($type, $id)
    {
        try {
            if ($type === 'whatsapp') {
                $integration = WhatsAppSetting::findOrFail($id);
            } elseif ($type === 'facebook') {
                $integration = FacebookMessengerSetting::findOrFail($id);
            } else {
                return redirect()->back()->with('error', 'نوع تكامل غير صحيح');
            }

            return view('integrations.templates', compact('integration', 'type'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'التكامل غير موجود');
        }
    }

    /**
     * Update message templates
     */
    public function updateTemplates(Request $request, $type, $id)
    {
        try {
            if ($type === 'whatsapp') {
                $integration = WhatsAppSetting::findOrFail($id);
            } elseif ($type === 'facebook') {
                $integration = FacebookMessengerSetting::findOrFail($id);
            } else {
                return redirect()->back()->with('error', 'نوع تكامل غير صحيح');
            }

            $templates = $request->input('templates', []);
            $autoReplies = $request->input('auto_replies', []);

            $integration->update([
                'message_templates' => $templates,
                'auto_replies' => $autoReplies
            ]);

            return redirect()->back()
                ->with('success', 'تم تحديث القوالب بنجاح');
                
        } catch (\Exception $e) {
            Log::error("Failed to update templates: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'فشل في تحديث القوالب');
        }
    }

    /**
     * Send daily notifications manually
     */
    public function sendDailyNotifications()
    {
        try {
            // Send WhatsApp notifications
            $whatsappResult = $this->whatsappService->sendDailyAdminNotifications();
            
            // Send client reminders
            $clientRemindersResult = $this->whatsappService->sendClientAppointmentReminders();

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الإشعارات اليومية',
                'whatsapp_admin' => $whatsappResult,
                'client_reminders' => $clientRemindersResult
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send daily notifications: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال الإشعارات'
            ]);
        }
    }
}
