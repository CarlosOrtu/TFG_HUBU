<?php

namespace App\Exports;

use App\Models\Antecedentes_oncologicos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AntecedentesOncologicosExport implements FromCollection, WithTitle, WithHeadings 
{
	public function title(): string
    {
        return 'Antecedentes oncologicos';
    }

    public function headings(): array
    {
        return [
            'Id antecedente oncologico',
            'Id paciente',
            'Tipo'
        ];
    }

    public function collection()
    {
    	return Antecedentes_oncologicos::all();
    }
}