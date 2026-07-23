<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarcodeLabelController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DamagedMedicineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationSwitcherController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PriceGroupController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuperAdmin\PharmacyController as SuperAdminPharmacyController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\UpdatePriceController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('pharmacies', [SuperAdminPharmacyController::class, 'index'])->name('pharmacies.index');
    Route::get('pharmacies/create', [SuperAdminPharmacyController::class, 'create'])->name('pharmacies.create');
    Route::post('pharmacies', [SuperAdminPharmacyController::class, 'store'])->name('pharmacies.store');
    Route::patch('pharmacies/{pharmacy}/toggle-status', [SuperAdminPharmacyController::class, 'toggleStatus'])->name('pharmacies.toggle-status');
});

Route::middleware(['auth', 'identify.pharmacy'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    $crudResource = function (string $uri, string $controller, string $permissionPrefix, array $only = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']) {
        // 'create' (a literal /uri/create path) must be registered before 'show' (a wildcard
        // /uri/{id} path) — the router matches routes in registration order, and a wildcard
        // segment would otherwise swallow the literal "create" path first.
        if (in_array('create', $only, true) || in_array('store', $only, true)) {
            Route::resource($uri, $controller)->only(array_intersect($only, ['create', 'store']))->middleware("permission:{$permissionPrefix}.create");
        }
        if (in_array('index', $only, true) || in_array('show', $only, true)) {
            Route::resource($uri, $controller)->only(array_intersect($only, ['index', 'show']))->middleware("permission:{$permissionPrefix}.view");
        }
        if (in_array('edit', $only, true) || in_array('update', $only, true)) {
            Route::resource($uri, $controller)->only(array_intersect($only, ['edit', 'update']))->middleware("permission:{$permissionPrefix}.edit");
        }
        if (in_array('destroy', $only, true)) {
            Route::resource($uri, $controller)->only(['destroy'])->middleware("permission:{$permissionPrefix}.delete");
        }
    };

    $crudResource('categories', CategoryController::class, 'categories', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('manufacturers', ManufacturerController::class, 'manufacturers', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('generics', GenericController::class, 'generics', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('medicine-types', MedicineTypeController::class, 'medicine_types', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('units', UnitController::class, 'units', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    // Static /medicines/* sub-paths must be registered before the medicines resource's
    // /medicines/{medicine} wildcard show route, or the wildcard would swallow them.
    Route::middleware('permission:medicines.view')->group(function () {
        Route::get('medicines/barcode-labels', [BarcodeLabelController::class, 'create'])->name('medicines.barcode-labels');
        Route::get('medicines/barcode-labels/print', [BarcodeLabelController::class, 'print'])->name('medicines.barcode-labels.print');
        Route::get('medicines/export', [MedicineController::class, 'export'])->name('medicines.export');
    });
    Route::middleware('permission:medicines.create')->group(function () {
        Route::post('medicines/import', [MedicineController::class, 'import'])->name('medicines.import');
        Route::post('medicines/import-opening-stock', [MedicineController::class, 'importOpeningStock'])->name('medicines.import-opening-stock');
    });

    $crudResource('medicines', MedicineController::class, 'medicines');
    $crudResource('suppliers', SupplierController::class, 'suppliers');
    $crudResource('customers', CustomerController::class, 'customers');
    $crudResource('purchases', PurchaseController::class, 'purchases', ['index', 'create', 'store', 'show']);
    $crudResource('sales', SaleController::class, 'sales', ['index', 'create', 'store', 'show']);
    $crudResource('users', UserController::class, 'users', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('roles', RoleController::class, 'roles', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('expense-categories', ExpenseCategoryController::class, 'expense_categories', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('expenses', ExpenseController::class, 'expenses', ['index', 'create', 'store', 'edit', 'update', 'destroy']);
    $crudResource('damaged-medicines', DamagedMedicineController::class, 'damaged_medicines', ['index', 'create', 'store']);
    $crudResource('stock-adjustments', StockAdjustmentController::class, 'stock_adjustments', ['index', 'create', 'store']);
    $crudResource('sale-returns', SaleReturnController::class, 'sale_returns', ['index', 'show']);
    $crudResource('purchase-returns', PurchaseReturnController::class, 'purchase_returns', ['index', 'show']);
    $crudResource('locations', LocationController::class, 'locations', ['index', 'create', 'store', 'edit', 'update']);
    $crudResource('stock-transfers', StockTransferController::class, 'stock_transfers', ['index', 'create', 'store', 'show']);
    $crudResource('quotations', QuotationController::class, 'quotations', ['index', 'create', 'store', 'show']);
    $crudResource('drafts', DraftController::class, 'drafts', ['index', 'create', 'store', 'show']);

    $crudResource('discounts', DiscountController::class, 'discounts');
    $crudResource('price-groups', PriceGroupController::class, 'price_groups');
    Route::middleware('permission:price_groups.edit')->get('price-groups/{priceGroup}/prices', [PriceGroupController::class, 'editPrices'])->name('price-groups.prices');
    Route::middleware('permission:price_groups.edit')->put('price-groups/{priceGroup}/prices', [PriceGroupController::class, 'updatePrices'])->name('price-groups.prices.update');
    $crudResource('warranties', WarrantyController::class, 'warranties');
    $crudResource('customer-groups', CustomerGroupController::class, 'customer_groups');
    $crudResource('tax-rates', TaxRateController::class, 'tax_rates');

    Route::middleware('permission:settings.edit')->group(function () {
        Route::get('update-price', [UpdatePriceController::class, 'edit'])->name('update-price.edit');
        Route::put('update-price', [UpdatePriceController::class, 'update'])->name('update-price.update');
    });

    Route::middleware('permission:quotations.create')->post('quotations/{quotation}/convert', [QuotationController::class, 'convert'])->name('quotations.convert');
    Route::middleware('permission:drafts.create')->post('drafts/{draft}/convert', [DraftController::class, 'convert'])->name('drafts.convert');

    Route::middleware('permission:sales.view')->get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::middleware('permission:sales.edit')->patch('sales/{sale}/shipping-status', [SaleController::class, 'updateShippingStatus'])->name('sales.shipping-status');

    Route::post('locations/{location}/switch', [LocationSwitcherController::class, 'switch'])->name('locations.switch');

    Route::middleware('permission:sales.view')->get('sales/{sale}/invoice', [SaleController::class, 'downloadInvoice'])->name('sales.invoice');

    Route::middleware('permission:sale_returns.create')->group(function () {
        Route::get('sales/{sale}/return', [SaleReturnController::class, 'create'])->name('sales.returns.create');
        Route::post('sales/{sale}/return', [SaleReturnController::class, 'store'])->name('sales.returns.store');
    });

    Route::middleware('permission:purchase_returns.create')->group(function () {
        Route::get('purchases/{purchase}/return', [PurchaseReturnController::class, 'create'])->name('purchases.returns.create');
        Route::post('purchases/{purchase}/return', [PurchaseReturnController::class, 'store'])->name('purchases.returns.store');
    });

    Route::middleware('permission:payments.view')->get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::middleware('permission:payments.create')->group(function () {
        Route::get('customers/{customer}/payment', [PaymentController::class, 'createForCustomer'])->name('customers.payments.create');
        Route::post('customers/{customer}/payment', [PaymentController::class, 'storeForCustomer'])->name('customers.payments.store');
        Route::get('suppliers/{supplier}/payment', [PaymentController::class, 'createForSupplier'])->name('suppliers.payments.create');
        Route::post('suppliers/{supplier}/payment', [PaymentController::class, 'storeForSupplier'])->name('suppliers.payments.store');
    });

    Route::middleware('permission:cash_accounts.view')->group(function () {
        Route::get('cash-accounts', [CashAccountController::class, 'index'])->name('cash-accounts.index');
        Route::get('cash-accounts/{cashAccount}', [CashAccountController::class, 'show'])->name('cash-accounts.show');
    });
    Route::middleware('permission:cash_accounts.create')->group(function () {
        Route::get('cash-accounts/create', [CashAccountController::class, 'create'])->name('cash-accounts.create');
        Route::post('cash-accounts', [CashAccountController::class, 'store'])->name('cash-accounts.store');
    });

    Route::middleware('permission:cheques.view')->get('cheques', [ChequeController::class, 'index'])->name('cheques.index');
    Route::middleware('permission:cheques.edit')->patch('cheques/{cheque}/status', [ChequeController::class, 'updateStatus'])->name('cheques.update-status');

    Route::middleware('permission:activity_logs.view')->get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::middleware('permission:notifications.view')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    });

    Route::middleware('permission:settings.edit')->group(function () {
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:reports.view')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    });

    Route::middleware('permission:sales.create')->group(function () {
        Route::get('pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        Route::get('pos/receipt/{sale}', [PosController::class, 'receipt'])->name('pos.receipt');
    });
});
