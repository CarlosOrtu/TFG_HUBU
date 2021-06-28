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

        $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function AccesoRutasAdminTest() {
        foreach (\Route::getRoutes()->get() as $route){
            if(in_array("GET", $route->methods)) {
                dump($route->uri);
                $credentials = [
                    "email" => "administrador@gmail.com",
                    "password" => "1234",
                ];
                $this->post('/login', $credentials);

                if(strpos($route->uri, "paciente") == true){
                    $rutaNueva = str_replace("{id}", "999", $route->uri);
                }else{
                    $rutaNueva = str_replace("{id}", "1", $route->uri);
                }
                dump($rutaNueva);
                $response = $this->get('/'.$route->uri);
                if($route->uri == "login")  
                    $response->assertRedirect('/pacientes');
                elseif($route->uri == "logout")
                    $response->assertRedirect('/');
                elseif($route->uri == "/")
                    $response->assertRedirect('/login');
                else
                    $response->assertStatus(200);
            }
        }
    }

}