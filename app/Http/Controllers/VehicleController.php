<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request as Request;
use \GuzzleHttp\Client as Client;

define('NHTSA_URL', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/%s/make/%s/model/%s?format=json'); 
define('NHTSA_RATINGS_URL', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/%s');

class VehicleController extends Controller
{
    private $emptyResult = [
        'Count' => 0,
        'Result' => [],
    ];

    private function getVehicle($year, $make, $model) {
        $client = new Client();

        $url = sprintf(NHTSA_URL, urldecode($year), urldecode($make), urldecode($model));

        $res = $client->request('GET', $url);
        $body = json_decode($res->getBody());

        unset($body->Message);
        return $body;
    }

    private function getCrashRating($vehicleId) {
        $client = new Client();

        $url = sprintf(NHTSA_RATINGS_URL, $vehicleId);
        $res = $client->request('GET', $url);

        $json = json_decode($res->getBody());

        if (!empty($json->Results)) {
            return $json->Results[0]->OverallRating;
        }
        return '';
    }
    
    public function index(Request $req, $year, $make, $model) {
        $result = $this->getVehicle($year, $make, $model);

        if ($req->input('withRating') == 'true') {
            foreach ($result->Results as &$r) {
                $r->CrashRating = $this->getCrashRating($r->VehicleId);
            }
        }
        return response()->json($result);
    }

    public function vehicles(Request $req) {
        $result = $this->emptyResult;
        $json = $req->json()->all();
        
        if (is_array($json)
            && !empty($json['modelYear'])
            && !empty($json['manufacturer'])
            && !empty($json['model'])) {

            $result = $this->getVehicle($json['modelYear'], $json['manufacturer'], $json['model']);
        }

        return response()->json($result);
    }

}
