<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Partner;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Task;
use App\Models\ClientOrder;
use App\Models\Conversation;
use App\Models\PartnerAssistanceRequest;
use App\Models\SupplierOrder;
use App\Models\WhatsAppSetting;
use App\Models\FacebookMessengerSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_client()
    {
        $client = Client::create([
            'name' => 'أحمد الشريف',
            'bride_name' => 'فاطمة السيد',
            'guardian_name' => 'السيد محمد',
            'phone' => '+966501234567',
            'email' => 'ahmed@example.com',
            'address' => 'الرياض، المملكة العربية السعودية',
            'status' => Client::STATUS_NEW,
        ]);

        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد الشريف',
            'status' => Client::STATUS_NEW,
        ]);

        $this->assertEquals('جديد', $client->status_label);
        $this->assertEquals('blue', $client->status_color);
        $this->assertTrue($client->isNew());
    }

    /** @test */
    public function it_can_create_a_service()
    {
        $service = Service::create([
            'name' => 'توثيق زواج',
            'category' => Service::CATEGORY_MARRIAGE,
            'description' => 'خدمة توثيق عقد الزواج',
            'price' => 500.00,
            'currency' => 'EGP',
            'duration_minutes' => 120,
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'توثيق زواج',
            'category' => Service::CATEGORY_MARRIAGE,
        ]);

        $this->assertEquals('زواج', $service->category_label);
        $this->assertEquals('500.00 EGP', $service->formatted_price);
        $this->assertEquals('2 ساعة', $service->formatted_duration);
    }

    /** @test */
    public function it_can_create_an_appointment()
    {
        $client = Client::create([
            'name' => 'أحمد الشريف',
            'phone' => '+966501234567',
        ]);

        $service = Service::create([
            'name' => 'توثيق زواج',
            'category' => Service::CATEGORY_MARRIAGE,
            'price' => 500.00,
        ]);

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDays(1),
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'service_id' => $service->id,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->assertEquals('مجدول', $appointment->status_label);
        $this->assertTrue($appointment->appointment_date->isFuture());
    }

    /** @test */
    public function it_can_create_a_partner()
    {
        $partner = Partner::create([
            'name' => 'مكتب المحامي أحمد',
            'license_number' => 'LS-987654',
            'service_scope' => 'التوثيق القانوني, الاستشارات',
            'commission_rate' => 15.00,
            'status' => Partner::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('partners', [
            'name' => 'مكتب المحامي أحمد',
            'license_number' => 'LS-987654',
        ]);

        // Debug information
        $this->assertEquals('نشط', $partner->status_label);
        $this->assertEquals('15.00%', $partner->formatted_commission);
        
        // Check the actual values
        $this->assertEquals(Partner::STATUS_ACTIVE, $partner->status);
        $this->assertTrue($partner->is_active);
        
        $this->assertTrue($partner->isActive());
    }

    /** @test */
    public function it_can_create_a_supplier()
    {
        $supplier = Supplier::create([
            'name' => 'مؤسسة الصفاء للقرطاسية',
            'contact_person' => 'أحمد سعيد',
            'phone' => '0501234567',
            'services_products' => 'كتب عقود, مستلزمات مكتبية',
            'rating' => 4.5,
            'status' => Supplier::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'مؤسسة الصفاء للقرطاسية',
            'rating' => 4.5,
        ]);

        $this->assertEquals('نشط', $supplier->status_label);
        $this->assertEquals('4.5/5', $supplier->formatted_rating);
        $this->assertTrue($supplier->isActive());
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $supplier = Supplier::create([
            'name' => 'مؤسسة الصفاء للقرطاسية',
            'services_products' => 'كتب عقود',
            'status' => Supplier::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'دفاتر عقود',
            'category' => Product::CATEGORY_BOOKS,
            'supplier_id' => $supplier->id,
            'purchase_price' => 90.00,
            'selling_price' => 140.00,
            'stock_quantity' => 75,
            'currency' => 'EGP',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'دفاتر عقود',
            'category' => Product::CATEGORY_BOOKS,
        ]);

        $this->assertEquals('كتب', $product->category_label);
        $this->assertEquals('50.00 EGP', $product->formatted_profit);
        $this->assertEquals('55.56%', $product->formatted_profit_margin);
        $this->assertFalse($product->isLowStock());
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $task = Task::create([
            'title' => 'تجهيز المستندات',
            'description' => 'جمع وتنظيم المستندات المطلوبة',
            'priority' => Task::PRIORITY_HIGH,
            'status' => Task::STATUS_PENDING,
            'due_date' => now()->addDays(2),
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'تجهيز المستندات',
            'priority' => Task::PRIORITY_HIGH,
        ]);

        $this->assertEquals('عالية', $task->priority_label);
        $this->assertEquals('معلق', $task->status_label);
        $this->assertTrue($task->canStart());
    }

    /** @test */
    public function it_can_create_a_client_order()
    {
        $client = Client::create([
            'name' => 'أحمد الشريف',
            'phone' => '+966501234567',
        ]);

        $service = Service::create([
            'name' => 'توثيق زواج',
            'category' => Service::CATEGORY_MARRIAGE,
            'price' => 500.00,
        ]);

        $order = ClientOrder::create([
            'client_id' => $client->id,
            'service_id' => $service->id,
            'status' => ClientOrder::STATUS_PENDING,
            'total_amount' => 500.00,
            'paid_amount' => 0.00,
            'currency' => 'EGP',
        ]);

        $this->assertDatabaseHas('client_orders', [
            'client_id' => $client->id,
            'service_id' => $service->id,
            'total_amount' => 500.00,
        ]);

        $this->assertEquals('500.00 EGP', $order->formatted_remaining_amount);
        $this->assertEquals('0%', $order->formatted_payment_percentage);
        $this->assertTrue($order->isUnpaid());
    }

    /** @test */
    public function it_can_create_a_conversation()
    {
        $client = Client::create([
            'name' => 'أحمد الشريف',
            'phone' => '+966501234567',
        ]);

        $conversation = Conversation::create([
            'client_id' => $client->id,
            'type' => Conversation::TYPE_WHATSAPP,
            'content' => 'مرحباً، أريد معلومات عن خدمة التوثيق',
            'direction' => Conversation::DIRECTION_INCOMING,
            'conversation_date' => now(),
        ]);

        $this->assertDatabaseHas('conversations', [
            'client_id' => $client->id,
            'type' => Conversation::TYPE_WHATSAPP,
        ]);

        $this->assertEquals('واتس اب', $conversation->type_label);
        $this->assertEquals('وارد', $conversation->direction_label);
        $this->assertTrue($conversation->isWhatsApp());
        $this->assertTrue($conversation->isIncoming());
    }

    /** @test */
    public function it_can_create_a_partner_assistance_request()
    {
        $requestingPartner = Partner::create([
            'name' => 'مكتب المحامي أحمد',
            'license_number' => 'LS-987654',
            'service_scope' => 'التوثيق القانوني',
            'status' => Partner::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $assistingPartner = Partner::create([
            'name' => 'مكتب المحامي محمد',
            'license_number' => 'LS-123456',
            'service_scope' => 'التوثيق القانوني',
            'status' => Partner::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $request = PartnerAssistanceRequest::create([
            'requesting_partner_id' => $requestingPartner->id,
            'assisting_partner_id' => $assistingPartner->id,
            'service_type' => 'توثيق زواج',
            'requested_date_time' => now()->addDays(1),
            'location' => 'الرياض',
            'status' => PartnerAssistanceRequest::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('partner_assistance_requests', [
            'requesting_partner_id' => $requestingPartner->id,
            'assisting_partner_id' => $assistingPartner->id,
        ]);

        $this->assertEquals('في الانتظار', $request->status_label);
        $this->assertTrue($request->canBeAccepted());
    }

    /** @test */
    public function it_can_create_a_supplier_order()
    {
        $supplier = Supplier::create([
            'name' => 'مؤسسة الصفاء للقرطاسية',
            'services_products' => 'كتب عقود',
            'status' => Supplier::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $order = SupplierOrder::create([
            'supplier_id' => $supplier->id,
            'order_number' => 'SO-001',
            'description' => 'دفاتر عقود (1000 نسخة)',
            'quantity' => 1000,
            'unit_price' => 5.00,
            'total_amount' => 5000.00,
            'expected_delivery_date' => now()->addDays(7),
            'status' => SupplierOrder::STATUS_PENDING,
            'currency' => 'EGP',
        ]);

        $this->assertDatabaseHas('supplier_orders', [
            'order_number' => 'SO-001',
            'supplier_id' => $supplier->id,
        ]);

        $this->assertEquals('في الانتظار', $order->status_label);
        $this->assertEquals('5.00 EGP', $order->formatted_unit_price);
        $this->assertTrue($order->canBeConfirmed());
    }

    /** @test */
    public function it_can_create_whatsapp_settings()
    {
        $settings = WhatsAppSetting::create([
            'business_name' => 'نظامنو تاريفلول',
            'phone_number' => '+966501234567',
            'status' => WhatsAppSetting::STATUS_PENDING_VERIFICATION,
        ]);

        $this->assertDatabaseHas('whatsapp_settings', [
            'business_name' => 'نظامنو تاريفلول',
            'phone_number' => '+966501234567',
        ]);

        $this->assertEquals('في انتظار التحقق', $settings->status_label);
        $this->assertFalse($settings->canSendMessages());
    }

    /** @test */
    public function it_can_create_facebook_messenger_settings()
    {
        $settings = FacebookMessengerSetting::create([
            'page_name' => 'نظامنو تاريفلول',
            'page_id' => '123456789',
            'status' => FacebookMessengerSetting::STATUS_PENDING_VERIFICATION,
        ]);

        $this->assertDatabaseHas('facebook_messenger_settings', [
            'page_name' => 'نظامنو تاريفلول',
            'page_id' => '123456789',
        ]);

        $this->assertEquals('في انتظار التحقق', $settings->status_label);
        $this->assertFalse($settings->canSendMessages());
    }
}
