<?php

namespace Tests\Seguridad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class AdministradorTest extends TestCase
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

        $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    public function AccesoRutasAdminTest() {
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
                if($route->uri != "logout" and $rutaNueva!="eliminar/usuario/1" and $rutaNueva!="eliminar/paciente/999"){
                    $response = $this->get('/'.$rutaNueva);
                    if($route->uri == "login")  
                        $response->assertRedirect('/pacientes');
                    elseif($route->uri == "/")
                        $response->assertRedirect('/login');
                    else
                        $response->assertStatus(200);
                }
            }
        }
    }

}