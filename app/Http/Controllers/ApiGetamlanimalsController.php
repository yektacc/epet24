<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetamlanimalsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "aml_animals";        
				$this->permalink   = "getamlanimals";    
				$this->method_type = "post";    
		    }
		

		public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
			$query = DB::table('aml_animals')
			->where('app_users_id', '=', $postdata['app_users_id'])
			->where('deleted_at', NULL)
			->get();

		    $results['api_status'] = 1;
		    $results['api_message'] = 'aml_animals';
		    $results['data'] = $query->toArray();
		    echo json_encode($results,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		    die;

//		catch (\Exception $exception){
//		    $results['api_status'] = 0;
//		    $results['api_message'] = 'server error'.$exception;
//		}
		}
		    

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
			DB::table('api_call_stat')
                                ->where('api_name', '=', 'getamlanimals')
                                ->update(['count'=> DB::raw('count + 1')]);
		    }

		}