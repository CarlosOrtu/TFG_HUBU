<?php

namespace App\Exports;

use App\Models\Enfermedades;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnfermedadesExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Enfermedades';
    }

    public function headings(): array
    {
        return [
            'Id_enfermedad',
            'Id_paciente',
            'Primera consulta',
            'Fecha Diagnostico',
            'ECOG',
            'T',
            'Tamaño T',
            'N',
            'Afectación N',
            'M',
            'Afectación metastasis',
            'TNM',
            'Tipo de muestra',
            'Tipo de histología',
            'Subtipo de histología',
            'Grado de histología',
            'Tratamiento dirigido'
        ];
    }

    public function collection()
    {
    	return Enfermedades::all();
    }
}
