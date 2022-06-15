<?php

namespace App\Http\Controllers;

use App\Store;
use App\Libraries\DynamicMenu;
use App\Libraries\JWTFAuth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreController extends Controller
{
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }     
    public function getMaterialsByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('description'));
        $data = Store::GetMaterialsByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }
    public function storeMaterial(Request $request)
    {
        $materiales = $request->get('materiales');
        $mat_cod = strtoupper($materiales['mat_cod']);
        $mat_des = strtoupper($materiales['mat_des']);
        $mat_uni_med = strtoupper($materiales['mat_uni_med']);

        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $data = General::AddMaterial($mat_cod, $mat_des, $mat_uni_med);
                break;
            case 'editar':
                $data = General::UpdateMaterial($mat_cod, $mat_des, $mat_uni_med);
                break;
            default:
                break;
        }
        return json_encode($data);
    }
}
