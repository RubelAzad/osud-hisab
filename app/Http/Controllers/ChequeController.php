<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChequeController extends Controller
{
    public function index(Request $request): View
    {
        $cheques = Cheque::with(['payment.customer', 'payment.supplier'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        return view('cheques.index', compact('cheques'));
    }

    public function updateStatus(Request $request, Cheque $cheque): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([Cheque::STATUS_PENDING, Cheque::STATUS_CLEARED, Cheque::STATUS_BOUNCED])],
        ]);

        $cheque->update($data);

        return back()->with('success', 'Cheque status updated.');
    }
}
