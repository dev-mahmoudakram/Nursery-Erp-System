<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GovernmentTender;
use App\Models\TenderEvaluation;
use Illuminate\Http\Request;

class GovernmentTenderController extends Controller
{
    public function index()
    {
        $tenders = GovernmentTender::with('latestEvaluation')->latest()->paginate(20);
        return view('government_tenders.index', compact('tenders'));
    }

    public function create()
    {
        return view('government_tenders.create', [
            'customers' => Customer::where('customer_type', 'government')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'government_entity_name' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'announcement_date' => 'nullable|date',
            'submission_deadline' => 'required|date',
            'tender_document_fee' => 'nullable|numeric',
            'bid_bond_amount' => 'nullable|numeric',
            'estimated_value' => 'nullable|numeric',
        ]);

        $tender = GovernmentTender::create($data + [
            'tender_number' => 'TND-' . now()->format('Ymd') . '-' . str_pad((string) (GovernmentTender::count() + 1), 4, '0', STR_PAD_LEFT),
            'status' => 'evaluating',
        ]);

        return redirect()->route('government-tenders.show', $tender)->with('success', __('messages.tender.created'));
    }

    public function show(GovernmentTender $governmentTender)
    {
        $governmentTender->load('evaluations', 'customer', 'quotation', 'documents');
        return view('government_tenders.show', ['tender' => $governmentTender]);
    }

    /**
     * رفع فعلي لمستند من مستندات المنافسة (كراسة شروط، خطاب ضمان، إلخ).
     */
    public function uploadDocument(Request $request, GovernmentTender $governmentTender)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $path = $request->file('file')->store('tender-documents', 'public');

        $governmentTender->documents()->create([
            'name' => $request->name,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', __('messages.tender.document_uploaded'));
    }

    /**
     * تقييم فني/مالي أولي للمنافسة، وتوليد توصية آلية (BR-B2G-02). القرار النهائي
     * يبقى دائمًا بيد الإدارة العليا عبر decide() أدناه.
     */
    public function evaluate(Request $request, GovernmentTender $governmentTender)
    {
        $data = $request->validate([
            'expected_margin_percent' => 'required|integer|min:0|max:100',
            'risk_level' => 'required|in:low,medium,high',
            'operational_capacity_score' => 'required|integer|min:1|max:5',
            'activity_fit_score' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $evaluation = new TenderEvaluation($data + [
            'government_tender_id' => $governmentTender->id,
            'evaluated_by' => auth()->id(),
        ]);
        $evaluation->system_recommendation = $evaluation->computeSystemRecommendation();
        $evaluation->save();

        return back()->with('success', __('messages.tender.evaluated'));
    }

    /**
     * قرار الإدارة العليا النهائي بالدخول في المنافسة أم لا (BP-05 خطوة 3).
     */
    public function decide(Request $request, GovernmentTender $governmentTender)
    {
        $request->validate(['final_decision' => 'required|in:bid,no_bid']);

        $evaluation = $governmentTender->latestEvaluation()->firstOrFail();
        $evaluation->update(['final_decision' => $request->final_decision, 'decided_by' => auth()->id()]);

        $governmentTender->update([
            'status' => $request->final_decision === 'bid' ? 'preparing_offer' : 'decided_no_bid',
        ]);

        return back()->with('success', __('messages.tender.decided'));
    }

    public function markSubmitted(GovernmentTender $governmentTender)
    {
        $governmentTender->update(['status' => 'submitted']);
        return back()->with('success', __('messages.tender.submitted'));
    }

    /**
     * تسجيل نتيجة المنافسة مع إلزام سبب الفوز/الخسارة للتعلم المؤسسي (BP-05 خطوة 6).
     */
    public function recordOutcome(Request $request, GovernmentTender $governmentTender)
    {
        $request->validate([
            'status' => 'required|in:won,lost',
            'outcome_reason' => 'required|string|max:500',
        ]);

        $governmentTender->update([
            'status' => $request->status,
            'outcome_reason' => $request->outcome_reason,
        ]);

        return back()->with('success', __('messages.tender.outcome_recorded'));
    }
}
