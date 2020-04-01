<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiPrdordersellertransactionController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_seller_transaction";        
				$this->permalink   = "prdordersellertransaction";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		try{
		
		    if($postdata['order_id'])
		    {
			if($postdata['seller_id'])
			    {
				if($postdata['tariff_amount'])
				{
				    $result_query = DB::table('prd_order_seller_transaction')->insert([
					'order_id' => $postdata['order_id'],
					'seller_id' => $postdata['seller_id'],
					'tariff_amount' => $postdata['tariff_amount'],
					'coupon_code_id' => $postdata['coupon_code_id'],
					'coupon_discount' => $postdata['coupon_discount']
					]);
				    $results['api_message'] = "tariff added";
				}
				else
				{
				    $results['api_message'] = "tariff amount required";
				}
			    }
			    else
			    {
				$results['api_message'] = "seller id required";	
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