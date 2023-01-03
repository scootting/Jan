<?php

namespace App\Http\Controllers;

use App\Archive;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArchiveController extends Controller
{
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }     
    public function getArchivesByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('description'));
        $data = Archive::GetArchivesByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    //  * parametros {id: identificador del archivo, year: gestion del archivo }     
    public function getDocumentsbyArchive(Request $request){
        $archivo = $request->get('id');
        $gestion = $request->get('year');
        $data = Archive::GetDocumentsbyArchive($archivo, $gestion);
        return json_encode($data);
    }
    
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    //  * parametros {description: descripcion del tipo de documento que se necesita }     
    public function getTypesDocument(Request $request){
        $descripcion = $request->get('description');
        $data = Archive::GetTypesDocument($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getTypesDocument')]
        );
        return json_encode($paginate);
        return json_encode($data);
    }

    //  * A6. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }     
    public function getFileContainerByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('description'));
        $data = Archive::getFileContainerByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 3;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }

    //  * A7. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros {archivo: datos del documento[descripcion, tipo]} 
    public function storeFileContainer(Request $request)
    {
        $archivo = $request->get('archivo');
        $glosa = strtoupper($archivo['glosa']);
        $tipo = $archivo['tipo'];
        $gestion = $archivo['gestion'];
        $marcador = $request->get('marker');
        \Log::info($glosa.' :glosa: '.$tipo.' :gestion: '.$gestion);
        switch ($marcador) {
            case 'registrar':
                $data = Archive::SaveFileContainer($glosa, $tipo, $gestion);
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($data);
    }
    //  * A10. Guardar un nuevo tipo de archivo 
    public function onStoreTypeArchive(Request $request)
    {
        $archivo = $request->get('archive');
        $codificacion = strtoupper($archivo['codification']);
        $descripcion = $archivo['description'];
        $tipo = $archivo['type'];
        $marcador = 'registrar';
        \Log::info($codificacion.' :descripcion: '.$descripcion.' :tipo: '.$tipo);
        switch ($marcador) {
            case 'registrar':
                $data = Archive::OnStoreTypeArchive($codificacion, $descripcion, $tipo);
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

}
