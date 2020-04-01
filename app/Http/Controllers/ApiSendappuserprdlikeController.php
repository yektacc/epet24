<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Carbon\Carbon;

		class ApiSendappuserprdlikeController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_user_prd_like";        
				$this->permalink   = "sendappuserprdlike";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		try{
	    $query_result = DB::table('app_user_prd_like')
	    ->where('app_user_id',$postdata['app_user_id'])
	    ->where('basecategory_id',$postdata['basecategory_id'])
	    ->where('subcategory_id',$postdata['subcategory_id'])
	    ->where('type_id',$postdata['type_id'])
	    ->where('prd_product_id',$postdata['prd_product_id'])
	    ->where('deleted_at', NULL)
	    ->pluck('id')
	    ->first();
	    
	if($query_result == null)	{
	    DB::table('app_user_prd_like')->insert([
	    'id' => NULL,
	    'app_user_id' => $postdata['app_user_id'],
	    'basecategory_id' => $postdata['basecategory_id'],
	    'subcategory_id' => $postdata['subcategory_id'],
	    'type_id' => $postdata['type_id'],
	    'prd_product_id' => $postdata['prd_product_id'],
	    'created_at' => Carbon::now()
	    ]);
	$results['api_status'] = 1;
	$results['api_message'] = 'app_user_prd_like inserted';
	} else {
	$results['api_status'] = 2;
	$results['api_message'] = 'app_user_prd_like exist';
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