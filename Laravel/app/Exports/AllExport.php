<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\PacientesExport;
use App\Exports\EnfermedadesExport;
use App\Exports\TecnicasRealizadasExport;
use App\Exports\OtrosTumoresExport;
use App\Exports\PruebasRealizadasExport;
use App\Exports\BiomarcadoresExport;
use App\Exports\SintomasExport;
use App\Exports\MetastasisExport;
use App\Exports\AntecedentesMedicosExport;
use App\Exports\SeguimientosExport;
use App\Exports\AntecedentesOncologicosExport;
use App\Exports\AntecedentesFamiliaresExport;
use App\Exports\EnfermedadesFamiliarExport;
use App\Exports\ReevaluacionesExport;
use App\Exports\TratamientosExport;
use App\Exports\FarmacosExport;

class AllExport implements WithMultipleSheets 
{
    public function sheets(): array
    {
        return [
            'Pacientes' => new PacientesExport(),
            'Enfermedades' => new EnfermedadesExport(),
            'Otros_tumores' => new OtrosTumoresExport(),
            'Tecnicas_realizadas' => new TecnicasRealizadasExport(),
            'Pruebas_realizadas' => new PruebasRealizadasExport(),
            'Biomarcadores' => new BiomarcadoresExport(),
            'Sintomas' => new SintomasExport(),
            'Metastasis' => new MetastasisExport(),
            'Antecedentes_medicos' => new AntecedentesMedicosExport(),
            'Seguimientos' => new SeguimientosExport(),
            'Antecedentes_oncologicos' => new AntecedentesOncologicosExport(),
            'Antecedentes_familiares' => new AntecedentesFamiliaresExport(), 
            'Enfermedades_familiar' => new EnfermedadesFamiliarExport(),
            'Reevaluaciones' => new ReevaluacionesExport(),
            'Tratamientos' => new TratamientosExport(),
            'Farmacos' => new FarmacosExport(),
        ];
    }
}