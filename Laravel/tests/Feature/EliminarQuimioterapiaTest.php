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

class EliminarQuimioterapiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el tratamiento de quimioterapia que vamos a eliminar
        $quimioAEliminar = new Tratamientos();
        $quimioAEliminar->id_tratamiento = 999;
        $quimioAEliminar->id_paciente = 999;
        $quimioAEliminar->tipo = "Quimioterapia";
        $quimioAEliminar->subtipo = "Adyuvancia";
        $quimioAEliminar->fecha_inicio = "2000-05-05";
        $quimioAEliminar->fecha_fin = "2000-06-06";
        $quimioAEliminar->save();
        //Creamos la intención del tratamiento que vamos a eliminar
        $intencionAEliminar = new Intenciones();
        $intencionAEliminar->id_intencion = 999;
        $intencionAEliminar->id_tratamiento = 999;
        $intencionAEliminar->tratamiento_acceso_expandido = 0;
        $intencionAEliminar->tratamiento_fuera_indicacion = 0;
        $intencionAEliminar->esquema = "Combinación";
        $intencionAEliminar->modo_administracion = "Intravenoso";
        $intencionAEliminar->tipo_farmaco = "Quimioterapia";
        $intencionAEliminar->numero_ciclos = 4;
        $intencionAEliminar->save();
        //Creamos los farmacos de la intencio que vamos a eliminar
        $farmacoAEliminar = new Farmacos();
        $farmacoAEliminar->id_farmaco = 999;
        $farmacoAEliminar->id_intencion = 999;
        $farmacoAEliminar->tipo = "Cisplatino";
        $farmacoAEliminar->save();
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
    public function eliminarQuimioterapiaCorrectaTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/tratamientos/quimioterapia/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no se ven los datos eliminados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia se han modificado correctamente
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

}
