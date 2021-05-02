<?php
 
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;

class EliminarPacienteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos un usuario el cual vamos a eliminar
        $pacienteAModificar = new Pacientes();
        $pacienteAModificar->id_paciente = 999;
        $pacienteAModificar->nombre = "PacientePrueba";
        $pacienteAModificar->apellidos = "Apellido1 Apellido2";
        $pacienteAModificar->sexo = "Masculino";
        $pacienteAModificar->nacimiento = date('Y-m-d');
        $pacienteAModificar->raza = "Caucásico";
        $pacienteAModificar->profesion = "Construcción";      
        $pacienteAModificar->fumador = "Desconocido";   
        $pacienteAModificar->bebedor = "Desconocido";    
        $pacienteAModificar->carcinogenos = "Desconocido";    
        $pacienteAModificar->ultima_modificacion = date("Y-m-d"); 
        $pacienteAModificar->save();  
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function eliminarPacienteCorrectoTest()
    {
        //Accedemos la vista administrarusuarios 
        $response = $this->get('/pacientes')->assertSee('Listado de pacientes');
        //Comprobamos que se ve el usuario
        $pacientes = Pacientes::all();
        
        $view = $this->view('pacientes', ['pacientes' => $pacientes]);
        $view->assertSee('999');
        $view->assertSee('PacientePrueba');
        $view->assertSee('Apellido1 Apellido2');
        //Realizamos la solicitud delete
        $response = $this->get('/eliminar/paciente/999');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/eliminar/paciente');
        //Comprobamos que el usuario este eliminado de la base de datos
        $usuario = Pacientes::find(999);
        $this->assertTrue(empty($usuario)); 
        //Comprobamos que el usuario no se vea
        $response = $this->get('/pacientes');
    }
}
