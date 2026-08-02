<?php

namespace Database\Seeders;

use App\Models\Nursery;
use App\Models\User;
use App\Support\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * مستخدم اختباري واحد لكل دور من أدوار النظام الخمسة (راجع App\Support\Role)،
     * لتسهيل اختبار مصفوفة الصلاحيات دون الحاجة لإنشاء المستخدمين يدويًا.
     */
    public function run(): void
    {
        $nursery = Nursery::firstOrCreate(
            ['code' => 'NUR-01'],
            ['name_ar' => 'المشتل الرئيسي', 'name_en' => 'Main Nursery', 'is_active' => true]
        );

        $names = [
            Role::ADMIN => 'مدير النظام',
            Role::NURSERY_MANAGER => 'مدير المشتل',
            Role::INVENTORY_KEEPER => 'أمين المخزون',
            Role::SALES_REP => 'مندوب المبيعات',
            Role::ACCOUNTANT => 'المحاسب',
        ];

        foreach (Role::ALL as $role) {
            User::firstOrCreate(
                ['email' => "{$role}@nursery-erp.test"],
                [
                    'name' => $names[$role],
                    'password' => Hash::make('password'), // غيّرها فورًا في الإنتاج
                    'role' => $role,
                    'nursery_id' => $role === Role::ADMIN ? null : $nursery->id,
                ]
            );
        }
    }
}
