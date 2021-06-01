<?php

namespace App\Exports;

use App\Models\Seguimientos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SeguimientosExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Seguimientos';
    }

    public function headings(): array
    {
        return [
            'Id seguimiento',
            'Id paciente',
            'Fecha',
            'Estado',
            'Motivo fallecimiento',
            'Fecha fallecimiento'
        ];
    }

    public function collection()
    {
    	return Seguimientos::all();
    }
}