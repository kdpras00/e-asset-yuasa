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
        $assets = Asset::where('status', 'active')->orderBy('name')->get(); // Only active assets usually need maintenance
        return view('maintenance.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'description' => 'required',
            'start_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'cost' => 'nullable|numeric|min:0',
        ]);

        AssetMaintenance::create($request->all());

        // Update asset status to maintenance if pending
        if ($request->status == 'pending') {
             $asset = Asset::find($request->asset_id);
             $asset->update(['status' => 'maintenance']);
        }

        // If completed directly (unlikely but possible), should we set to active? 
        // Or maintenance records are just logs? 
        // Let's assume inputting a 'completed' record means it was done.
        if ($request->status == 'completed') {
             $asset = Asset::find($request->asset_id);
             $asset->update(['status' => 'active']);
        }

        return redirect()->route('maintenance.index')->with('success', 'Maintenance record created.');
    }

    public function edit(AssetMaintenance $maintenance)
    {
        return view('maintenance.edit', compact('maintenance'));
    }

    public function update(Request $request, AssetMaintenance $maintenance)
    {
        $request->validate([
            'description' => 'required',
            'start_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update($request->all());

        if ($request->status == 'completed') {
             $maintenance->asset->update(['status' => 'active']);
             $maintenance->update(['completion_date' => now()]);
        }

        return redirect()->route('maintenance.index')->with('success', 'Maintenance updated.');
    }
}
