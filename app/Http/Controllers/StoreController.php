<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreController extends Controller
{
    //  * M1. Obtiene la lista de materiales con una breve descripcion
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

    //  * M2. Obtiene la lista de movimientos del material
    //  * parametros {codigo: codigo del material, gestion: la gestion que se esta consultando }
    public function getMovementOfMaterial(Request $request)
    {

        $codigo = $request->get('codigo');
        $gestion = $request->get('gestion');
        $data = Store::GetMovementOfMaterial($codigo, $gestion);
        return json_encode($data);
    }

    public function storeMaterial(Request $request)
    {
        $material = $request->get('material');
        $mat_cod = strtoupper($material['mat_cod']);
        $mat_des = strtoupper($material['mat_des']);
        $mat_uni_med = strtoupper($material['mat_uni_med']);

        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $data = Store::AddMaterial($mat_cod, $mat_des, $mat_uni_med);
                break;
            case 'editar':
                $data = Store::EditMaterial($mat_cod, $mat_des, $mat_uni_med);
                break;
            default:
                break;
        }
        return json_encode($data);
    }
    public function getMaterialById($id)
    {
        $data = Store::GetMaterialByIdentityCard($id);
        return json_encode($data);
    }
}
