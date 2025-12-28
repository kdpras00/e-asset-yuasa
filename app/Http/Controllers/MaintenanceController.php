<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = AssetMaintenance::with('asset')->orderBy('created_at', 'desc')->paginate(10);
        return view('maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        // Include 'active', 'maintenance', 'baik' (legacy), 'rusak' (legacy)
        $assets = Asset::whereIn('status', ['active', 'maintenance', 'baik', 'rusak'])->orderBy('name')->get(); 
        return view('maintenance.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'description' => 'required',
            // 'start_date' => 'required|date', // Removed as we set it auto
            // 'status' => 'required', // Default pending
            'cost' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'room' => 'required',
            'warranty_status' => 'required',
        ]);

        $data = $request->all();
        $data['start_date'] = now();
        $data['status'] = 'pending'; // Default
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('maintenance', 'public');
        }

        AssetMaintenance::create($data);

        // Update asset status to maintenance immediately? Or wait for approval?
        // Let's set to maintenance so it's not available.
        $asset = Asset::find($request->asset_id);
        $asset->update(['status' => 'maintenance']);

        return redirect()->route('maintenance.index')->with('success', 'Pengajuan perbaikan berhasil dikirim.');
    }

    public function edit(AssetMaintenance $maintenance)
    {
        return view('maintenance.edit', compact('maintenance'));
    }

    public function approve(AssetMaintenance $maintenance)
    {
        $maintenance->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Pengajuan perbaikan disetujui.');
    }

    public function reject(AssetMaintenance $maintenance)
    {
        $maintenance->update(['status' => 'rejected']);
        $maintenance->asset->update(['status' => 'active']); // Revert asset to active
        return redirect()->back()->with('success', 'Pengajuan perbaikan ditolak.');
    }

    public function complete(AssetMaintenance $maintenance)
    {
        $maintenance->update(['status' => 'completed', 'completion_date' => now()]);
        $maintenance->asset->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Perbaikan selesai.');
    }

    public function update(Request $request, AssetMaintenance $maintenance)
    {
        // Keep generic update for editing details
        $request->validate([
            'description' => 'required',
            'cost' => 'nullable|numeric|min:0',
        ]);
        $maintenance->update($request->all());
        
        return redirect()->route('maintenance.index')->with('success', 'Data perbaikan diperbarui.');
    }
}
