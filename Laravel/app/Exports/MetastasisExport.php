<?php

namespace App\Exports;

use App\Models\Metastasis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MetastasisExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Metastasis';
    }

    public function headings(): array
    {
        return [
            'Id metastasis',
            'Id enfermedad',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Metastasis::all();
    }
}