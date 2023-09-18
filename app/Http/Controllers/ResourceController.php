<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
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
    public function storeCourseOfPostgraduate(Request $request)
    {
        $curso = $request->get('dataCourse'); //objeto
        $programa = $request->get('dataProgram'); //objeto
        $valor = $request->get('dataValue');

        $curso_detalle = strtoupper($curso['detalle']);
        $tipo = $programa['cat_sis'];
        $cod_prg = $programa['cod_prg'];
        $cod_val = $valor['cod_val'];
        $pre_val = $valor['pre_uni'];

        if ($curso_detalle == '') {
            $curso_detalle = $programa['cat_des'];
        }
        $gestion = $request->get('year'); //valor entero
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
    //  * RP3. Obtiene las transacciones realizadas en caja universitaria del valorado - curso de postgrado
    public function getInputCourse(Request $request)
    {
        $gestion = $request->get('year'); //valor entero
        $id = $request->get('id');
        $data = Resource::GetInputCourse($id, $gestion);
        return json_encode($data);
    }

    //  * RP4. Guarda las transacciones conciliadas del curso de postgrado
    //Route::post('storeInputCourse', 'ResourceController@storeInputCourse');
    public function storeInputCourse(Request $request)
    {
        \Log::info($request);
        $id = $request->get('id');
        $ingresos = $request->get('dataSelected'); //array
        $usuario = $request->get('user'); //objeto
        $marcador = $request->get('marker');
        $usuario = $usuario['usuario'];
        //$gestion = $usuario['gestion'];
        $ingreso = 0;
        switch ($marcador) {
            case 'registrar':
                # code add...
                foreach ($ingresos as $item) {
                    $ci_per = $item['ci_per'];
                    $cod_val = $item['cod_val'];
                    $des_per = $item['des_per'];
                    $fec_tra = $item['fec_tra'];
                    $gestion = $item['gestion'];
                    $id_tran = $item['id_tran'];
                    $imp_val = $item['imp_val'];
                    $id_dia = $item['id_dia'];
                    $tip_tra = $item['tip_tra'];
                    $obs = '';
                    $response = Resource::StoreInputCourse($id, $ci_per, $cod_val, $des_per, $fec_tra, $gestion, $id_tran, $imp_val, $id_dia, $tip_tra, $usuario, $obs);
                    $ingreso = $response[0]->{'ff_registrar_ingreso_curso'};
                }
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($ingreso);
    }
    //  * RP5. obtiene los ingresos  conciliados del curso de postgrado
    public function getInputTransactionsOfCourse(Request $request)
    {
        $gestion = $request->get('year'); //valor entero
        $id = $request->get('id');
        $data = Resource::GetInputTransactionsOfCourse($id, $gestion);
        return json_encode($data);
    }

}
