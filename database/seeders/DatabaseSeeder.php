<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * بيانات أولية أساسية فقط (ليست بيانات تجريبية كاملة) - تكفي لتسجيل أول دخول
     * ورؤية شاشة فارغة تعمل فعليًا. بيانات المشاتل والأصناف الحقيقية تُدخَل
     * لاحقًا حسب خطة ترحيل البيانات (راجع وثيقة SRS القسم 6).
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
    }
}
