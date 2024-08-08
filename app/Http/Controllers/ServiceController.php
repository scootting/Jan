<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Libraries\JSRClient;

class ServiceController extends Controller
{
    //
    //  * SE1. Obtiene la lista de las ordenes de servicio realizadas
    public function getServicesOrder(Request $request)
    {
        $year = strtoupper($request->get('gestion'));
        $data = Service::GetServicesOrder($year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getServicesOrder')]
        );
        return json_encode($paginate);
    }

    //  * SE2. Guardar o Editar las ordenes de servicio realizadas
    public function storDataServiceOrder(Request $request)
    {
        $servicio = $request->get('servicio');
        $contratante = $request->get('contratante');
        $proveedor = $request->get('proveedor');

        $fecha = $servicio['fecha'];
        $cod_prg = $servicio['cod_prg'];
        $des_prg = $servicio['des_prg'];
        $descripcion = $servicio['descripcion'];
        $importe = $servicio['importe'];
        $fecha_inicial = $servicio['fecha_inicial'];
        $fecha_final = $servicio['fecha_final'];
        $observaciones = $servicio['observaciones'];
        
        $ci_contratante = $contratante['ci_contratante'];
        $des_contratante = $contratante['des_contratante'];

        $ci_proveedor = $proveedor['ci_proveedor'];
        $des_proveedor = $proveedor['des_proveedor'];
        $dir_proveedor = $proveedor['dir_proveedor'];
        $tel_proveedor = $proveedor['tel_proveedor'];

        $marcador = $request->get('marker');

        /*
        cod_prg VARCHAR(8),
        des_prg VARCHAR,
        ci_contratante VARCHAR,
        des_contratante VARCHAR,
        ci_proveedor VARCHAR,
        des_proveedor VARCHAR,
        dir_proveedor VARCHAR,
        tel_proveedor VARCHAR,
        descripcion TEXT,
        importe NUMERIC(10,2),
        fecha_inicial DATE,
        fecha_final DATE,
        observaciones VARCHAR
        */
        switch ($marcador) {
            case 'registrar':
                $data = Service::StoreDataServiceOrder($fecha, $cod_prg, $des_prg, $id_tipo, $ci_per, $des_per, $gestion, $direccion, $telefono, $correo);
                break;
            case 'editar':
                $data = Service::UpdateDataServiceOrder($id_solvencia, $fecha, $cod_prg, $des_prg, $id_tipo, $ci_per, $des_per, $gestion, $direccion, $telefono, $correo);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

}
