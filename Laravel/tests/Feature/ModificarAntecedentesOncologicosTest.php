<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_oncologicos;
use Tests\Ini\CrearDatosTest;

class ModificarAntecedentesOncologicosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el antecedente oncológico a modificar
        $antOModificar = new Antecedentes_oncologicos();
        $antOModificar->id_paciente = 999;
        $antOModificar->id_antecedente_o = 999;
        $antOModificar->tipo = "Próstata";
        $antOModificar->save();
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function modificarAntecedenteOncologicoCorrectoTest()
    {
        //Accedemos la vista antecedentes oncológicos
        $response = $this->get('/paciente/999/antecedentes/oncologicos')->assertSee('Antecedentes oncológicos');
        $antecedenteO = [
            "tipo"=> "Hígado",
        ];
        //Realizamos la solicitud put con los datos del antecedente oncológicos definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/oncologicos/0', $antecedenteO);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/oncologicos');
        //Comprobamos que los datos del antecedente oncológico se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_oncologicos[0]->tipo == "Hígado");
    }
}
