<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_oncologicos;
use Tests\Ini\CrearDatosTest;

class EliminarAntecedentesOncologicosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el antecedente oncológico a eliminar
        $antecedenteEliminar = new Antecedentes_oncologicos();
        $antecedenteEliminar->id_paciente = 999;
        $antecedenteEliminar->id_antecedente_o = 999;
        $antecedenteEliminar->tipo = "Próstata";
        $antecedenteEliminar->save();
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
    public function eliminarAntecedenteOncologicoCorrectoTest()
    {
        //Accedemos la vista antecedentes oncológicos
        $response = $this->get('/paciente/999/antecedentes/oncologicos')->assertSee('Antecedentes oncológicos');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/antecedentes/oncologicos/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/oncologicos');
        //Comprobamos que los datos del antecedente oncológico se han eliminado correctamente
        $antecedenteO = Antecedentes_oncologicos::find(999);
        $this->assertTrue(empty($antecedenteO));
    }
}
