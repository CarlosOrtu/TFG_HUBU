<?php

namespace App\Exports;

use App\Models\Tecnicas_realizadas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TecnicasRealizadasExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Tecnicas realizadas';
    }

    public function headings(): array
    {
        return [
            'Id_tecnica',
            'Id_enfermedad',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Tecnicas_realizadas::all();
    }
}