<?php

namespace Tests\Seguridad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Usuarios;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class AnonimoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Realizamos el login con el administrador para comprobar las rutas
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        $iniDatos->crearReevaluacion();
        $iniDatos->crearSeguimiento();
        $iniDatos->crearComentario();
    }

    protected function tearDown(): void
    {
        Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    public function AccesoRutasAnonimoTest() {
        foreach (\Route::getRoutes()->get() as $route){
            if(in_array("GET", $route->methods)) {
                if(strpos($route->uri, "paciente") === false){
                    $rutaNueva = str_replace("{id}", "1", $route->uri);
                }else{
                    $rutaNueva = str_replace("{id}", "999", $route->uri);
                }
                $rutaNueva = str_replace("{num_reevaluacion}", "0", $rutaNueva);
                $rutaNueva = str_replace("{num_seguimiento}", "0", $rutaNueva);
                $rutaNueva = str_replace("{num_comentario}", "0", $rutaNueva);
                //Realizamos la solicitud get en todas las rutas menos 
                // logout porque cerraría sesión y no dejaría acceder al resto de rutas,
                // eliminar usuario porque eliminaría el usuario administrador 
                // eliminar paciente porque eliminaria todos sus datos como enfermedad y no se podría acceder a ellos
                $response = $this->get('/'.$rutaNueva);
                if($rutaNueva != "login" and $rutaNueva != "logout")
                    if($route->uri == "administrar/usuarios" or $route->uri == "nuevo/usuario" or $rutaNueva == "modificar/usuario/1" or $rutaNueva == "eliminar/usuario/1"){
                        $response->assertRedirect('/');
                    }else{
                        $response->assertRedirect('/login');
                    }
            }
        }
    }

}