<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetprdordersellertransactionController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_seller_transaction";        
				$this->permalink   = "getprdordersellertransaction";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        try{
    
	    if($postdata['order_id'])
	    {
	    if($postdata['seller_id'])
	    {
	$result_query = DB::table('prd_order_seller_transaction')
	    ->join('prd_orders','prd_orders.id','=','prd_order_seller_transaction.order_id')
	    ->where('order_id',$postdata['order_id'])
	    ->where('seller_id',$postdata['seller_id'])
	    ->get();

	    $results['api_message'] = $result_query;
	    }
	    else
	    {
	    $results['api_message'] = "seller id required";
	    }
	    }
	    else
	    {
	$result_query = DB::table('prd_order_seller_transaction')
                ->join('prd_orders','prd_orders.id','=','prd_order_seller_transaction.order_id')
		->where('seller_id',$postdata['seller_id'])
		->get();

	    $results['api_message'] = $result_query;
//	    $results['api_message2'] = $order_code;
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