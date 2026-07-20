<?php

namespace App\Services;

use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function dailySales(string $date): array
    {
        $sales = Sale::with('customer')->whereDate('sale_date', $date)->orderBy('id')->get();

        return [
            'columns' => ['Invoice', 'Customer', 'Subtotal', 'Discount', 'VAT', 'Total', 'Paid', 'Due'],
            'rows' => $sales->map(fn (Sale $s) => [
                $s->invoice_no, $s->customer->name ?? 'Walk-in', number_format($s->subtotal, 2),
                number_format($s->discount, 2), number_format($s->vat, 2), number_format($s->total, 2),
                number_format($s->paid, 2), number_format($s->due, 2),
            ])->all(),
            'total_label' => 'Total Sales',
            'total_value' => number_format($sales->sum('total'), 2),
        ];
    }

    public function monthlySales(int $year, int $month): array
    {
        $rows = Sale::whereYear('sale_date', $year)->whereMonth('sale_date', $month)
            ->selectRaw('DATE(sale_date) as day, COUNT(*) as count, SUM(total) as total')
            ->groupBy('day')->orderBy('day')->get();

        return [
            'columns' => ['Date', 'Invoices', 'Total Sales'],
            'rows' => $rows->map(fn ($r) => [$r->day, $r->count, number_format($r->total, 2)])->all(),
            'total_label' => 'Total Sales',
            'total_value' => number_format($rows->sum('total'), 2),
        ];
    }

    public function profitLoss(string $from, string $to): array
    {
        $revenue = (float) Sale::whereBetween('sale_date', [$from, $to])->sum('total');
        $cogs = (float) SaleItem::whereHas('sale', fn ($q) => $q->whereBetween('sale_date', [$from, $to]))
            ->join('medicine_batches', 'sale_items.medicine_batch_id', '=', 'medicine_batches.id')
            ->sum(DB::raw('medicine_batches.purchase_price * sale_items.qty'));
        $expenses = (float) Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $profit = $revenue - $cogs - $expenses;

        return [
            'columns' => ['Line Item', 'Amount'],
            'rows' => [
                ['Revenue', number_format($revenue, 2)],
                ['Cost of Goods Sold', number_format($cogs, 2)],
                ['Gross Profit', number_format($revenue - $cogs, 2)],
                ['Expenses', number_format($expenses, 2)],
                ['Net Profit', number_format($profit, 2)],
            ],
            'total_label' => 'Net Profit',
            'total_value' => number_format($profit, 2),
        ];
    }

    public function purchases(string $from, string $to): array
    {
        $purchases = Purchase::with('supplier')->whereBetween('purchase_date', [$from, $to])->orderBy('id')->get();

        return [
            'columns' => ['Invoice', 'Supplier', 'Subtotal', 'Discount', 'VAT+Tax', 'Total', 'Paid', 'Due'],
            'rows' => $purchases->map(fn (Purchase $p) => [
                $p->invoice_no, $p->supplier->name ?? '-', number_format($p->subtotal, 2), number_format($p->discount, 2),
                number_format($p->vat + $p->tax, 2), number_format($p->total, 2), number_format($p->paid, 2), number_format($p->due, 2),
            ])->all(),
            'total_label' => 'Total Purchases',
            'total_value' => number_format($purchases->sum('total'), 2),
        ];
    }

    public function supplierDue(): array
    {
        $suppliers = Supplier::where('balance', '>', 0)->orderByDesc('balance')->get();

        return [
            'columns' => ['Supplier', 'Phone', 'Balance Due'],
            'rows' => $suppliers->map(fn (Supplier $s) => [$s->name, $s->phone, number_format($s->balance, 2)])->all(),
            'total_label' => 'Total Due',
            'total_value' => number_format($suppliers->sum('balance'), 2),
        ];
    }

    public function customerDue(): array
    {
        $customers = Customer::where('balance', '>', 0)->orderByDesc('balance')->get();

        return [
            'columns' => ['Customer', 'Phone', 'Balance Due'],
            'rows' => $customers->map(fn (Customer $c) => [$c->name, $c->phone, number_format($c->balance, 2)])->all(),
            'total_label' => 'Total Due',
            'total_value' => number_format($customers->sum('balance'), 2),
        ];
    }

    public function stock(): array
    {
        $medicines = Medicine::withSum('batches as total_stock', 'remaining_qty')->orderBy('medicine_name')->get();

        return [
            'columns' => ['Medicine', 'Stock', 'Minimum Stock', 'Sale Price', 'Stock Value'],
            'rows' => $medicines->map(fn (Medicine $m) => [
                $m->medicine_name, $m->total_stock ?? 0, $m->minimum_stock, number_format($m->sale_price, 2),
                number_format(($m->total_stock ?? 0) * $m->sale_price, 2),
            ])->all(),
            'total_label' => 'Total Stock Value',
            'total_value' => number_format($medicines->sum(fn ($m) => ($m->total_stock ?? 0) * $m->sale_price), 2),
        ];
    }

    public function lowStock(): array
    {
        $medicines = Medicine::withSum('batches as total_stock', 'remaining_qty')
            ->havingRaw('COALESCE(total_stock, 0) <= minimum_stock')
            ->orderBy('total_stock')->get();

        return [
            'columns' => ['Medicine', 'Stock', 'Minimum Stock'],
            'rows' => $medicines->map(fn (Medicine $m) => [$m->medicine_name, $m->total_stock ?? 0, $m->minimum_stock])->all(),
        ];
    }

    public function expired(): array
    {
        $batches = MedicineBatch::with('medicine')
            ->where('remaining_qty', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', today())
            ->orderBy('expiry_date')->get();

        return [
            'columns' => ['Medicine', 'Batch No', 'Remaining Qty', 'Expiry Date'],
            'rows' => $batches->map(fn (MedicineBatch $b) => [
                $b->medicine->medicine_name ?? '-', $b->batch_no, $b->remaining_qty, $b->expiry_date->format('Y-m-d'),
            ])->all(),
        ];
    }

    public function nearExpiry(int $days): array
    {
        $batches = MedicineBatch::with('medicine')
            ->where('remaining_qty', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [today(), Carbon::today()->addDays($days)])
            ->orderBy('expiry_date')->get();

        return [
            'columns' => ['Medicine', 'Batch No', 'Remaining Qty', 'Expiry Date'],
            'rows' => $batches->map(fn (MedicineBatch $b) => [
                $b->medicine->medicine_name ?? '-', $b->batch_no, $b->remaining_qty, $b->expiry_date->format('Y-m-d'),
            ])->all(),
        ];
    }

    public function vat(string $from, string $to): array
    {
        $sales = Sale::whereBetween('sale_date', [$from, $to])->get();
        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])->get();

        return [
            'columns' => ['Line Item', 'Amount'],
            'rows' => [
                ['VAT Collected (Sales)', number_format($sales->sum('vat'), 2)],
                ['VAT Paid (Purchases)', number_format($purchases->sum('vat'), 2)],
                ['Net VAT', number_format($sales->sum('vat') - $purchases->sum('vat'), 2)],
            ],
            'total_label' => 'Net VAT',
            'total_value' => number_format($sales->sum('vat') - $purchases->sum('vat'), 2),
        ];
    }

    public function bestSelling(string $from, string $to): array
    {
        $rows = SaleItem::select('medicine_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(total) as total_revenue'))
            ->whereHas('sale', fn ($q) => $q->whereBetween('sale_date', [$from, $to]))
            ->groupBy('medicine_id')->orderByDesc('total_qty')->with('medicine')->limit(20)->get();

        return [
            'columns' => ['Medicine', 'Qty Sold', 'Revenue'],
            'rows' => $rows->map(fn ($r) => [$r->medicine->medicine_name ?? '-', $r->total_qty, number_format($r->total_revenue, 2)])->all(),
        ];
    }

    public function slowMoving(int $days): array
    {
        $recentlySoldIds = SaleItem::whereHas('sale', fn ($q) => $q->where('sale_date', '>=', Carbon::today()->subDays($days)))
            ->pluck('medicine_id')->unique();

        $medicines = Medicine::whereNotIn('id', $recentlySoldIds)
            ->withSum('batches as total_stock', 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->orderBy('medicine_name')->get();

        return [
            'columns' => ['Medicine', 'Current Stock'],
            'rows' => $medicines->map(fn (Medicine $m) => [$m->medicine_name, $m->total_stock ?? 0])->all(),
        ];
    }

    public function cashBook(string $from, string $to): array
    {
        $transactions = AccountTransaction::with('cashAccount')
            ->whereBetween('transaction_date', [$from, $to])
            ->orderBy('transaction_date')->orderBy('id')->get();

        return [
            'columns' => ['Date', 'Account', 'Type', 'Reference', 'Credit', 'Debit'],
            'rows' => $transactions->map(fn (AccountTransaction $t) => [
                $t->transaction_date->format('Y-m-d'), $t->cashAccount->account_name ?? '-', $t->type,
                trim(($t->reference ?? '').' '.($t->reference_id ? '#'.$t->reference_id : '')),
                $t->credit > 0 ? number_format($t->credit, 2) : '-', $t->debit > 0 ? number_format($t->debit, 2) : '-',
            ])->all(),
            'total_label' => 'Net Movement',
            'total_value' => number_format($transactions->sum('credit') - $transactions->sum('debit'), 2),
        ];
    }

    public function expenses(string $from, string $to): array
    {
        $expenses = Expense::with('category')->whereBetween('expense_date', [$from, $to])->orderBy('expense_date')->get();

        return [
            'columns' => ['Date', 'Category', 'Amount', 'Description'],
            'rows' => $expenses->map(fn (Expense $e) => [
                $e->expense_date->format('Y-m-d'), $e->category->name ?? '-', number_format($e->amount, 2), $e->description,
            ])->all(),
            'total_label' => 'Total Expenses',
            'total_value' => number_format($expenses->sum('amount'), 2),
        ];
    }

    public function purchaseReturns(string $from, string $to): array
    {
        $returns = PurchaseReturn::with(['purchase', 'supplier'])->whereBetween('return_date', [$from, $to])->orderBy('return_date')->get();

        return [
            'columns' => ['Date', 'Purchase Invoice', 'Supplier', 'Amount'],
            'rows' => $returns->map(fn (PurchaseReturn $r) => [
                $r->return_date->format('Y-m-d'), $r->purchase->invoice_no ?? '-', $r->supplier->name ?? '-', number_format($r->amount, 2),
            ])->all(),
            'total_label' => 'Total Returned',
            'total_value' => number_format($returns->sum('amount'), 2),
        ];
    }

    public function salesReturns(string $from, string $to): array
    {
        $returns = SaleReturn::with(['sale', 'customer'])->whereBetween('return_date', [$from, $to])->orderBy('return_date')->get();

        return [
            'columns' => ['Date', 'Sale Invoice', 'Customer', 'Refund Amount'],
            'rows' => $returns->map(fn (SaleReturn $r) => [
                $r->return_date->format('Y-m-d'), $r->sale->invoice_no ?? '-', $r->customer->name ?? 'Walk-in', number_format($r->refund_amount, 2),
            ])->all(),
            'total_label' => 'Total Refunded',
            'total_value' => number_format($returns->sum('refund_amount'), 2),
        ];
    }
}
