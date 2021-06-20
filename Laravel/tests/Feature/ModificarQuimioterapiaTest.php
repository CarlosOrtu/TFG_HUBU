<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use App\Models\Intenciones;
use App\Models\Farmacos;
use Tests\Ini\CrearDatosTest;

class ModificarQuimioterapiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el tratamiento de quimioterapia que vamos a modificar
        $quimioAModificar = new Tratamientos();
        $quimioAModificar->id_tratamiento = 999;
        $quimioAModificar->id_paciente = 999;
        $quimioAModificar->tipo = "Quimioterapia";
        $quimioAModificar->subtipo = "Adyuvancia";
        $quimioAModificar->fecha_inicio = "2000-05-05";
        $quimioAModificar->fecha_fin = "2000-06-06";
        $quimioAModificar->save();
        //Creamos la intención del tratamiento que vamos a modificar
        $intencionAModificar = new Intenciones();
        $intencionAModificar->id_intencion = 999;
        $intencionAModificar->id_tratamiento = 999;
        $intencionAModificar->tratamiento_acceso_expandido = 0;
        $intencionAModificar->tratamiento_fuera_indicacion = 0;
        $intencionAModificar->esquema = "Combinación";
        $intencionAModificar->modo_administracion = "Intravenoso";
        $intencionAModificar->tipo_farmaco = "Quimioterapia";
        $intencionAModificar->numero_ciclos = 4;
        $intencionAModificar->save();
        //Creamos los farmacos de la intencio que vamos a modificar
        $farmacoAModificar = new Farmacos();
        $farmacoAModificar->id_farmaco = 999;
        $farmacoAModificar->id_intencion = 999;
        $farmacoAModificar->tipo = "Cisplatino";
        $farmacoAModificar->save();
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
    public function modificarQuimioterapiaCorrectaTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia se ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertTrue($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarQuimioterapiaNumeroCiclosVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => "",
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si devuelve error en el campo num_ciclos
        $response->assertSessionHasErrors('num_ciclos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarQuimioterapiaNumeroCiclosMenorQueUnoTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 0,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si devuelve error en el campo num_ciclos
        $response->assertSessionHasErrors('num_ciclos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");    }

    /** @test */
    //Caso de prueba 4
    public function modificarQuimioterapiaFechaPrimerCicloVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si devuelve error en el campo primer_ciclo
        $response->assertSessionHasErrors('primer_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");    }

    /** @test */
    //Caso de prueba 5
    public function modificarQuimioterapiaFechaUltimoCicloVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si devuelve error en el campo ultimo_ciclo
        $response->assertSessionHasErrors('ultimo_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");    }

    /** @test */
    //Caso de prueba 6
    public function modificarQuimioterapiaFechaPrimerCicloPosteriorFechaUltimoCicloTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-07-07",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);
        //Comprobamos si devuelve error en el campo ultimo_ciclo
        $response->assertSessionHasErrors('ultimo_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('1999-07-07');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");    }

    /** @test */
    //Caso de prueba 7
    public function modificarQuimioterapiaFechaPrimerCicloPosteriorActualTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "2022-07-07",
            "ultimo_ciclo" => "2022-08-08",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud put con los datos de la quimioterapia definidos anteriormente
        $response = $this->put('/paciente/999/tratamientos/quimioterapia/0', $quimioterapia);

        //Comprobamos si devuelve error en el campo primer_ciclo
        $response->assertSessionHasErrors('primer_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('2022-07-07');
        $view->assertDontSee('2022-08-08');
        //Comprobamos que los datos de la quimioterapia no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertFalse($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertFalse($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertFalse($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");    }
}
