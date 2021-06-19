<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearRadioterapiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();

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
    public function crearRadioterapiaCorrectaTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 5,
            "fecha_inicio" => "1999-05-05",
            "fecha_fin" => "1999-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertTrue($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertTrue($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertTrue($paciente->Tratamientos[0]->dosis == 5);
    }

    /** @test */
    //Caso de prueba 2
    public function crearRadioterapiaDosisVacioTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => "",
            "fecha_inicio" => "1999-05-05",
            "fecha_fin" => "1999-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo dosis
        $response->assertSessionHasErrors('dosis');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function crearRadioterapiaDosisMenorQueUnoTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 0,
            "fecha_inicio" => "1999-05-05",
            "fecha_fin" => "1999-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo dosis
        $response->assertSessionHasErrors('dosis');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 4
    public function crearRadioterapiaFechaInicioVacioTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 5,
            "fecha_inicio" => "",
            "fecha_fin" => "1999-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_inicio
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 5
    public function crearRadioterapiaFechaFinVacioTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 5,
            "fecha_inicio" => "1999-05-05",
            "fecha_fin" => ""  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_fin
        $response->assertSessionHasErrors('fecha_fin');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 6
    public function crearRadioterapiaFechaInicioPosteriorFechaFinTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 5,
            "fecha_inicio" => "2000-05-05",
            "fecha_fin" => "1999-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_fin
        $response->assertSessionHasErrors('fecha_fin');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('1999-07-07');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 6
    public function crearRadioterapiaFechaInicioPosteriorActualTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        $radioterapia = [
            "intencion" => "Radical",
            "localizacion" => "Pulmonar",
            "dosis" => 5,
            "fecha_inicio" => "2022-05-05",
            "fecha_fin" => "2022-06-06"  
        ];
        //Realizamos la solicitud post con los datos de la radioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/radioterapia', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_inicio
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('2022-07-07');
        $view->assertDontSee('2022-08-08');
        //Comprobamos que los datos de la radioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }
}
