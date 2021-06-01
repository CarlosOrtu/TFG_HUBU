<?php

namespace App\Exports;

use App\Models\Farmacos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FarmacosExport implements FromCollection, WithTitle, WithHeadings 
{
    public function title(): string
    {
        return 'Farmacos';
    }

    public function headings(): array
    {
        return [
            'Id farmaco',
            'Id intención',
            'Tipo'
        ];
    }

    public function collection()
    {
        return Farmacos::all();
    }
}