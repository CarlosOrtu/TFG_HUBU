<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;

class CrearPacienteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Realizamos el login para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el paciente
        Pacientes::where('nombre','Paciente')->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function crearNuevoPacienteCorrectoTest()
    {
        //Accedemos la vista pacientes y posteriormente a la vista nuevopaciente
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        $response = $this->get('/nuevo/paciente')->assertSee('Añadir paciente');
        $paciente = [
            "nombre" => "Paciente",
            "apellidos" => "Apellido1 Apellido2",
            "sexo" => "Masculino",
            "nacimiento" => "1999-10-05",
            "raza" => "Caucásico",
            "profesion" => "Bombero",
            "fumador" => "",
            "bebedor" => "",
            "carcinogenos" => "",
        ];
        //Realizamos la solicitud post con los datos del paciente definidos anteriormente
        $response = $this->post('/nuevo/paciente', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/pacientes');
        //Comprobamos que en la vista pacientes se vea el nuevo paciente
        $pacientes = Pacientes::all();
        $view = $this->view('pacientes', ['pacientes' => $pacientes]);
        $view->assertSee('Paciente');
        $view->assertSee('Apellido1 Apellido2');
        //Comprobmos que el paciente este incluido en la base de datos
        $paciente = Pacientes::where('nombre','Paciente')->where('apellidos','Apellido1 Apellido2')->first();
        $this->assertTrue(!empty($paciente));
        //Comprobamos que los datos del paciente coincidan con los introducidos
        $this->assertTrue($paciente->sexo == "Masculino");
        $this->assertTrue($paciente->nacimiento == "1999-10-05");
        $this->assertTrue($paciente->raza == "Caucásico");
        $this->assertTrue($paciente->profesion == "Bombero");
        $this->assertTrue($paciente->fumador == null);
        $this->assertTrue($paciente->bebedor == null);
        $this->assertTrue($paciente->carcinogenos == null);
    }

    /** @test */
    //Caso de prueba 2
    public function crearNuevoPacienteNombreVacioTest()
    {
        //Accedemos la vista pacientes y posteriormente a la vista nuevopaciente
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        $response = $this->get('/nuevo/paciente')->assertSee('Añadir paciente');
        $paciente = [
            "nombre" => "",
            "apellidos" => "Apellido1 Apellido2",
            "sexo" => "Masculino",
            "nacimiento" => "1999-10-05",
            "raza" => "Caucásico",
            "profesion" => "Bombero",
            "fumador" => "",
            "bebedor" => "",
            "carcinogenos" => "",
        ];
        //Realizamos la solicitud post con los datos del paciente definidos anteriormente
        $response = $this->post('/nuevo/paciente', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/paciente');
        //Comprobamos si devuelve error en el campo nombre
        $response->assertSessionHasErrors('nombre');
        //Comprobamos que no se haya insertado el paciente en la base de datos
        $paciente = Pacientes::where('nacimiento','1999-10-05')->where('apellidos','Apellido1 Apellido2')->first();
        $this->assertTrue(empty($paciente));
    }

    /** @test */
    //Caso de prueba 3
    public function crearNuevoPacienteApellidosVacioTest()
    {
        //Accedemos la vista pacientes y posteriormente a la vista nuevopaciente
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        $response = $this->get('/nuevo/paciente')->assertSee('Añadir paciente');
        $paciente = [
            "nombre" => "Paciente",
            "apellidos" => "",
            "sexo" => "Masculino",
            "nacimiento" => "1999-10-05",
            "raza" => "Caucásico",
            "profesion" => "Bombero",
            "fumador" => "",
            "bebedor" => "",
            "carcinogenos" => "",
        ];
        //Realizamos la solicitud post con los datos del paciente definidos anteriormente
        $response = $this->post('/nuevo/paciente', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/paciente');
        //Comprobamos si devuelve error en el campo apellidos
        $response->assertSessionHasErrors('apellidos');
        //Comprobamos que no se haya insertado el paciente en la base de datos
        $paciente = Pacientes::where('nombre','Paciente')->where('nacimiento','1999-10-05')->first();
        $this->assertTrue(empty($paciente));
    }

        /** @test */
    //Caso de prueba 4
    public function crearNuevoPacienteNacimientoVacioTest()
    {
        //Accedemos la vista pacientes y posteriormente a la vista nuevopaciente
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        $response = $this->get('/nuevo/paciente')->assertSee('Añadir paciente');
        $paciente = [
            "nombre" => "Paciente",
            "apellidos" => "Apellido1 Apellido2",
            "sexo" => "Masculino",
            "nacimiento" => "",
            "raza" => "Caucásico",
            "profesion" => "Bombero",
            "fumador" => "",
            "bebedor" => "",
            "carcinogenos" => "",
        ];
        //Realizamos la solicitud post con los datos del paciente definidos anteriormente
        $response = $this->post('/nuevo/paciente', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/paciente');
        //Comprobamos si devuelve error en el campo nacimiento
        $response->assertSessionHasErrors('nacimiento');
        //Comprobamos que no se haya insertado el paciente en la base de datos
        $paciente = Pacientes::where('nombre','Paciente')->where('apellidos','Apellido1 Apellido2')->first();
        $this->assertTrue(empty($paciente));
    }

        /** @test */
    //Caso de prueba 5
    public function crearNuevoNacimientoIncorrectoTest()
    {
        //Accedemos la vista pacientes y posteriormente a la vista nuevopaciente
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        $response = $this->get('/nuevo/paciente')->assertSee('Añadir paciente');
        $paciente = [
            "nombre" => "Paciente",
            "apellidos" => "Apellido1 Apellido2",
            "sexo" => "Masculino",
            "nacimiento" => "2022-10-05",
            "raza" => "Caucásico",
            "profesion" => "Bombero",
            "fumador" => "",
            "bebedor" => "",
            "carcinogenos" => "",
        ];
        //Realizamos la solicitud post con los datos del paciente definidos anteriormente
        $response = $this->post('/nuevo/paciente', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/paciente');
        //Comprobamos si devuelve error en el campo nacimiento
        $response->assertSessionHasErrors('nacimiento');
        //Comprobamos que no se haya insertado el paciente en la base de datos
        $paciente = Pacientes::where('nombre','Paciente')->where('apellidos','Apellido1 Apellido2')->first();
        $this->assertTrue(empty($paciente));
    }
}
