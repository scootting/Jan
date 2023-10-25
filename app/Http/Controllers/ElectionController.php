<?php

namespace App\Http\Controllers;

use App\Election;
use Illuminate\Http\Request;
use App\Libraries\JSRClient;

class ElectionController extends Controller
{
    //

    //  * E1 . Obtener la persona por su carnet de identidad y la mesa donde deberia realizar su boto
    //  * parametros {id: numero de carnet de identidad de la persona, id_election:  id de la eleccion que se esta realizando}
    public function getAuthorizedPerson(Request $request)
    {
        $id = $request->get('id');
        $id_election = $request->get('id_election');
        $id_election = 2;
        //  * Recupera la informacion de la persona y si se encuentra habilitada o no
        $dataPerson = Election::GetInformationPerson($id, $id_election);
        //\log::info($dataPerson);
        //  * Recupera la informacion de la mesa en la cual deberia votar la persona
        $dataTablet = Election::GetInformationTabletByPerson($id, $id_election);
        return json_encode(['dataPerson' => $dataPerson, 'dataTablet' => $dataTablet]);
    }
    //  * E3 . Obtener la lista de mesas habilitadas para la eleccion
    public function getInformationTablets(Request $request)
    {
        $id_election = $request->get('id_election');
        $id_election = 2;
        $dataTablets = Election::GetInformationTablets($id_election);
        return json_encode(['dataTablets' => $dataTablets] );
    }

    //  * E4 . Obtener la lista de candidatos habilitados para la eleccion
    public function getInformationCandidates(Request $request)
    {
        $id_tablet = $request->get('id_tablet');
        $id_election = $request->get('id_election');
        $id_election = 2;
        $dataCandidates = Election::GetInformationCandidates($id_election, $id_tablet);
        return json_encode(['dataCandidates' => $dataCandidates]);
    }
    //  * E5 . Guardar las votaciones por candidatos y mesa
    //Route::post('storeVotesForCandidates', 'ElectionController@storeVotesForCandidates');
    public function storeVotesForCandidates(Request $request)
    {
        $candidates = $request->get('dataCandidates');
        $id_mesa = $request->get('id_mesa');
        $marcador = 'registrar';
        $fecha = '28/09/2023';
        switch ($marcador) {
            case 'registrar':
                $documento = 0;
                $documento = Election::DeleteVotesForCandidates($id_mesa);
                foreach ($candidates as $item) {
                    $id_candidato = $item['id'];
                    $votos = $item['votos'];
                    $id_claustro = $item['id_claustro'];
                    $response = Election::StoreVotesForCandidates($id_mesa, $id_candidato, $votos, $id_claustro, $fecha);
                    //$documento = $response[0]->{'ff_registrar_votos'};
                }
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($marcador);
    }    

    //  * E6 . Muestra el reporte de los datos por mesa
    public function getReportTablet(Request $request)
    {
        $nreport = 'Econ_Votes_Tablet';
        $id_tablet = (int)$request->get('id_tablet');
        $id_election = 2;//$request->get('id_election');
        $controls = array(
            'id_mesa' => $id_tablet,
            'id_eleccion' => $id_election,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
    //  * E6 . Muestra el reporte de los datos por mesa
    public function getReportTablet2(Request $request)
    {
        $nreport = 'EconInformationPerson2';
        $id_tablet = (int)$request->get('id_tablet');
        $id_election = 2;//$request->get('id_election');
        $controls = array(
            'id_mesa' => $id_tablet,
            'id_eleccion' => $id_election,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
    //  * E7 . Muestra el reporte general de los datos por mesa
    public function getReportGeneralTablet(Request $request)
    {
        $nreport = 'Econ_Resume_Votes_';
        //$id_tablet = (int)$request->get('id_tablet');
        //$id_election = 2;//$request->get('id_election');
        $controls = array(
            //'id_mesa' => $id_tablet,
            //'id_eleccion' => $id_election,
        );
        \log::info($nreport);
        \log::info("entra");
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    public function getReportGeneralTablet2(Request $request)
    {
        $id_claustro = 2;
        $response = Election::ValueTotalVotes($id_claustro);
        $nreport = 'Econ_Resume_Votes_General';
        //$id_tablet = (int)$request->get('id_tablet');
        $id_election = 2;//$request->get('id_election');
        $stament = $request->get('stament');
        $controls = array(
            //'id_mesa' => $id_tablet,
            'id_eleccion' => $id_election,
            'p_estamento' => $stament,
        );
        \log::info($nreport);
        \log::info("entra");
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
}
