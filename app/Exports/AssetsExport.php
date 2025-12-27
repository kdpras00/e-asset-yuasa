<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Asset::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Code',
            'SAP Code',
            'Category',
            'Location',
            'Status',
            'Price',
            'Purchase Date',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->id,
            $asset->name,
            $asset->code,
            $asset->sap_code,
            $asset->category,
            $asset->location,
            $asset->status,
            $asset->price,
            $asset->purchase_date,
        ];
    }
}
