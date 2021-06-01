<?php

namespace App\Exports;

use App\Models\Pacientes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PacientesExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Pacientes';
    }

    public function headings(): array
    {
        return [
            'Id_paciente',
            'Nombre',
            'Apellidos',
            'Sexo',
            'Raza',
            'Nacimiento',
            'Profesion',
            'Fumador',
            'Cigarros al día',
            'Bebedor',
            'Carcinogenos'
        ];
    }

    public function collection()
    {
    	return Pacientes::all();
    }
}
