<?php

namespace App\Exports;

use App\Models\Sintomas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SintomasExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Sintomas';
    }

    public function headings(): array
    {
        return [
            'Id sintoma',
            'Id enfermedad',
            'Tipo',
            'Fecha inicio'
        ];
    }

    public function collection()
    {
    	return Sintomas::all();
    }
}