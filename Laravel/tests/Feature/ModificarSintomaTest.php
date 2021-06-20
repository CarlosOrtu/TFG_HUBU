<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Sintomas;
use Tests\Ini\CrearDatosTest;

class ModificarSintomaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el sintoma a modificar
        $sintomaAModificar = new Sintomas();
        $sintomaAModificar->id_enfermedad = 999;
        $sintomaAModificar->fecha_inicio = "1999-05-05";
        $sintomaAModificar->tipo = "Asintomático";
        $sintomaAModificar->save();

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
    public function modificarFechaSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "1999-06-06",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertSee('1999-06-06');
        //Comprobamos que la fecha del sintoma se ha modificado correctamente
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "1999-06-06");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarTipoSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $tipo = [
            "tipo"=> "Tos",
        ];
        //Realizamos la solicitud put con el tipo definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/0', $tipo);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que el tipo del sintoma se ha modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->tipo == "Tos");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarFechaSintomaCampoFechaInicioVacioTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos que devuelve error en el campo fecha inicio 
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que la fecha del sintoma no se ha modificado
        $paciente = Pacientes::find(999);
        $this->assertFalse($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "");
    }

    /** @test */
    //Caso de prueba 4
    public function modificarFechaSintomaCampoFechaInicioPosteriorTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "2022-05-05",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos que devuelve error en el campo fecha inicio 
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que la fecha del sintoma no se ha modificado
        $this->assertFalse($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "2022-05-05");
    }
}
