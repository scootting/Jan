<?php

namespace App\Http\Controllers;


use App\Archive;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArchiveController extends Controller
{
    //
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }     
    public function getArchivesByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('descripcion'));
        $data = Archive::GetArchivesByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 1;

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
