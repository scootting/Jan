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
        $contenedor = $request->get('document');
        $gestion = $request->get('year');
        $marcador = $request->get('marker');
        \Log::info($contenedor);
        /*
        $glosa = strtoupper($contenedor['glosa']);
        $tipo = $contenedor['tipo'];
        $gestion = $contenedor['gestion'];
        $marcador = $request->get('marker');

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
        return json_encode($data);*/
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
        $descripcion = strtoupper($archivo['descr']);
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
        $gestion = $request->get('year');
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'añadir':
                # code add...
                $documento = 0;
                foreach ($dataArchivesOfDocument as $item) {
                    //indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: ""
                    $indice = $item['indice'];
                    $numeral = strtoupper($item['numeral']);
                    $glosa = strtoupper($item['glosa']);
                    $fecha = $item['fecha'];

                    $id_tipo = $item['id_tipo'];
                    $id_arch = $item['id_arch'];
                    $descripcion = $item['descr'];
                    if($indice == 1){
                        $response = Archive::AddHeaderOfDocument($id_tipo, $glosa, $fecha, $id_arch, $gestion);
                        \Log::info($response);
                        $documento = $response[0]->{'ff_registrar_documento'};
                    }
                    $marker = Archive::addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion);
                }
                
                break;
            case 'editar':
                # code edit...
                $documento = $request->get('document'); //id del documento
            
                $marker = Archive::deleteArchivesByDocument($documento);
                foreach ($dataArchivesOfDocument as $item) {
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
                break;
            default:
                break;

        }
        return json_encode($marker);
    }

    //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual y se encuentre libres
    public function getDocumentAndFilesContainerById(Request $request)
    {
        $id_fileContainer = $request->get('id');
        \Log::info($id_fileContainer);
        $document = Archive::GetDataDocumentById($id_fileContainer);
        /*
        $data = Archive::GetDocumentAndFilesContainerById($id_fileContainer);
        foreach ($data as $key => $item) {
        # code...
        if(item.tipo_rama = 'A')
        $arrayDOCUMENTOS
        else {
        $arrayCONTENENDORES
        }
        }
         */
        $fileContainer = Archive::GetDetaFileContainerById($id_fileContainer);
        // *A15 Obtiene un documento o contenedor o ubiacion por su id
        $container = Archive::GetFileContainerById($id_fileContainer);
        return json_encode(['documents' => $document, 'fileContainer' => $fileContainer, 'container' => $container]);

    }

    //  * A13. Obtiene la lista de documentos y contenedores que estan libres para su registro
    public function getDocumentAndContainerFree(Request $request)
    {
        $id_fileContainer = $request->get('id');
        \Log::info($id_fileContainer);
        $dataDocuments = Archive::GetDocumentFree($id_fileContainer);
        $dataContainers = Archive::GetContainerFree($id_fileContainer);
        return json_encode(['dataDocuments' => $dataDocuments, 'dataContainers' => $dataContainers]);
    }

    //  * A14. Guardar los documentos y contenedores en el contenedor
    public function storeDocumentsAndContainers(Request $request)
    {
        $documentos = $request->get('documents'); // matrices
        $contenedores = $request->get('fileContainer'); //matrices
        $usuario = $request->get('username'); //dato
        $gestion = $request->get('year'); //dato
        $id_raiz = $request->get('id_container'); //dato
        $id_tipo_raiz = $request->get('id_type'); //dato
        //$contenedor = $request->get('container');//array
        $marcador = $request->get('marker');

        //$id_raiz = $contenedor['id'];//array
        //$id_tipo_raiz = $contenedor['id_tipo'];//dato

        $marker = Archive::deleteDocumentsAndContainerFromContainer($id_raiz);
        foreach ($documentos as $item) {
            \log::info($item);
            $id_rama = $item['id'];
            $id_tipo_rama = strtoupper($item['id_tipo']);
            $marker = Archive::AddDocumentsAndContainers($id_rama, $id_tipo_rama, $id_raiz, $id_tipo_raiz, $usuario, $gestion);
            $id_tran = 0;
        }
        foreach ($contenedores as $item) {
            \log::info($item);
            $id_rama = $item['id'];
            $id_tipo_rama = strtoupper($item['id_tipo']);
            $marker = Archive::AddDocumentsAndContainers($id_rama, $id_tipo_rama, $id_raiz, $id_tipo_raiz, $usuario, $gestion);
            $id_tran = 0;
        }
        return json_encode($marker);
    }

}
