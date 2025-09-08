<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'admin@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234567',
            'role' => 'admin',
            'is_active' => true,
            'specialization' => 'إدارة عامة',
            'notes' => 'مدير النظام'
        ]);

        // Create staff users
        User::create([
            'name' => 'فاطمة علي',
            'email' => 'fatima@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234568',
            'role' => 'staff',
            'is_active' => true,
            'specialization' => 'خدمات الزواج',
            'notes' => 'موظفة متخصصة في خدمات الزواج'
        ]);

        User::create([
            'name' => 'محمد عبدالله',
            'email' => 'mohammed@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234569',
            'role' => 'staff',
            'is_active' => true,
            'specialization' => 'خدمات الطلاق',
            'notes' => 'موظف متخصص في خدمات الطلاق'
        ]);

        User::create([
            'name' => 'سارة أحمد',
            'email' => 'sara@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234570',
            'role' => 'staff',
            'is_active' => true,
            'specialization' => 'خدمات التوثيق',
            'notes' => 'موظفة متخصصة في خدمات التوثيق'
        ]);

        // Create partner users
        User::create([
            'name' => 'عبدالرحمن السعد',
            'email' => 'abdulrahman@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234571',
            'role' => 'partner',
            'is_active' => true,
            'specialization' => 'محامي متخصص',
            'notes' => 'الشيخ محامي متخصص في القانون العائلي'
        ]);

        User::create([
            'name' => 'خديجة المطيري',
            'email' => 'khadija@maazoun.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234572',
            'role' => 'partner',
            'is_active' => true,
            'specialization' => 'مستشارة أسرية',
            'notes' => 'الشيخة مستشارة أسرية متخصصة'
        ]);

        $this->command->info('تم إنشاء المستخدمين بنجاح!');
    }
}
