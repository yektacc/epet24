<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Carbon\Carbon;

		class ApiSendamlanimalsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "aml_animals";        
				$this->permalink   = "sendamlanimals";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        try{
        $query_result = DB::table('aml_animals')
        ->where('app_users_id',$postdata['app_users_id'])
        ->where('deleted_at', NULL)
        ->pluck('id')
        ->first();
//    var_dump($query_result); exit;
    if($query_result == null){
    
        $aml_animals_id = DB::table('aml_animals')->insertGetId([
        'id' => NULL,
        'app_users_id' => $postdata['app_users_id'],
        'animal_name' => $postdata['animal_name'],
        'animal_image' => $postdata['animal_image'],
        'animal_age' => $postdata['animal_age'],
        'birth_date' => $postadata['birth_date'],
        'gender_id' => $postdata['gender_id'],
        'type_id' => $postdata['type_id'],
        'sterilization_id' => $postdata['sterilization_id'],
        'created_at' => Carbon::now()
        ],'id');

    $results['api_status'] = 1;
    $results['aml_animals_id']= $aml_animals_id;
    $results['api_message'] = 'aml_animals inserted';
    } 
    else
    {
    $results['api_status'] = 2;
    $results['aml_animals_id'] = $query_result;
    $results['api_message'] = 'aml_animals exist';
    }
    }
        catch (\Exception $exception){
    $results['api_status'] = 0;
    $results['api_message'] = 'server error'.'_'.$exception;
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