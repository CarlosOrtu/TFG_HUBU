<?php

namespace App\Exports;

use App\Models\Biomarcadores;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BiomarcadoresExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Tecnicas realizadas';
    }

    public function headings(): array
    {
        return [
            'Id biomarcador',
            'Id enfermedad',
            'Nombre',
            'Tipo',
            'Subtipo'
        ];
    }

    public function collection()
    {
    	return Biomarcadores::all();
    }
}