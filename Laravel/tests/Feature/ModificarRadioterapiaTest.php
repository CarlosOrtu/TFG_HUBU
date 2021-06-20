<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use Tests\Ini\CrearDatosTest;

class ModificarRadioterapiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la radioterapia que vamos a modificar
        $radioAModificar = new Tratamientos();
        $radioAModificar->id_tratamiento = 999;
        $radioAModificar->id_paciente = 999;
        $radioAModificar->tipo = "Radioterapia";
        $radioAModificar->subtipo = "Paliativa";
        $radioAModificar->dosis = 4;
        $radioAModificar->localizacion = "Ósea";
        $radioAModificar->fecha_inicio = "1998-05-05";
        $radioAModificar->fecha_fin = "1998-06-06";
        $radioAModificar->save();
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
    public function modificarRadioterapiaCorrectaTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia se ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia se han actualizado correctamente
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
    public function modificarRadioterapiaDosisVacioTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo dosis
        $response->assertSessionHasErrors('dosis');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertSee('1998-05-05');
        $view->assertSee('1998-06-06');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == "");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarRadioterapiaDosisMenorQueUnoTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo dosis
        $response->assertSessionHasErrors('dosis');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == 0);    
    }

    /** @test */
    //Caso de prueba 4
    public function modificarRadioterapiaFechaInicioVacioTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_inicio
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == 5);   
    }

    /** @test */
    //Caso de prueba 5
    public function modificarRadioterapiaFechaFinVacioTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_fin
        $response->assertSessionHasErrors('fecha_fin');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == 5);  
    }

    /** @test */
    //Caso de prueba 6
    public function modificarRadioterapiaFechaInicioPosteriorFechaFinTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_fin
        $response->assertSessionHasErrors('fecha_fin');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('1999-07-07');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "2000-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == 5);  
    }

    /** @test */
    //Caso de prueba 6
    public function modificarRadioterapiaFechaInicioPosteriorActualTest()
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
        //Realizamos la solicitud put con los datos de la radioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/radioterapia/0', $radioterapia);
        //Comprobamos si devuelve error en el campo fecha_inicio
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no ven los datos actualizados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('2022-07-07');
        $view->assertDontSee('2022-08-08');
        //Comprobamos que los datos de la radioterapia no se han actualizado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Radioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Radical");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "2022-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "2022-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->localizacion == "Pulmonar");
        $this->assertFalse($paciente->Tratamientos[0]->dosis == 5);  
    }
}
