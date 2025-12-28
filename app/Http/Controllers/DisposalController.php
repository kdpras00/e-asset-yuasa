<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDisposal;
use Illuminate\Http\Request;

class DisposalController extends Controller
{
    public function index()
    {
        $disposals = AssetDisposal::with('asset')->orderBy('created_at', 'desc')->paginate(10);
        return view('disposal.index', compact('disposals'));
    }

    public function create()
    {
        // Assets that can be disposed (active, maintenance, or broken?)
        $assets = Asset::whereIn('status', ['active', 'maintenance'])->orderBy('name')->get();
        return view('disposal.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'reason' => 'required',
            'disposal_date' => 'required|date',
            'method' => 'required',
        ]);

        AssetDisposal::create($request->all());

        // Update asset status
        Asset::find($request->asset_id)->update(['status' => 'disposed']);

        return redirect()->route('disposal.index')->with('success', 'Asset disposal recorded.');
    }
}
