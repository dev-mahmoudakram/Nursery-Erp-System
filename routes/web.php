<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\B2bContractController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\GovernmentTenderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NurseryController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\StorefrontAdmin\CouponController;
use App\Http\Controllers\StorefrontAdmin\OnlineOrderController;
use App\Http\Controllers\StorefrontAdmin\ReviewModerationController;
use App\Http\Controllers\StorefrontAdmin\StorefrontProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/**
 * كل ما بعد هذا السطر يتطلب تسجيل دخول (موظف داخلي) + فحص دور فعلي عبر middleware('role:...').
 * تسجيل الـ Middleware مطلوب في bootstrap/app.php أو Kernel.php - راجع README.
 * هذا تطبيق فعلي لمصفوفة الأدوار في وثيقة Security & Infrastructure، وليس توثيقًا فقط.
 */
Route::middleware(['auth'])->group(function () {

    Route::redirect('/', '/dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== موديول 1: المشاتل والمخزون =====
    // القراءة متاحة لكل الأدوار الداخلية؛ الكتابة مقيّدة حسب الدور
    Route::resource('nurseries', NurseryController::class)->only(['index']);
    Route::resource('items', ItemController::class)->only(['index', 'show']);
    Route::resource('batches', BatchController::class)->only(['index', 'create', 'store', 'show']);

    Route::middleware('role:admin,nursery_manager')->group(function () {
        Route::resource('nurseries', NurseryController::class)->except(['index']);
    });

    Route::middleware('role:admin')->group(function () {
        // تعديل الأصناف وقوائم الأسعار مركزي ومقيد - BR-SEC-02
        Route::resource('items', ItemController::class)->except(['index', 'show']);
    });

    Route::middleware('role:admin,nursery_manager,inventory_keeper')->group(function () {
        Route::post('batches/{batch}/status', [BatchController::class, 'changeStatus'])->name('batches.status');
        Route::post('batches/{batch}/photo', [BatchController::class, 'uploadPhoto'])->name('batches.photo');
    });

    // ===== موديول 2: CRM والمبيعات =====
    Route::resource('customers', CustomerController::class)->only(['index', 'show']);
    Route::resource('opportunities', OpportunityController::class)->only(['index', 'show']);
    Route::resource('quotations', QuotationController::class)->only(['index', 'show']);
    Route::resource('sales-orders', SalesOrderController::class)->only(['index', 'show']);
    Route::resource('invoices', InvoiceController::class)->only(['index', 'show']);

    Route::middleware('role:admin,sales_rep')->group(function () {
        Route::resource('customers', CustomerController::class)->only(['create', 'store', 'edit', 'update']);
        Route::resource('opportunities', OpportunityController::class)->only(['create', 'store']);
        Route::post('opportunities/{opportunity}/stage', [OpportunityController::class, 'updateStage'])->name('opportunities.stage');
        Route::resource('quotations', QuotationController::class)->only(['create', 'store']);
        Route::post('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
    });

    Route::middleware('role:admin,nursery_manager,inventory_keeper')->group(function () {
        Route::post('sales-orders/{salesOrder}/items/{item}/deliver', [SalesOrderController::class, 'deliverItem'])->name('sales-orders.deliver-item');
    });

    Route::middleware('role:admin,accountant')->group(function () {
        Route::post('sales-orders/{salesOrder}/invoice', [SalesOrderController::class, 'generateInvoice'])->name('sales-orders.generate-invoice');
        Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'recordPayment'])->name('invoices.record-payment');
    });

    // ===== موديول 3: المشتريات والموردون =====
    Route::resource('suppliers', SupplierController::class)->only(['index']);
    Route::resource('purchase-requests', PurchaseRequestController::class)->only(['index']);
    Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'show']);

    Route::middleware('role:admin,nursery_manager,inventory_keeper')->group(function () {
        Route::resource('suppliers', SupplierController::class)->only(['create', 'store']);
        Route::resource('purchase-requests', PurchaseRequestController::class)->only(['create', 'store']);
        Route::resource('purchase-orders', PurchaseOrderController::class)->only(['create', 'store']);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receiveGoods'])->name('purchase-orders.receive');
    });

    Route::middleware('role:admin,nursery_manager')->group(function () {
        Route::post('purchase-requests/{purchaseRequest}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
        Route::post('purchase-requests/{purchaseRequest}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    });

    // ===== موديول 4: النقل والتجهيز والتسليم =====
    Route::resource('delivery-orders', DeliveryOrderController::class)->only(['index', 'show']);

    Route::middleware('role:admin,nursery_manager,inventory_keeper')->group(function () {
        Route::resource('vehicles', VehicleController::class)->only(['index', 'create', 'store']);
        Route::resource('drivers', DriverController::class)->only(['index', 'create', 'store']);
        Route::resource('delivery-orders', DeliveryOrderController::class)->only(['create', 'store']);
        Route::post('delivery-orders/{deliveryOrder}/confirm', [DeliveryOrderController::class, 'confirm'])->name('delivery-orders.confirm');
        Route::post('delivery-orders/{deliveryOrder}/fail', [DeliveryOrderController::class, 'fail'])->name('delivery-orders.fail');
    });

    // ===== موديول 5: B2B - قرار استراتيجي، الإدارة العليا فقط =====
    Route::middleware('role:admin')->group(function () {
        Route::resource('b2b-contracts', B2bContractController::class)->only(['index', 'create', 'store']);
    });

    // ===== موديول 6: B2G =====
    Route::resource('government-tenders', GovernmentTenderController::class)->only(['index', 'show']);

    Route::middleware('role:admin,sales_rep')->group(function () {
        Route::resource('government-tenders', GovernmentTenderController::class)->only(['create', 'store']);
        Route::post('government-tenders/{governmentTender}/evaluate', [GovernmentTenderController::class, 'evaluate'])->name('government-tenders.evaluate');
        Route::post('government-tenders/{governmentTender}/submitted', [GovernmentTenderController::class, 'markSubmitted'])->name('government-tenders.submitted');
        Route::post('government-tenders/{governmentTender}/outcome', [GovernmentTenderController::class, 'recordOutcome'])->name('government-tenders.outcome');
        Route::post('government-tenders/{governmentTender}/documents', [GovernmentTenderController::class, 'uploadDocument'])->name('government-tenders.documents.store');
    });

    Route::middleware('role:admin')->group(function () {
        // قرار الدخول النهائي في منافسة حكومية: الإدارة العليا فقط - BR-B2G-02
        Route::post('government-tenders/{governmentTender}/decide', [GovernmentTenderController::class, 'decide'])->name('government-tenders.decide');
    });

    // ===== موديول 7: B2C (إدارة المتجر) =====
    Route::prefix('storefront-admin')->name('storefront-admin.')->group(function () {
        Route::resource('orders', OnlineOrderController::class)->only(['index', 'show']);

        Route::middleware('role:admin,sales_rep,inventory_keeper')->group(function () {
            Route::post('orders/{order}/confirm', [OnlineOrderController::class, 'confirm'])->name('orders.confirm');
            Route::post('orders/{order}/cancel', [OnlineOrderController::class, 'cancel'])->name('orders.cancel');
        });

        Route::middleware('role:admin')->group(function () {
            Route::resource('products', StorefrontProductController::class)->only(['index', 'create', 'store']);
            Route::post('products/{storefrontProduct}/toggle-publish', [StorefrontProductController::class, 'togglePublish'])->name('products.toggle-publish');
            Route::resource('coupons', CouponController::class)->only(['index', 'create', 'store']);
            Route::get('reviews', [ReviewModerationController::class, 'index'])->name('reviews.index');
            Route::post('reviews/{review}/approve', [ReviewModerationController::class, 'approve'])->name('reviews.approve');
            Route::post('reviews/{review}/reject', [ReviewModerationController::class, 'reject'])->name('reviews.reject');
        });
    });
});
