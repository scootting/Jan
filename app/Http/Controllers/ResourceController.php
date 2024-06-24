<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ResourceController extends Controller
{
    //  * RP11. Obtener la lista de programas academicos
    public static function getTypesOfProgram(Request $request)
    {
        $data = Resource::GetTypesOfProgram();
        return json_encode($data);
    }

    //  * RP12. Guardar un curso de postgrado.
    public function storeProgram(Request $request)
    {
        $programa = $request->get('programa');
        $usuario = $request->get('usuario');
        $tutor = $request->get('tutor');

        $descripcion = strtoupper($programa['descripcion']);
        $costo = $programa['costo'];
        $fecha = $programa['fecha'];
        $ci_tutor = $programa['ci_tutor'];
        $des_tutor = $programa['des_tutor'];
        $id_tipo = $programa['id_tipo'];
        $des_tipo = $programa['des_tipo'];
        $gestion = '2024'; //$usuario['gestion']'';
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $response = Resource::StoreProgram($descripcion, $costo, $fecha, $ci_tutor, $des_tutor, $id_tipo, $gestion);
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

    //  * RP1. Obtener la lista de cursos de posgrado.
    //  * parametros {description: descripcion que se esta buscando }
    public function getPrograms(Request $request)
    {
        $gestion = $request->get('year');
        $data = Resource::GetPrograms($gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getPrograms')]
        );
        return json_encode($paginate);
    }

    //  * RP2. Obtener un curso de posgrado.
    //Route::post('getProgramById', 'ResourceController@getProgramById');
    public function getProgramById(Request $request)
    {
        $id = $request->get('id');
        $data = Resource::GetProgramById($id);
        return json_encode($data);
    }
    //  * RP13. Obtener la lista de estudiantes por cursos de posgrado.
    //Route::post('getStudentsByProgram', 'ResourceController@getStudentsByProgram');
    public function getStudentsByProgram(Request $request)
    {
        $id = $request->get('id');
        $data = Resource::GetStudentsByProgram($id);
        return json_encode($data);
    }
    //  * RP14. Agregar un grupo de estudiantes al curso.
    //Route::post('storeStudentByProgram', 'ResourceController@storeStudentByProgram');
    public function storeStudentByProgram(Request $request)
    {
        $estudiante = $request->get('estudiante');
        $id = $request->get('programa');

        $ci_per = strtoupper($estudiante['ci_per']);
        $des_per = strtoupper($estudiante['des_per']);
        $deuda = $estudiante['deuda'];
        $pago = $estudiante['pago'];
        $saldo = $estudiante['saldo'];
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $response = Resource::StoreStudentByProgram($id, $ci_per, $des_per, $deuda, $pago, $saldo);
                $course = $response[0]->{'ff_registrar_curso_persona'};
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($course);
    }
    //  * RP15. Cerrar el curso.
    //Route::post('closeFinallyProgram', 'ResourceController@closeFinallyProgram');
    public function closeFinallyProgram(Request $request)
    {
        $id = $request->get('id');
        $data = Resource::CloseFinallyProgram($id);
        return json_encode($data);
    }
    //  * RP16. mostrar los pagos de un estudiante.
    //Route::post('getStudentDetails', 'ResourceController@getStudentDetails');
    public function getStudentDetails(Request $request)
    {
        $id = $request->get('id');
        $id_estudiante = $request->get('id_student');
        $data = Resource::GetStudentDetails($id, $id_estudiante);
        return json_encode($data);
    }
    //  * RP17. imprimir la certificacion del estudiante
    //Route::get('studentProgramCertificate/', 'DocumentController@studentProgramCertificate');
    public function studentProgramCertificate(Request $request)
    {
        $id = $request->get('id');
        $id_estudiante = $request->get('id_student');
        $nreport = 'ResourceStudentProgramCertificate';
        $controls = array(
            'p_programa' => $id,
            'estudiante' => $id_estudiante,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
    //  * RP3. Obtiene un estudiante registrado en un curso.
    //Route::post('getStudentProgramById', 'DocumentController@getStudentProgramById');
    public function getStudentProgramById(Request $request)
    {
        $id_programa = $request->get('id_programa');
        $ci_per = $request->get('ci_per');
        $data = Resource::GetStudentProgramById($id_programa, $ci_per);
        return json_encode($data);
    }
    //  * RP4. Obtiene los tipos de pago.
    //Route::post('getFormSaleProgram', 'DocumentController@getFormSaleProgram');
    public function getFormSaleProgram(Request $request)
    {
        $data = Resource::GetFormSaleProgram();
        return json_encode($data);
    }
    //  * RP18. Obtener los pagos de un curso de postgrado.
    //Route::post('getSalesStudentByProgram', 'DocumentController@getSalesStudentByProgram');
    public function getSaleStudentByProgram (Request $request)
    {
        $id = $request->get('id');
        $data = Resource::GetSaleStudentByProgram($id);
        return json_encode($data);
    }

    //  * RP19. Agregar un pago de estudiantes al curso.
    //Route::post('storeSaleStudentProgram', 'DocumentController@storeSaleStudentProgram');
    public function storeSaleStudentProgram(Request $request)
    {
        $estudiante = $request->get('estudiante');
        $id_programa = $request->get('programa');

        $ci_per = strtoupper($estudiante['ci_per']);
        $des_per = strtoupper($estudiante['des_per']);
        $importe = $estudiante['importe'];
        $fecha = $estudiante['fecha'];
        $id_metodo = $estudiante['id_metodo'];
        $comprobante = $estudiante['comprobante'];
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $response = Resource::StoreSaleStudentProgram($id_programa, $ci_per, $des_per, $importe, $fecha, $id_metodo, $comprobante);
                $course = $response[0]->{'ff_registrar_persona_pago'};
                $response = Resource::UpdateMountStudentProgram($id_programa, $ci_per);
                $course = $response[0]->{'ff_actualizar_pagos'};
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
