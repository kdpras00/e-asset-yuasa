<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\AssetLoan::with(['user', 'asset'])->latest();

        // Filter by specific status (e.g. 'pending' for  Pengajuan)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter for History (completed/rejected/returned)
        if ($request->has('history')) {
            $query->whereIn('status', ['returned', 'rejected', 'cancelled']);
        } 
        // If not requesting history and no specific status, maybe default to active?
        // But for now, let's just let it be open unless filtered.
        // Actually, if 'history' is NOT set, we might typically want 'active' (pending, borrowed, pending_return).
        elseif (!$request->has('status')) {
             // By default show active if not asking for history specifically? 
             // Let's keep it simple: if no params, show all. 
             // The sidebar links will provide the params.
        }

        $loans = $query->paginate(10);
        return view('loans.index', compact('loans'));
    }

    public function myLoans()
    {
        // For Karyawan: See only their loans
        $loans = \App\Models\AssetLoan::with(['asset'])
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('loans.my_loans', compact('loans'));
    }

    public function create()
    {
        // Form to lend an asset
        // Filter assets that are available (not borrowed, status=baik)
        // Ideally should check if type=consumable (stock > 0) or type=fixed (not in loan)
        // For simplicity, let's just show all available assets.
        
        $assets = \App\Models\Asset::where('status', 'baik')->get();
        // Only allow lending to Karyawan (exclude admin/pimpinan)
        $users = \App\Models\User::where('role', 'karyawan')->get();
        
        return view('loans.create', compact('assets', 'users'));
    }
    
    public function store(\Illuminate\Http\Request $request) 
    {
        // Handle borrowing logic
         $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_position' => 'required|string|max:255',
            'loan_date' => 'required|date',
            'notes' => 'nullable|string',
            'amount' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $asset = \App\Models\Asset::find($request->asset_id);
                    if ($asset && $value > $asset->stock) {
                        $fail("The $attribute exceeds available stock ($asset->stock units).");
                    }
                },
            ],
        ]);

        $status = 'pending'; // All requests require approval (e.g. from Kepala Dept)

        $loan = \App\Models\AssetLoan::create([
            'asset_id' => $request->asset_id,
            'user_id' => null,
            'borrower_name' => $request->borrower_name,
            'borrower_position' => $request->borrower_position,
            'loan_date' => $request->loan_date,
            'status' => $status,
            'notes' => $request->notes,
            'amount' => $request->amount,
        ]);
        
        // Log Activity
        \App\Models\LoanActivity::create([
            'asset_loan_id' => $loan->id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'created',
            'description' => 'Loan request submitted for ' . $request->borrower_name,
        ]);

        $msg = $status === 'pending' ? 'Loan request submitted successfully.' : 'Asset successfully loaned.';
        return redirect()->route('loans.index')->with('success', $msg);
    }

    public function show($id)
    {
        $loan = \App\Models\AssetLoan::with(['user', 'asset', 'activities.user'])->findOrFail($id);
        return view('loans.show', compact('loan'));
    }
    
    public function approve($id)
    {
        $loan = \App\Models\AssetLoan::findOrFail($id);
        
        if ($loan->status == 'pending') {
            $asset = $loan->asset;
            
            // Check stock availability
            if ($asset->stock < $loan->amount) {
                return back()->with('error', 'Cannot approve loan. Insufficient stock (Requested: '.$loan->amount.', Available: '.$asset->stock.').');
            }

            // Decrement Stock
            $asset->decrement('stock', $loan->amount);
            
            // Update status
            $loan->update(['status' => 'borrowed']);
            
            // Log Activity
            \App\Models\LoanActivity::create([
                'asset_loan_id' => $loan->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'approved',
                'description' => 'Loan request approved. Stock reduced by ' . $loan->amount,
            ]);
            
            return back()->with('success', 'Loan request approved. Stock updated.');

        } elseif ($loan->status == 'pending_return') {
            // Increment stock by loan amount on return
            $loan->asset->increment('stock', $loan->amount);
            
            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);
            
            // Log Activity
            \App\Models\LoanActivity::create([
                'asset_loan_id' => $loan->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'returned',
                'description' => 'Return request approved. Stock restored by ' . $loan->amount,
            ]);
            
            return back()->with('success', 'Return request approved. Stock restored.');
        }
        
        return back();
    }

    public function reject($id)
    {
        $loan = \App\Models\AssetLoan::findOrFail($id);
        
        if ($loan->status == 'pending') {
            $loan->update(['status' => 'rejected']);
            
            // Log Activity
            \App\Models\LoanActivity::create([
                'asset_loan_id' => $loan->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'rejected',
                'description' => 'Loan request rejected.',
            ]);
            
            return back()->with('success', 'Loan request rejected.');
        } elseif ($loan->status == 'pending_return') {
            // Rejecting a return means it's still borrowed (maybe damaged or not actually returned)
            $loan->update(['status' => 'borrowed']);
            
            // Log Activity
            \App\Models\LoanActivity::create([
                'asset_loan_id' => $loan->id,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'return_rejected',
                'description' => 'Return request rejected. Status reverted to borrowed.',
            ]);
            
            return back()->with('success', 'Return request rejected. Asset status reverted to borrowed.');
        }
        
        return back();
    }

    // Direct return by admin/inventory (bypassing approval)
    public function returnAsset($id)
    {
        // Enforce approval workflow for Returns
        // When Tim Asset clicks "Mark Returned", it should go to 'pending_return'
        // Then Pimpinan approves or rejects.
        
        $loan = \App\Models\AssetLoan::findOrFail($id);
        
        // Previously this was direct return. changing to pending return request.
        $loan->update([
            'status' => 'pending_return',
            'return_date' => null, // Not returned yet
        ]);
        
        // Log Activity
        \App\Models\LoanActivity::create([
            'asset_loan_id' => $loan->id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'return_requested',
            'description' => 'Return requested by Tim Asset.',
        ]);
        
        return back()->with('success', 'Return request submitted. Waiting for approval Pimpinan.');
    }

    // Karyawan requests to return
    public function requestReturn($id)
    {
        $loan = \App\Models\AssetLoan::findOrFail($id);
        
        // Ensure only the borrower can request return
        if ($loan->user_id != \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $loan->update(['status' => 'pending_return']);
        
        // Log Activity
        \App\Models\LoanActivity::create([
            'asset_loan_id' => $loan->id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => 'return_requested',
            'description' => 'Return requested by User.',
        ]);
            
        return back()->with('success', 'Return request submitted. Waiting for approval.');
    }
}