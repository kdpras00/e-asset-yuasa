<?php

namespace App\Exports;

use App\Models\AssetDisposal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DisposalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AssetDisposal::with('asset')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Asset Name',
            'Asset Code',
            'Reason',
            'Disposal Date',
            'Method',
            'Notes',
            'Approved By',
            'Created At',
        ];
    }

    public function map($disposal): array
    {
        return [
            $disposal->id,
            $disposal->asset->name ?? 'Unknown',
            $disposal->asset->code ?? 'N/A',
            $disposal->reason,
            $disposal->disposal_date,
            $disposal->method,
            $disposal->notes,
            $disposal->approved_by,
            $disposal->created_at,
        ];
    }
}
