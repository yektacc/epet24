<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetnewsellerprofileController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "new_seller_profile";        
				$this->permalink   = "getnewsellerprofile";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process

			$query = DB::table('new_seller_profile')
				->where('app_user_id', '=', $postdata['app_user_id'])
				->get();

		    $results['api_status'] = 1;
		    $results['api_message'] = 'get_new_seller_profile';
		    $results['data'] = $query->toArray();
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