<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;

class ReportController extends Controller
{
    public function index()
    {
        // Fetch all documents or filter by pending
        $documents = Document::with('asset')->orderBy('created_at', 'desc')->get();
        return view('reports.index', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::with('asset')->findOrFail($id);
        return view('reports.show', compact('document'));
    }

    public function approve(Document $document)
    {
        $document->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Document approved successfully');
    }

    public function reject(Document $document)
    {
        $document->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Document rejected');
    }

    public function create()
    {
        $assets = Asset::select('id', 'name', 'code', 'sap_code')->orderBy('name')->get();
        return view('reports.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type' => 'required',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:2048'
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            
            Document::create([
                'asset_id' => $request->asset_id,
                'type' => $request->type,
                'file_path' => $path,
                'status' => 'pending',
            ]);
            
            return redirect()->route('reports.index')->with('success', 'Document uploaded successfully.');
        }
        
        return back()->with('error', 'File upload failed.');
    }

    public function summary()
    {
        // General Stats
        $totalAssets = Asset::count();
        $totalValue = Asset::sum('price');
        
        // Status Stats
        $activeAssets = Asset::where('status', 'active')->count();
        $maintenanceAssets = Asset::where('status', 'maintenance')->count();
        $disposedAssets = Asset::where('status', 'disposed')->count();

        // Category Distribution
        $assetsByCategory = Asset::select('category', DB::raw('count(*) as total'))
                                 ->groupBy('category')
                                 ->get();
                                 
        // Recent Activities (Documents)
        $recentDocuments = Document::with('asset')
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)
                                   ->get();

        return view('reports.summary', compact(
            'totalAssets', 
            'totalValue', 
            'activeAssets', 
            'maintenanceAssets', 
            'disposedAssets',
            'assetsByCategory',
            'recentDocuments'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $type = $request->get('type', 'assets'); // assets, maintenance, disposal
        
        if ($format === 'excel') {
            switch ($type) {
                case 'maintenance':
                    return Excel::download(new \App\Exports\MaintenanceExport, 'maintenance-report.xlsx');
                case 'disposal':
                    return Excel::download(new \App\Exports\DisposalExport, 'disposal-report.xlsx');
                default:
                    return Excel::download(new AssetsExport, 'assets-report.xlsx');
            }
        }

        // PDF (Default to assets for now, or expand if needed)
        $assets = Asset::all();
        $pdf = Pdf::loadView('reports.pdf', compact('assets'));
        return $pdf->download('assets-report.pdf');
    }
}
