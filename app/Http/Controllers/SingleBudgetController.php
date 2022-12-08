<?php

namespace App\Http\Controllers;

use App\SingleBudget;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SingleBudgetController extends Controller
{
    //  * PIN1. Obtener una lista de los presupuestos individuales de el recurso utilizado.
    //  * {presupuesto individual: datos de la persona[descripcion, usuario, gestion]}
    public function getSingleBudget(Request $request)
    {
        $usuario = $request->get('user');
        $gestion = $request->get('year');        
        $data = SingleBudget::getSingleBudget($gestion, $usuario);
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
