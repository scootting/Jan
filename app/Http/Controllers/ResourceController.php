<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
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
    
}
