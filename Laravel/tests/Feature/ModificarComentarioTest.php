<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Comentarios;

class ModificarComentarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario 
        $paciente = new Pacientes();
        $paciente->id_paciente = 999;
        $paciente->nombre = "PacienteTest";
        $paciente->apellidos = "ApellidosTest";
        $paciente->sexo = "Masculino";
        $paciente->nacimiento = "1999-10-05";
        $paciente->raza = "Asiático";      
        $paciente->profesion = "Peluquero";      
        $paciente->fumador = "Desconocido";      
        $paciente->bebedor = "Desconocido";      
        $paciente->carcinogenos = "Desconocido"; 
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save(); 
        //Creamos la enfermedad
        $enfermedad = new Enfermedades();
        $enfermedad->id_enfermedad = 999;
        $enfermedad->id_paciente = 999;
        $enfermedad->fecha_primera_consulta = "1999-02-02";
        $enfermedad->fecha_diagnostico = "1999-03-03";
        $enfermedad->ECOG = 2;
        $enfermedad->T = 3;
        $enfermedad->T_tamano = 1.0;
        $enfermedad->N = 3;
        $enfermedad->N_afectacion = "Uni ganglionar";
        $enfermedad->M = "1b";
        $enfermedad->num_afec_metas = "1";
        $enfermedad->TNM = "IA2";
        $enfermedad->tipo_muestra = "Biopsia";
        $enfermedad->histologia_tipo = "Sarcomatoide";
        $enfermedad->histologia_subtipo = "Mucinoso";
        $enfermedad->histologia_grado = "Bien diferenciado";
        $enfermedad->tratamiento_dirigido = 1;
        $enfermedad->save();
        //Creamos el comentario a modificar
        $comentarioAModificar = new Comentarios();
        $comentarioAModificar->id_paciente = 999;
        $comentarioAModificar->id_comentario = 999;
        $comentarioAModificar->comentario = "Este es el comentario a modificar";
        $comentarioAModificar->save();
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
        //Eliminamos el usuario
        $usuario = Pacientes::find(999)->delete();
        parent::tearDown();
    }


    /** @test */
    //Caso de prueba 1
    public function modificarComentarioCorrectoTest()
    {
        //Accedemos la vista comentario nuevo
        $response = $this->get('/paciente/999/comentarios/modificar/0')->assertSee('Comentario 1');
        $comentario = [
            "comentario" => "Esto es un comentario de prueba",
        ];
        //Realizamos la solicitud put con los datos del comentario definidos anteriormente
        $response = $this->put('/paciente/999/comentarios/modificar/0', $comentario);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/comentarios/modificar/0');
        //Comprobamos que en la vista comentario se ven los datos actualizados
        $paciente = Pacientes::find(999);
        $comentarios = $paciente->Comentarios;
        $comentario = $comentarios[0];
        $view = $this->view('comentarios',['paciente' => $paciente, 'comentario' => $comentario, 'posicion' => 0]);
        $view->assertSee('Esto es un comentario de prueba');
        //Comprobamos que los datos del comentario se han actualizado correctamente
        $this->assertTrue($paciente->Comentarios[0]->comentario == "Esto es un comentario de prueba");
    }
}
