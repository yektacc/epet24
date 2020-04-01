<?php namespace App\Http\Controllers;

		use Carbon\Carbon;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDeleteamlanimalsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "aml_animals";        
				$this->permalink   = "deleteamlanimals";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		try{
		$query_result = DB::table('aml_animals')
		->where("app_users_id",$postdata['app_users_id'])
		->pluck("id")
		->first();
		
		DB::table("aml_animals")
		    ->where("id",$query_result)
		    ->update([
		    "id" => $query_result,
/*
		    "app_users_id" => $postdata['app_users_id'],
		    "animal_name" => $query_result->animal_name,
		    "animal_image" => $query_result->animal_image,
		    "animal_age" => $query_result->animal_age,
		    "gender_id" => $query_result->gender_id,
		    "type_id" => $query_result->type_id,
		    "sterilization_id" => $query_result->sterilization_id,
*/
		    "deleted_at" => Carbon::now(),
		    ]);

		    $data['response_code'] = 0;
                    $results['api_status'] = 1;
                    $results['api_message'] = 'aml animal deleted';
                    $results['id'] = $query_result;

                } catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
                }
			echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			die;
		}

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}