<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnerNewFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_partner_with_new_fields()
    {
        $partnerData = [
            'name' => 'الشيخ أحمد محمد',
            'office_name' => 'مكتب الشيخ أحمد للعقود',
            'office_address' => 'الرياض، حي النرجس، شارع الملك فهد',
            'agent_name' => 'محمد أحمد',
            'agent_phone' => '0501234567',
            'agent_email' => 'agent@example.com',
            'location_number' => 'LOC001',
            'book_number' => 'BOOK001',
            'document_number' => 'DOC001',
            'license_number' => 'LIC123456',
            'service_scope' => 'الزواج',
            'phone' => '0509876543',
            'email' => 'sheikh@example.com',
            'address' => 'الرياض، حي النرجس',
            'commission_rate' => 15.5,
            'status' => 'active',
            'is_active' => true,
        ];

        $response = $this->post(route('partners.store'), $partnerData);

        $response->assertRedirect();
        $this->assertDatabaseHas('partners', [
            'name' => 'الشيخ أحمد محمد',
            'office_name' => 'مكتب الشيخ أحمد للعقود',
            'office_address' => 'الرياض، حي النرجس، شارع الملك فهد',
            'agent_name' => 'محمد أحمد',
            'agent_phone' => '0501234567',
            'agent_email' => 'agent@example.com',
            'location_number' => 'LOC001',
            'book_number' => 'BOOK001',
            'document_number' => 'DOC001',
        ]);
    }

    public function test_can_update_partner_with_new_fields()
    {
        $partner = Partner::factory()->create();

        $updateData = [
            'name' => $partner->name,
            'license_number' => $partner->license_number,
            'service_scope' => $partner->service_scope,
            'phone' => $partner->phone,
            'office_name' => 'مكتب الشيخ أحمد المحدث',
            'office_address' => 'الدمام، حي الفيصلية، شارع الملك عبدالعزيز',
            'agent_name' => 'عبدالله محمد',
            'agent_phone' => '0507654321',
            'agent_email' => 'updated_agent@example.com',
            'location_number' => 'LOC002',
            'book_number' => 'BOOK002',
            'document_number' => 'DOC002',
            'status' => 'active',
            'is_active' => true,
        ];

        $response = $this->put(route('partners.update', $partner), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'office_name' => 'مكتب الشيخ أحمد المحدث',
            'agent_name' => 'عبدالله محمد',
            'location_number' => 'LOC002',
        ]);
    }

    public function test_partner_validation_rules()
    {
        $response = $this->post(route('partners.store'), [
            'name' => 'Test Partner',
            'license_number' => 'LIC123',
            'service_scope' => 'الزواج',
            'phone' => '0501234567',
            'status' => 'active',
            'agent_email' => 'invalid-email', // Invalid email
            'commission_rate' => 150, // Invalid commission rate (over 100)
        ]);

        $response->assertSessionHasErrors(['agent_email', 'commission_rate']);
    }

    public function test_partner_can_have_multiple_services()
    {
        $partner = Partner::factory()->create([
            'service_scope' => 'الزواج,الطلاق,التصديق على المستندات'
        ]);

        $this->assertTrue($partner->hasServiceScope('الزواج'));
        $this->assertTrue($partner->hasServiceScope('الطلاق'));
        $this->assertTrue($partner->hasServiceScope('التصديق على المستندات'));
        $this->assertFalse($partner->hasServiceScope('الوصية'));
    }

    public function test_partner_office_information()
    {
        $partner = Partner::factory()->create([
            'office_name' => 'مكتب الشيخ محمد للعقود',
            'office_address' => 'الرياض، حي النرجس، شارع الملك فهد',
            'agent_name' => 'أحمد محمد',
            'agent_phone' => '0501234567',
            'agent_email' => 'agent@example.com',
        ]);

        $this->assertEquals('مكتب الشيخ محمد للعقود', $partner->office_name);
        $this->assertEquals('الرياض، حي النرجس، شارع الملك فهد', $partner->office_address);
        $this->assertEquals('أحمد محمد', $partner->agent_name);
        $this->assertEquals('0501234567', $partner->agent_phone);
        $this->assertEquals('agent@example.com', $partner->agent_email);
    }

    public function test_partner_document_numbers()
    {
        $partner = Partner::factory()->create([
            'location_number' => 'LOC123',
            'book_number' => 'BOOK456',
            'document_number' => 'DOC789',
        ]);

        $this->assertEquals('LOC123', $partner->location_number);
        $this->assertEquals('BOOK456', $partner->book_number);
        $this->assertEquals('DOC789', $partner->document_number);
    }
}
