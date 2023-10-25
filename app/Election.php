<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Election extends Model
{
    //  * Recupera la informacion de la persona y si se encuentra habilitada o no
    public static function GetInformationPerson($id, $id_election)
    {
        $query = "select * from econ.ff_datos_persona('" . $id . "','" . $id_election . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Recupera la informacion de la mesa en la cual deberia votar la persona
    public static function GetInformationTabletByPerson($id, $id_election)
    {
        $query = "select * from econ.ff_datos_persona_mesa('" . $id . "','" . $id_election . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * Recupera la informacion de la mesa en la cual deberia votar la persona
    public static function getInformationTablets($id_election)
    {
        $query = "select * from econ.mesas where id_claustro = '" . $id_election . "' order by numero";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * E4 . Obtener la lista de candidatos habilitados para la eleccion
    public static function getInformationCandidates($id, $id_election)
    {
        $query = " select * from econ.ff_datos_votos_mesa('" . $id . "','" . $id_election . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function StoreVotesForCandidates($id_mesa, $id_candidato, $votos, $id_claustro, $fecha)
    {
        $query = "select * from econ.ff_registrar_votos('" . $fecha . "'," . $id_mesa . "," . $id_candidato . "," . $votos . "," . $id_claustro . ")";
        $data = collect(DB::select(DB::raw($query)));
        $query = "select * from econ.ff_valorar_votos_mesa(" . $id_claustro . "," . $id_mesa . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function DeleteVotesForCandidates($id_mesa)
    {
        $query = "delete from econ.votacion v where v.id_mesa  =" . $id_mesa . "" ;
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * E4 . Obtener la votacion de las mesas por candidato
    public static function GetVotesForTablet($id_election)
    {
        $query = "select * from econ.mesas where id_claustro = '" . $id_election . "' order by numero";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function ValueTotalVotes($id_claustro)
    {
        $query = "select * from econ.ff_valorar_votos_finales(". $id_claustro . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


}
