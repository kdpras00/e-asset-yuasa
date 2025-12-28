<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\AssetDisposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Page: Pembelian (New Asset)
     */
    public function purchase()
    {
        return view('transactions.purchase');
    }

    /**
     * Page: Perbaikan (Maintenance)
     */
    public function maintenance()
    {
        $assets = Asset::select('id', 'name', 'code')->orderBy('name')->get();
        return view('transactions.maintenance', compact('assets'));
    }

    /**
     * Page: Pemusnahan (Disposal)
     */
    public function disposal()
    {
        $assets = Asset::select('id', 'name', 'code')->orderBy('name')->get();
        return view('transactions.disposal', compact('assets'));
    }

    /**
     * Tab 1: Pembelian (New Asset)
     */
    public function storePurchase(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department' => 'required', // Ruangan / Divisi
            'image' => 'required|image|max:2048',
            'warranty_proof' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            // Default fields
            'quantity' => 'required|integer|min:1',
            'category' => 'required', // Needed for creating asset, maybe hidden or default
            'code' => 'nullable|unique:assets,code', // Validation for unique code
            // 'status' => 'required', // Removed, we force pending
        ]);

        $data = $request->all();
        // If code is empty, generate it? The model does this on 'booted'.
        // But if user provides it, it must be unique.
        $data['status'] = 'pending'; // Force pending for approval

        // Handle File Uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }
        if ($request->hasFile('warranty_proof')) {
            $data['warranty_proof'] = $request->file('warranty_proof')->store('warranties', 'public');
        }

        // Defaults or Mapped fields
        // 'department' maps to 'department' in DB.
        // 'description' maps to 'description'.
        
        $asset = Asset::create($data);

        return redirect()->route('transactions.purchase')->with('success', 'Aset baru berhasil diajukan. Menunggu persetujuan pimpinan.');
    }

    /**
     * Tab 2: Perbaikan (Maintenance)
     */
    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'room' => 'required', // Ruangan / Divisi
            'image' => 'required|image|max:2048', // Bukti Kerusakan
            'warranty_status' => 'required|in:Ada,Tidak,Habis',
            'cost' => 'required|numeric|min:0',
            'description' => 'required', // Penjelasan kerusakan
        ]);

        $data = $request->all();
        $data['start_date'] = now(); // Default start date since it's "reporting now"
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('maintenance', 'public');
        }

        AssetMaintenance::create($data);

        // Update Asset Status or Room if needed?
        // Usually maintenance implies asset is not fully active, but let's just log it for now or set to maintenance.
        Asset::find($request->asset_id)->update(['status' => 'maintenance']);

        return redirect()->route('transactions.maintenance')->with('success', 'Laporan perbaikan berhasil dikirim.');
    }

    /**
     * Tab 3: Pemusnahan (Disposal) - Bulk Input
     */
    public function storeDisposal(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.asset_id' => 'required|exists:assets,id',
            // 'items.*.name' => 'required', // Removed logic constraint
             'items.*.image' => 'required|image|max:2048',
             'items.*.description' => 'required',
        ]);

        DB::transaction(function() use ($request) {
            foreach ($request->items as $item) {
                $path = null;
                if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $item['image']->store('disposal', 'public');
                }

                AssetDisposal::create([
                    'asset_id' => $item['asset_id'],
                    'reason' => $item['description'],
                    'disposal_date' => now(),
                    'method' => 'destroyed', // Default or implicit
                    'notes' => 'Bulk input from Laporan',
                    'approved_by' => null, // Waiting approval
                ]);

                // Update Status
                Asset::where('id', $item['asset_id'])->update(['status' => 'pending_disposal']);
            }
        });

        return redirect()->route('transactions.disposal')->with('success', 'Laporan pemusnahan dikirim. Menunggu approval.');
    }
}
