<?php

namespace App\Exports;

use App\Models\Tratamientos;
use App\Models\Intenciones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class TratamientosExport implements FromCollection, WithTitle, WithHeadings 
{
    public function title(): string
    {
        return 'Tratamientos';
    }

    public function headings(): array
    {
        return [
            'Id tratamiento',
            'Id paciente',
            'Tipo',
            'Subtipo',
            'Dosis',
            'Localización',
            'Fecha inicio',
            'Fecha fin',
            'Id intencion',
            'Id tratamiento',
            'Ensayo',
            'Fase del ensayo',
            'Tratamiento de acceso expandido',
            'Tratamiento fuera de indicación',
            'Medicación extranjera',
            'Esquema',
            'Modo administración',
            'Tipo de fármaco',
            'Número de ciclos'
        ];
    }

    public function collection()
    {
        $joinTratamientosIntenciones = DB::table('Tratamientos')->leftJoin('Intenciones','Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento');
        return $joinTratamientosIntenciones->get();
    }
}