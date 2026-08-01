<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\GovernmentTender;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OnlineOrder;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * لوحة قيادة موحَّدة تجمع مؤشرات من كل الموديولات السبعة في شاشة واحدة (BR-RPT-01).
     * كل الأرقام محسوبة لحظيًا من نفس الجداول التشغيلية، وليست جداول تلخيص منفصلة
     * قد تتأخر عن الواقع.
     */
    public function index()
    {
        $readyBatches = Batch::with('item')->where('lifecycle_status', 'ready_for_sale')->get();

        $totalAvailableUnits = $readyBatches->sum(fn ($b) => $b->available_quantity);
        $totalInventoryValue = $readyBatches->sum(fn ($b) => $b->available_quantity * ($b->item->retail_price ?? 0));

        // تنبيه انخفاض المخزون: مجموع المتاح لكل صنف أقل من مخزون الأمان الخاص به (BR-ALT-01)
        $lowStockItems = Item::with('batches')
            ->get()
            ->filter(function ($item) {
                $available = $item->batches->where('lifecycle_status', 'ready_for_sale')
                    ->sum(fn ($b) => $b->available_quantity);

                return $item->safety_stock > 0 && $available < $item->safety_stock;
            })
            ->take(10);

        $salesThisMonth = SalesOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $topCustomers = Customer::withSum('salesOrders', 'total')
            ->orderByDesc('sales_orders_sum_total')
            ->take(5)
            ->get();

        $pendingOnlineOrders = OnlineOrder::where('status', 'pending_review')->count();
        $pendingPurchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'partially_received'])->count();

        $overdueInvoices = Invoice::whereIn('status', ['unpaid', 'partially_paid'])
            ->where('due_date', '<', now()->toDateString())
            ->count();

        $tenderPipeline = GovernmentTender::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        return view('dashboard.index', compact(
            'totalAvailableUnits', 'totalInventoryValue', 'lowStockItems', 'salesThisMonth',
            'topCustomers', 'pendingOnlineOrders', 'pendingPurchaseOrders', 'overdueInvoices', 'tenderPipeline'
        ));
    }
}
