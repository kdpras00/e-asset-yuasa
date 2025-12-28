<?php

namespace App\Exports;

use App\Models\AssetMaintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AssetMaintenance::with('asset')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Asset Name',
            'Asset Code',
            'Description',
            'Cost',
            'Start Date',
            'Completion Date',
            'Status',
            'Performed By',
            'Created At',
        ];
    }

    public function map($maintenance): array
    {
        return [
            $maintenance->id,
            $maintenance->asset->name ?? 'Unknown',
            $maintenance->asset->code ?? 'N/A',
            $maintenance->description,
            $maintenance->cost,
            $maintenance->start_date,
            $maintenance->completion_date,
            $maintenance->status,
            $maintenance->performed_by,
            $maintenance->created_at,
        ];
    }
}
