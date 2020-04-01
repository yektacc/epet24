<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Carbon\Carbon;
	
	class ApiOrderpackingtimeController extends \crocodicstudio\crudbooster\controllers\ApiController {

	    function __construct() {    
		$this->table       = "prd_order_item";        
		$this->permalink   = "orderpackingtime";    
		$this->method_type = "post";    
	    }
	

	    public function hook_before(&$postdata) {
		//This method will be execute before run the main process
		try{
    
		if($postdata['order_id'])
		    {
		    if($postdata['item_id'])
			{
		    
		$result_query = DB::table('prd_order_item')
			->where('order_id',$postdata['order_id'])
			->where('item_id',$postdata['item_id'])
			->pluck('id')
			->first();
	    
		    DB::table('prd_order_item')
			->where('id',$result_query)
			->update([
			'packing_time' => Carbon::now(),
			]);
					    
			$results['api_message'] = "sucsses";
			
			}
		    else
		    {
		    $results['api_message'] = "item id required";
		    }
		    }
		else
		{
		$results['api_message'] = "order id required";
		}
	    
		}			
		catch (\Exception $exception){
		    $results['api_status'] = 0;
		    $results['api_message'] = 'server error'. $exception;
		}
		    //
		$results['api_status'] = 1;
		    
		$results['response_code'] = 0;
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