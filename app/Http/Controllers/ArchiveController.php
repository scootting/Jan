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
        $tipo = $request->get('id');
        $data = Archive::GetArchivesByDescription($descripcion, $tipo);
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
    public function getDocumentsbyArchive(Request $request)
    {
        $archivo = $request->get('id');
        $gestion = $request->get('year');
        $data = Archive::GetDocumentsbyArchive($archivo, $gestion);
        return json_encode($data);
    }

    //  * A3. Obtiene la lista de tipos de documentos
    //  * parametros {tipo: descripcion del tipo de documento que se necesita }
    public function getTypesDocument(Request $request)
    {
        $tipo = '';
        $data = Archive::GetTypesDocument($tipo);
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
    }

    //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo, contenedor o ubicacion
    //  * parametros {tipo: descripcion del tipo de documento que se necesita }
    public function getTypesDocumentById(Request $request)
    {
        $tipo = $request->get('id_type');
        $data = Archive::GetTypesDocument($tipo);
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
        \Log::info($glosa . ' :glosa: ' . $tipo . ' :gestion: ' . $gestion);
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

    //  * A8. Obtiene un tipo de archivo en especifico
    public function getTypeArchiveById($id)
    {
        $data = Archive::GetTypeArchiveById($id);
        return json_encode($data);
    }
    //  * A10. Guardar un nuevo tipo de archivo
    public function onStoreTypeArchive(Request $request)
    {
        $archivo = $request->get('archive');
        $marcador = $request->get('marker');
        \Log::info($request);
        $descripcion = $archivo['descr'];
        switch ($marcador) {
            case 'añadir':
                $tipo = $archivo['type'];
                $data = Archive::OnStoreTypeArchive($descripcion, $tipo);
                break;
            case 'editar':
                $id = $archivo['id'];
                $data = Archive::OnUpdateTypeArchive($id, $descripcion);
                break;
            default:
                break;
        }
        return json_encode($data);
    }
    //  * A11. Guardar los archivos del documento
    public function storeArchivesOfDocument(Request $request)
    {
        $dataArchivesOfDocument = $request->get('archivesOfDocument');
        $documento = $request->get('document'); //id del documento
        $gestion = $request->get('year');
        $marcador = $request->get('marker');

        $marker = Archive::deleteArchivesByDocument($documento);
        foreach ($dataArchivesOfDocument as $item) {
            # code...
            //indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: ""
            $indice = $item['indice'];
            $numeral = strtoupper($item['numeral']);
            $glosa = strtoupper($item['glosa']);
            $fecha = $item['fecha'];

            $id_tipo = $item['id_tipo'];
            $id_arch = $item['id_arch'];
            $descripcion = $item['descr'];
            $marker = Archive::addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion);
            $id_tran = 0;
        }
        return json_encode($marker);
    }
}
