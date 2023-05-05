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
        $documento = $request->get('document');
        $gestion = $request->get('year');
        $marcador = $request->get('marker');

        /*
        $idx = Treasure::getIdTransactionsByYear($gestion);
        $idx = $idx[0]->{'ff_id_tramite'};
        $nro_com = str_pad($idx, 6, "0", STR_PAD_LEFT);
         */
        $tip_tra = '10';

        if ($paterno != "") {
            $des_per = $paterno . " " . $materno . ", " . $nombres;
        } else {
            $des_per = $materno . "," . $nombres;
        }

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
            if ($indice == 0) {
                //insertar
                $marker = Archive::addArchivesByDocument($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, '-1', $ci_per, $des_per, $tip_tra, $gestion);
                $id_tran = $marker[0]->{'id_tran'};
            }
            else{
                //actualizar
                $data = Treasure::updateArchivesByDocument($id_dia, $id_tran, -1, $cod_val, $ci_per, $des_per, -1, $gestion, $des_tra, $pre_uni);
            }
            $id_tran = 0;
        }
        return json_encode($marker);
    }
}
