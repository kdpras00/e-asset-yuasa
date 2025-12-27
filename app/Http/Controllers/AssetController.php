<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();
        
        if ($request->has('search')) {
            $search = $request->search;

            // SCANNER LOGIC: If exact match on Code/SAP Code, redirect to detail immediately
            $exactMatch = Asset::where('code', $search)
                               ->orWhere('sap_code', $search)
                               ->first();

            if ($exactMatch) {
                return redirect()->route('assets.show', $exactMatch->id);
            }

            // Normal Search
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('sap_code', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Group filter removed

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        } else {
             // Default to showing all? Or maybe fixed only? 
             // If user goes to "Asset List" usually implies fixed. But let's keep all for now or check current behavior.
             // Actually, if I change the link to type=fixed, it handles it. 
             // But if I go to just /assets, what should I see? 
             // I'll leave it as is, showing all if no filter.
        }

        $assets = $query->paginate(10);
        return view('assets.index', compact('assets'));
    }
    
    public function dashboard()
    {
        // Totals
        $currentMonthAssets = Asset::whereMonth('created_at', now()->month)->count();
        $lastMonthAssets = Asset::whereMonth('created_at', now()->subMonth()->month)->count();
        
        $growth = 0;
        if ($lastMonthAssets > 0) {
            $growth = (($currentMonthAssets - $lastMonthAssets) / $lastMonthAssets) * 100;
        } elseif ($currentMonthAssets > 0) {
            $growth = 100;
        }

        $summary = [
            'total_assets' => Asset::sum('quantity'),
            // 'total_groups' => Asset::distinct('group')->count('group'), // Removed
            'total_categories' => Asset::distinct('category')->count('category'),
            'month_growth' => round($growth, 1),
            'vs_last_month' => $currentMonthAssets - $lastMonthAssets
        ];
        
        // Data for charts
        // Group Chart Removed
        /*
        $groupByGroup = Asset::selectRaw('`group`, count(*) as count')
            ->groupBy('group')
            ->pluck('count', 'group');
        */

        $groupByCategory = Asset::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        $charts = [
            /* 'groups' => [
                'labels' => $groupByGroup->keys()->map(fn($k) => $k ?: 'Uncategorized')->toArray(),
                'data' => $groupByGroup->values()->toArray(),
            ], */
            'categories' => [
                'labels' => $groupByCategory->keys()->toArray(),
                'data' => $groupByCategory->values()->toArray(),
            ]
        ];

        return view('dashboard.index', compact('summary', 'charts'));
    }

    // Groups method removed

    public function categories()
    {
        $categories = Asset::selectRaw('category, count(*) as count, sum(price) as total_price, max(created_at) as last_update')
            ->groupBy('category')
            ->get();
        return view('assets.categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:assets',
            // 'sap_code' => 'nullable|unique:assets', // Auto-generated
            'category' => 'required',
            // 'group' => 'required|string', // Removed
            'department' => 'nullable',
            'section' => 'nullable',
            'pic' => 'nullable',
            'status' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        // Initialize stock to be equal to quantity if not set
        if (!isset($data['stock'])) {
            $data['stock'] = $data['quantity'];
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset = Asset::create($data);

        // Document upload is now handled separately or via update, but keeping this basic support
        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset->load('documents');
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:assets,code,' . $asset->id,
            'sap_code' => 'nullable|unique:assets,sap_code,' . $asset->id,
            'category' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);
        
        return redirect()->route('assets.show', $asset->id)->with('success', 'Asset updated successfully');
    }

    // Document Upload Methods
    public function createDocument(Asset $asset)
    {
        return view('documents.create', compact('asset'));
    }

    public function storeDocument(Request $request, Asset $asset)
    {
        $request->validate([
            'acq_date' => 'nullable|date',
            'documents.*' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $types = ['purchase', 'disposal', 'maintenance'];
        $uploaded = 0;

        foreach ($types as $type) {
             if ($request->hasFile($type)) {
                $path = $request->file($type)->store('public/documents');
                Document::create([
                    'asset_id' => $asset->id,
                    'type' => $type,
                    'file_path' => $path,
                    'status' => 'pending',
                ]);
                $uploaded++;
             }
        }

        // Just saving acq_date if provided via this form context to the asset
        if($request->filled('acq_date')){
             // Assuming acq_date maps to purchase_date or a new field. Using purchase_date.
             $asset->update(['purchase_date' => $request->acq_date]);
        }
        
        if ($uploaded == 0 && !$request->filled('acq_date')) {
            return back()->with('error', 'Please upload at least one document or set date.');
        }

        return redirect()->route('assets.show', $asset->id)->with('success', 'Documents uploaded successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully');
    }
}
