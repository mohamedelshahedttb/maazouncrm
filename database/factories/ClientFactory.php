<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Service;
use App\Models\ClientSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'bride_name' => $this->faker->name(),
            'guardian_name' => $this->faker->name(),
            'phone' => '05' . $this->faker->numerify('########'),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'geographical_area' => $this->faker->randomElement(['المنطقة الوسطى', 'المنطقة الشرقية', 'المنطقة الغربية', 'المنطقة الشمالية', 'المنطقة الجنوبية']),
            'governorate' => $this->faker->randomElement(['الرياض', 'الدمام', 'جدة', 'مكة المكرمة', 'المدينة المنورة']),
            'area' => $this->faker->randomElement(['حي النرجس', 'حي الفيصلية', 'حي العليا', 'حي الملز', 'حي النهضة']),
            'google_maps_link' => 'https://maps.google.com/' . $this->faker->slug(),
            'relationship_status' => $this->faker->randomElement(['والد', 'أخ', 'عم', 'خال', 'ابن العم']),
            'status' => $this->faker->randomElement(['new', 'in_progress', 'completed', 'cancelled']),
            'call_result' => $this->faker->randomElement(['interested', 'not_interested', 'follow_up_later', 'potential_client', 'confirmed_booking', 'completed_booking', 'cancelled', 'inquiry', 'client_booking', 'no_answer', 'busy_number']),
            'next_follow_up_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'document_status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected', 'incomplete']),
            'document_rejection_reason' => $this->faker->optional()->sentence(),
            'assigned_partner_id' => null,
            'job_date' => $this->faker->optional()->dateTimeBetween('now', '+60 days'),
            'job_time' => $this->faker->optional()->time(),
            'job_number' => $this->faker->optional()->bothify('JOB###'),
            'coupon_number' => $this->faker->optional()->bothify('COUPON###'),
            'final_document_delivery_date' => $this->faker->optional()->dateTimeBetween('+30 days', '+90 days'),
            'final_document_notification_sent' => $this->faker->boolean(20),
            'notes' => $this->faker->optional()->paragraph(),
            'whatsapp_number' => '05' . $this->faker->numerify('########'),
            'facebook_id' => $this->faker->optional()->numerify('##########'),
            'facebook_page_id' => $this->faker->optional()->numerify('##########'),
            'is_active' => $this->faker->boolean(90),
            'service_id' => Service::factory(),
            'source_id' => ClientSource::factory(),
        ];
    }
}