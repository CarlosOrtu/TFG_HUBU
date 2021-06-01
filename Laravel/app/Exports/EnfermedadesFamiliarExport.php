<?php

namespace App\Exports;

use App\Models\Enfermedades_familiar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnfermedadesFamiliarExport implements FromCollection, WithTitle, WithHeadings 
{
    public function title(): string
    {
        return 'Enfermedades familiar';
    }

    public function headings(): array
    {
        return [
            'Id enfermedad familiar',
            'Id antecedente familiar',
            'Tipo'
        ];
    }

    public function collection()
    {
        return Enfermedades_familiar::all();
    }
}