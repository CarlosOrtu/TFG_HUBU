<?php

namespace App\Exports;

use App\Models\Antecedentes_medicos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AntecedentesMedicosExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Antecedentes medicos';
    }

    public function headings(): array
    {
        return [
            'Id antecedente medico',
            'Id paciente',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Antecedentes_medicos::all();
    }
}