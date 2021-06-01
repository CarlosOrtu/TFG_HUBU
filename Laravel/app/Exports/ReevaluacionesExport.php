<?php

namespace App\Exports;

use App\Models\Reevaluaciones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReevaluacionesExport implements FromCollection, WithTitle, WithHeadings 
{
    public function title(): string
    {
        return 'Reevaluaciones';
    }

    public function headings(): array
    {
        return [
            'Id reevaluacion',
            'Id paciente',
            'Fecha',
            'Estado',
            'Localización de la progresión',
            'Tipo de tratamiento'
        ];
    }

    public function collection()
    {
        return Reevaluaciones::all();
    }
}