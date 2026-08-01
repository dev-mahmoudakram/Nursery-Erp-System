<?php

namespace Database\Seeders;

use App\Models\Nursery;
use App\Models\User;
use App\Support\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * بيانات أولية أساسية فقط (ليست بيانات تجريبية كاملة) - تكفي لتسجيل أول دخول
     * ورؤية شاشة فارغة تعمل فعليًا. بيانات المشاتل والأصناف الحقيقية تُدخَل
     * لاحقًا حسب خطة ترحيل البيانات (راجع وثيقة SRS القسم 6).
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@nursery-erp.test'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'), // غيّرها فورًا في الإنتاج
                'role' => Role::ADMIN,
            ]
        );

        Nursery::firstOrCreate(
            ['code' => 'NUR-01'],
            ['name_ar' => 'المشتل الرئيسي', 'name_en' => 'Main Nursery', 'is_active' => true]
        );
    }
}
