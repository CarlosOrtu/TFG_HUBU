<?php

namespace App\Exports;

use App\Models\Antecedentes_familiares;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AntecedentesFamiliaresExport implements FromCollection, WithTitle, WithHeadings 
{
    public function title(): string
    {
        return 'Antecedentes familiares';
    }

    public function headings(): array
    {
        return [
            'Id antecedente famiiar',
            'Id paciente',
            'Familiar'
        ];
    }

    public function collection()
    {
        return Antecedentes_familiares::all();
    }
}