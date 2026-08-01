<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderEvaluation extends Model
{
    protected $fillable = [
        'government_tender_id', 'expected_margin_percent', 'risk_level',
        'operational_capacity_score', 'activity_fit_score',
        'system_recommendation', 'final_decision', 'evaluated_by', 'decided_by', 'notes',
    ];

    public function tender()
    {
        return $this->belongsTo(GovernmentTender::class, 'government_tender_id');
    }

    /**
     * توصية آلية أولية (BR-B2G-02) — الإدارة العليا تتخذ القرار النهائي دائمًا،
     * هذه مجرد إشارة داعمة وليست قرارًا ملزِمًا.
     *
     * الحد الأدنى المفترض: هامش ربح >= 15%، مخاطر ليست مرتفعة، وقدرة تشغيلية وتوافق نشاط
     * لا يقلان عن 3 من 5.
     */
    public function computeSystemRecommendation(): string
    {
        $passesMargin = $this->expected_margin_percent >= 15;
        $passesRisk = $this->risk_level !== 'high';
        $passesCapacity = $this->operational_capacity_score >= 3;
        $passesFit = $this->activity_fit_score >= 3;

        return ($passesMargin && $passesRisk && $passesCapacity && $passesFit) ? 'bid' : 'no_bid';
    }
}
