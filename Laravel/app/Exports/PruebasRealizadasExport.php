<?php

namespace App\Exports;

use App\Models\Pruebas_realizadas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PruebasRealizadasExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Pruebas realizadas';
    }

    public function headings(): array
    {
        return [
            'Id_prueba',
            'Id_enfermedad',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Pruebas_realizadas::all();
    }
}