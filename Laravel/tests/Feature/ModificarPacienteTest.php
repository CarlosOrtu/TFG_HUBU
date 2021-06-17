<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;

class ModificarPacienteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario a modificar
        $pacienteAModificar = new Pacientes();
        $pacienteAModificar->id_paciente = 999;
        $pacienteAModificar->nombre = "PacienteTest";
        $pacienteAModificar->apellidos = "ApellidosTest";
        $pacienteAModificar->sexo = "Masculino";
        $pacienteAModificar->nacimiento = "1999-10-05";
        $pacienteAModificar->raza = "Asiático";      
        $pacienteAModificar->profesion = "Peluquero";      
        $pacienteAModificar->fumador = "Desconocido";      
        $pacienteAModificar->bebedor = "Desconocido";      
        $pacienteAModificar->carcinogenos = "Desconocido"; 
        $pacienteAModificar->ultima_modificacion = date("Y-m-d");
        $pacienteAModificar->save(); 
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
    public function modificarPacienteCorrectoTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999')->assertSee('Datos paciente');
        $paciente = [
            "nombre" => "PacienteModificado",
            "apellidos" => "ApellidoModificado",
            "sexo" => "Femenino",
            "nacimiento" => "1999-10-10",
            "raza" => "Caucásico",
            "profesion" => "Pintor",
            "fumador" => "Nunca fumador",
            "bebedor" => "Exbebedor",
            "carcinogenos" => "Asbesto", 
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999', $paciente);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999');
        //Comprobamos que en la vista paciente se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datospaciente', ['paciente' => $paciente]);
        $view->assertSee('PacienteModificado');
        $view->assertSee('ApellidoModificado');
        $view->assertSee('Femenino');
        $view->assertSee('1999-10-10');
        $view->assertSee('Caucásico');
        $view->assertSee('Pintor');
        $view->assertSee('Nunca fumador');
        $view->assertSee('Exbebedor');
        $view->assertSee('Asbesto');
        //Comprobamos que los datos del paciente coincidan con los modificados
        $this->assertTrue($paciente->nombre == "PacienteModificado");
        $this->assertTrue($paciente->apellidos == "ApellidoModificado");
        $this->assertTrue($paciente->sexo == "Femenino");
        $this->assertTrue($paciente->nacimiento == "1999-10-10");
        $this->assertTrue($paciente->raza == "Caucásico");
        $this->assertTrue($paciente->profesion == "Pintor");
        $this->assertTrue($paciente->fumador == "Nunca fumador");
        $this->assertTrue($paciente->bebedor == "Exbebedor");
        $this->assertTrue($paciente->carcinogenos == "Asbesto");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarPacienteCampoNombreVacioTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999')->assertSee('Datos paciente');
        $paciente = [
            "nombre" => "",
            "apellidos" => "ApellidoModificado",
            "sexo" => "Femenino",
            "nacimiento" => "1999-10-10",
            "raza" => "Caucásico",
            "profesion" => "Pintor",
            "fumador" => "Nunca fumador",
            "bebedor" => "Exbebedor",
            "carcinogenos" => "Asbesto",
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999', $paciente);
        //Comprobamos que devuelve error en el campo nombre 
        $response->assertSessionHasErrors('nombre');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999');
        //Comprobamos que en la vista pacientes no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datospaciente', ['paciente' => $paciente]);
        $view->assertDontSee('ApellidoModificado');
        $view->assertDontSee('1999-10-10');
        //Comprobamos que los datos del paciente no se han actualizado
        $this->assertFalse($paciente->nombre == "");
        $this->assertFalse($paciente->apellidos == "ApellidoModificado");
        $this->assertFalse($paciente->sexo == "Femenino");
        $this->assertFalse($paciente->nacimiento == "1999-10-10");
        $this->assertFalse($paciente->raza == "Caucásico");
        $this->assertFalse($paciente->profesion == "Pintor");
        $this->assertFalse($paciente->fumador == "Nunca fumador");
        $this->assertFalse($paciente->bebedor == "Exbebedor");
        $this->assertFalse($paciente->carcinogenos == "Asbesto");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarPacienteCampoApellidosVacioTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999')->assertSee('Datos paciente');
        $paciente = [
            "nombre" => "PacienteModificado",
            "apellidos" => "",
            "sexo" => "Femenino",
            "nacimiento" => "1999-10-10",
            "raza" => "Caucásico",
            "profesion" => "Pintor",
            "fumador" => "Nunca fumador",
            "bebedor" => "Exbebedor",
            "carcinogenos" => "Asbesto",
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999', $paciente);
        //Comprobamos que devuelve error en el campo apellidos 
        $response->assertSessionHasErrors('apellidos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999');
        //Comprobamos que en la vista pacientes no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datospaciente', ['paciente' => $paciente]);
        $view->assertDontSee('PacienteModificado');
        $view->assertDontSee('1999-10-10');
        //Comprobamos que los datos del paciente no se han actualizado
        $this->assertFalse($paciente->nombre == "PacienteModificado");
        $this->assertFalse($paciente->apellidos == "");
        $this->assertFalse($paciente->sexo == "Femenino");
        $this->assertFalse($paciente->nacimiento == "1999-10-10");
        $this->assertFalse($paciente->raza == "Caucásico");
        $this->assertFalse($paciente->profesion == "Pintor");
        $this->assertFalse($paciente->fumador == "Nunca fumador");
        $this->assertFalse($paciente->bebedor == "Exbebedor");
        $this->assertFalse($paciente->carcinogenos == "Asbesto");
    }

    /** @test */
    //Caso de prueba 4
    public function modificarPacienteFechaIncorrectaTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999')->assertSee('Datos paciente');
        $paciente = [
            "nombre" => "PacienteModificado",
            "apellidos" => "ApellidoModificado",
            "sexo" => "Femenino",
            "nacimiento" => "2022-10-10",
            "raza" => "Caucásico",
            "profesion" => "Pintor",
            "fumador" => "Nunca fumador",
            "bebedor" => "Exbebedor",
            "carcinogenos" => "Asbesto",
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999', $paciente);
        //Comprobamos que devuelve error en el campo nacimiento 
        $response->assertSessionHasErrors('nacimiento');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999');
        //Comprobamos que en la vista pacientes no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datospaciente', ['paciente' => $paciente]);
        $view->assertDontSee('PacienteModificado');
        $view->assertDontSee('ApellidoModificado');
        $view->assertDontSee('2022-10-10');
        //Comprobamos que los datos del paciente no se han actualizado
        $this->assertFalse($paciente->nombre == "PacienteModificado");
        $this->assertFalse($paciente->apellidos == "ApellidoModificado");
        $this->assertFalse($paciente->sexo == "Femenino");
        $this->assertFalse($paciente->nacimiento == "2022-10-10");
        $this->assertFalse($paciente->raza == "Caucásico");
        $this->assertFalse($paciente->profesion == "Pintor");
        $this->assertFalse($paciente->fumador == "Nunca fumador");
        $this->assertFalse($paciente->bebedor == "Exbebedor");
        $this->assertFalse($paciente->carcinogenos == "Asbesto");
    }

}
