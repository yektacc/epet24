<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetprovincecitynameController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cities";        
				$this->permalink   = "getprovincecityname";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata)
            {
                //This method will be execute before run the main process
               try{
                   $rows = DB::table('province_city')->get();
                   $query_result = [];

                   $rows = $rows->toArray();

                   foreach ($rows as $row) {

                       if (count($query_result) == 0) {
                           $query_result[] = [
                               "province_name" => $row->province,
                               "province_id" => $row->province_id,
                               "cities" => [
                                   [
                                       "city_id" => $row->id,
                                       "city_name" => $row->city,
                                       "citycenter_N" => $row->citycenter_N,
                                       "citycenter_E" => $row->citycenter_E,
                                       "map_zoom" => $row->map_zoom,
                                   ]

                               ]
                           ];
                           continue;
                       }

                       for ($i = 0; $i < count($query_result); $i++) {

                           if ($row->province == $query_result[$i]["province_name"]) {

                               $query_result[$i]["cities"][] = [
                                   "city_id" => $row->id,
                                   "city_name" => $row->city,
                                   "citycenter_N" => $row->citycenter_N,
                                   "citycenter_E" => $row->citycenter_E,
                                   "map_zoom" => $row->map_zoom,
                               ];
                               break;
                           }
                       }

                       if ($i == count($query_result)) {
                           $query_result[] = [
                               "province_name" => $row->province,
                               "province_id" => $row->province_id,
                               "cities" => [
                                   [
                                       "city_id" => $row->id,
                                       "city_name" => $row->city,
                                       "citycenter_N" => $row->citycenter_N,
                                       "citycenter_E" => $row->citycenter_E,
                                       "map_zoom" => $row->map_zoom,
                                   ]
                               ]
                           ];
                       }
                   }

                   $results['api_status'] = 1;
                   $results['api_message'] = 'province city';
                   $results['data'] = $query_result;
               }
               catch (\Exception $exception){
                   $results['api_status'] = 0;
                   $results['api_message'] = 'server error';
               }

                echo json_encode($results,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}