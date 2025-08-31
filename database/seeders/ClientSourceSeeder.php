<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClientSource;

class ClientSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'واتساب',
                'type' => 'whatsapp',
                'description' => 'عملاء يأتون من خلال رسائل الواتساب',
                'is_active' => true,
            ],
            [
                'name' => 'فيسبوك',
                'type' => 'facebook',
                'description' => 'عملاء يأتون من خلال صفحة الفيسبوك',
                'is_active' => true,
            ],
            [
                'name' => 'الموقع الإلكتروني',
                'type' => 'website',
                'description' => 'عملاء يأتون من خلال الموقع الإلكتروني',
                'is_active' => true,
            ],
            [
                'name' => 'إحالة من عميل',
                'type' => 'referral',
                'description' => 'عملاء يأتون من خلال إحالة عملاء سابقين',
                'is_active' => true,
            ],
            [
                'name' => 'إعلانات جوجل',
                'type' => 'other',
                'description' => 'عملاء يأتون من خلال إعلانات جوجل',
                'is_active' => true,
            ],
            [
                'name' => 'إنستغرام',
                'type' => 'other',
                'description' => 'عملاء يأتون من خلال صفحة الإنستغرام',
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            ClientSource::create($source);
        }
    }
}
