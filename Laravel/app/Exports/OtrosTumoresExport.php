<?php

namespace App\Exports;

use App\Models\Otros_tumores;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;


class OtrosTumoresExport implements FromCollection, WithTitle, WithHeadings
{
	public function title(): string
    {
        return 'Otros tumores';
    }

    public function headings(): array
    {
        return [
            'Id_tumor',
            'Id_enfermedad',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Otros_tumores::all();
    }
}