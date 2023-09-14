<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use Illuminate\Pagination\LengthAwarePaginator;

class ResourceController extends Controller
{
    //
    //  * RP1. Obtener la lista de cursos de posgrado.
    //  * parametros {description: descripcion que se esta buscando }
    public function getCoursesOfPostgraduate(Request $request)
    {
        //$descripcion = strtoupper($request->get('description'));
        $gestion = $request->get('year');
        \Log::info($request);
        \Log::info($gestion);
        $data = Resource::GetCoursesOfPostgraduate($gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/courses')]
        );
        return json_encode($paginate);
    }

    //  * RP2. Guardar un curso de postgrado.    
    public function storeCourseOfPostgraduate(Request $request){
        $curso = $request->get('dataCourse');//objeto
        $programa = $request->get('dataProgram');//objeto
        $valor = $request->get('dataValue');

        $curso_detalle = strtoupper($curso['detalle']);
        $tipo = $programa['cat_sis'];
        $cod_prg = $programa['cod_prg']; 
        $cod_val = $valor['cod_val'];
        $pre_val = $valor['pre_uni'];
                
        if($curso_detalle == ''){
            $curso_detalle = $programa['cat_des'];
        }
        $gestion = $request->get('year');//valor entero
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $response = Resource::StoreCourseOfPostgraduate($curso_detalle, $tipo, $cod_prg, $cod_val, $pre_val, $gestion);
                $course = $response[0]->{'ff_registrar_curso'};
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($course);
    }
    
}
