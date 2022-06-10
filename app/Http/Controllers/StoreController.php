<?php

namespace App\Http\Controllers;

use App\Store;
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
}
