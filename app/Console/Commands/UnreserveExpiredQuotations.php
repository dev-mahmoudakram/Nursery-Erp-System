<?php

namespace App\Console\Commands;

use App\Models\Quotation;
use Illuminate\Console\Command;

class UnreserveExpiredQuotations extends Command
{
    protected $signature = 'quotations:unreserve-expired';
    protected $description = 'فك الحجز التلقائي لعروض الأسعار المنتهية الصلاحية دون قبول (BR-CRM-04 / FR-031)';

    public function handle(): int
    {
        $expired = Quotation::whereIn('status', ['draft', 'sent'])
            ->where('valid_until', '<', now()->toDateString())
            ->get();

        foreach ($expired as $quotation) {
            $quotation->unreserveStock();
            $quotation->update(['status' => 'expired']);
            $this->info("Unreserved and expired quotation #{$quotation->quotation_number}");
        }

        $this->info("Processed {$expired->count()} expired quotation(s).");

        return self::SUCCESS;
    }
}
