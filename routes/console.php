<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// فك الحجز التلقائي لعروض الأسعار المنتهية الصلاحية دون قبول (BR-CRM-04 / FR-031)
Schedule::command('quotations:unreserve-expired')->hourly();
