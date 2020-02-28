<?php
namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller {

    public function index(Request $request)
    {
        // Log::info($request->input('action'));
        // return response()->json('success?!');
        // return response()->json(Location::get_states($request->all()));
        // return response()->json($request);

        if ( $request->has('action') && $request->input('action') == 'autocomplete' ) {
            $response = $this->autocomplete( $request->input('query') );
        } else {
            $response = $this->getLocation( $request->all() );
        }

        return $response;

    }

    // private $db;
    // private $requestMethod;
    // private $args;
    //
    // private $locationGateway;
    //
    // public function __construct($db, $requestMethod, $args)
    // {
    //     $this->db = $db;
    //     $this->requestMethod = $requestMethod;
    //     $this->args = $args;
    //
    //     $this->locationGateway = new LocationGateway($db);
    // }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ( isset($this->args['action']) && $this->args['action'] = 'autocomplete' ) {
                    $response = $this->autocomplete($this->args['query']);
                } elseif ($this->args) {
                    $response = $this->getLocation($this->args);
                }
                break;
            // case 'POST':
            //     $response = $this->createLocationFromRequest();
            //     break;
            // case 'PUT':
            //     $response = $this->updateLocationFromRequest($this->args);
            //     break;
            // case 'DELETE':
            //     $response = $this->deleteLocation($this->args);
                // break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getLocation($args) {
        switch ($args['type']) {
            case 'state':
                $result = Location::get_states($args);
                break;
            case 'county':
                $result = Location::get_counties($args);
                break;
            case 'city':
                $result = Location::get_cities($args);
                break;
            case 'neighborhood':
                $result = Location::get_neighborhoods($args);
                break;
            case 'postal':
                $result = Location::get_postal($args);
                break;
            default:
                $result = 'Nothing found';
                break;
        }
        // if (!$result) {
        //     return $this->notFoundResponse();
        // }
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        // $response['body'] = json_encode($result);
        return $result;
    }

    private function autocomplete($query) {

        $locations = array();

        if( is_numeric($query) ) {

            $postal_codes = Location::get_postal( array( 'fields' => 'postal_id,postal_code,state_id,state_abr,city_id,city_name,county_id', 'postal_%' => trim($query), 'per_page' => 10, 'paged' => 1 ));
            if( !empty($postal_codes) ) {
                foreach( $postal_codes as $postal) {
                    $postal = (array)$postal;
                    $locations[] = array(
                        'value' => $postal['postal_code'] .' '. $postal['city_name'] .', '. $postal['state_abr'],
                        'data' => [ 'category' => 'postal codes', 'postal_id' => $postal['postal_id'], 'city_id' => $postal['city_id'], 'county_id' => $postal['county_id'], 'state_id' => $postal['state_id'] ] //'coordinates' => $postal['geojson']
                    );
                }
            }

        } else {
            $results = Location::find_locartions($query);
            if( !empty($results) ) {
                foreach ( $results as $result ) {
                    $result = (array)$result;
                    if ($result['city_id']) {
                        $locations[] = array(
                            'value' => $result['city_name'] .', '. $result['state_abr'],
                            'data' => [ 'category' => 'cities', 'city_id' => $result['city_id'], 'county_id' => $result['county_id'], 'state_id' => $result['state_id'] ]
                        );
                    } elseif ($result['county_id']) {
                        $locations[] = array(
                            'value' => $result['county_name'] .', '. $result['state_abr'],
                            'data' => [ 'category' => 'counties', 'county_id' => $result['county_id'], 'state_id' => $result['state_id'] ]
                        );
                    } elseif ($result['state_id']) {
                        $locations[] = array(
                            'value' => $result['state_name'],
                            'data' => [ 'category' => 'state', 'state_id' => $result['state_id'] ] //, 'coordinates' => $result['geojson']
                        );
                    }
                }
            }

        }

        $result = array(
            'suggestions' => $locations
        );
        $result['success'] = 1;

        // if (!$result) {
        //     return $this->notFoundResponse();
        // }
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        // $response['body'] = json_encode($result);
        return $result;
    }

    private function createLocationFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validatePerson($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->locationGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    // private function updateLocationFromRequest($id)
    // {
    //     $result = $this->locationGateway->find($id);
    //     if (! $result) {
    //         return $this->notFoundResponse();
    //     }
    //     $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    //     if (! $this->validatePerson($input)) {
    //         return $this->unprocessableEntityResponse();
    //     }
    //     $this->locationGateway->update($id, $input);
    //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
    //     $response['body'] = null;
    //     return $response;
    // }

    // private function deleteLocation($id)
    // {
    //     $result = $this->locationGateway->find($id);
    //     if (! $result) {
    //         return $this->notFoundResponse();
    //     }
    //     $this->locationGateway->delete($id);
    //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
    //     $response['body'] = null;
    //     return $response;
    // }

    private function validatePerson($input)
    {
        if (! isset($input['firstname'])) {
            return false;
        }
        if (! isset($input['lastname'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
