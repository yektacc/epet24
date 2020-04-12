<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Carbon\Carbon;
	use App\Helper\AppConfig;
	
	class ApiOrdertransferController extends \crocodicstudio\crudbooster\controllers\ApiController {

	    function __construct() {    
		$this->table       = "prd_order_seller_transaction";        
		$this->permalink   = "ordertransfer";    
		$this->method_type = "post";    
	    }
	

	    public function hook_before(&$postdata) {
	        //This method will be execute before run the main process
	        try{
    
    if(isset($postdata['order_id']))
        {
         if(isset($postdata['seller_id']))
            {
            if (isset($postdata['transfer_comment']))
             {
				    $result_query = DB::table('prd_order_seller_transaction')
					->where('order_id',$postdata['order_id'])
					->where('seller_id',$postdata['seller_id'])
					->pluck('id')
					->first();
				    if(! $result_query == null){

				    DB::table('prd_order_seller_transaction')
					->where('id',$result_query)
					->update([
					'order_transfer_time' => Carbon::now(),
					'transfer_comment' => $postdata['transfer_comment']
					]);
					
				    $user_id = DB::table('prd_orders')
					->where('id',$postdata['order_id'])
					->pluck('customer_id')
					->first();
					
				    $order_code = DB::table('prd_orders')
					->where('id',$postdata['order_id'])
					->pluck('order_code')
					->first();
					
				    $mobile_number = DB::table("app_users")
						->where("id",$user_id)
						->pluck("mobile_number")
						->first();

					    //send otp via sms to customer
					    $alert_text = "ارسال محموله به شماره سفارش".$order_code." با موفقیت انجام شد. سبد خرید شما بزودی از طریق پست یا باربری به دست شما خواهد رسید.";
						AppConfig::sendSMS($mobile_number,$alert_text);
					$results['api_message'] = "sucsses";
	}
	else {
				    DB::table('prd_order_seller_transaction')
					->insert([
					'id' => null,
					'order_id' => $postdata['order_id'],
					'seller_id' => $postdata['seller_id'],
					'order_transfer_time' => Carbon::now(),
					'transfer_comment' => $postdata['transfer_comment']
					]);
					
				    $user_id = DB::table('prd_orders')
					->where('id',$postdata['order_id'])
					->pluck('customer_id')
					->first();
					
				    $order_code = DB::table('prd_orders')
					->where('id',$postdata['order_id'])
					->pluck('order_code')
					->first();
					
				    $mobile_number = DB::table("app_users")
						->where("id",$user_id)
						->pluck("mobile_number")
						->first();

					    //send otp via sms to customer
					    $alert_text = "ارسال محموله به شماره سفارش".$order_code." با موفقیت انجام شد. سبد خرید شما بزودی از طریق پست یا باربری به دست شما خواهد رسید.";
						AppConfig::sendSMS($mobile_number,$alert_text);
					$results['api_message'] = "sucsses";
	}
	}
	else
	{
//		$results['api_message'] = "transfer commnet required";
	$result_query = DB::table('prd_order_seller_transaction')
	    ->where('order_id',$postdata['order_id'])
	    ->where('seller_id',$postdata['seller_id'])
	    ->pluck('id')
	    ->first();
	if (! $result_query == null){
	DB::table('prd_order_seller_transaction')
	    ->where('id',$result_query)
	    ->update([
	    'order_transfer_time' => Carbon::now(),
	    'transfer_comment' => "محموله مورد نظر بسته بندی و آماده ارسال شد"
	    ]);
	    $user_id = DB::table('prd_orders')
	    ->where('id',$postdata['order_id'])
	    ->pluck('customer_id')
	    ->first();
	    
	    $order_code = DB::table('prd_orders')
	    ->where('id',$postdata['order_id'])
	    ->pluck('order_code')
	    ->first();
	    
	    $mobile_number = DB::table("app_users")
                    ->where("id",$user_id)
                    ->pluck("mobile_number")
                    ->first();
                    
                    //send otp via sms to customer
                    $alert_text = "ارسال محموله به شماره سفارش".$order_code." با موفقیت انجام شد. سبد خرید شما بزودی از طریق پست یا باربری به دست شما خواهد رسید.";
                    AppConfig::sendSMS($mobile_number,$alert_text);
	    $results['api_message'] = "sucsses";
	}
	
	else {
	DB::table('prd_order_seller_transaction')
	    ->insert([
	    'id' => null,
	    'order_id' => $postdata['order_id'],
	    'seller_id' => $postdata['seller_id'],
	    'order_transfer_time' => Carbon::now(),
	    'transfer_comment' => "محموله مورد نظر بسته بندی و آماده ارسال شد"
	    ]);
	    
	$user_id = DB::table('prd_orders')
	    ->where('id',$postdata['order_id'])
	    ->pluck('customer_id')
	    ->first();
	    
	$order_code = DB::table('prd_orders')
	    ->where('id',$postdata['order_id'])
	    ->pluck('order_code')
	    ->first();
	    
	$mobile_number = DB::table("app_users")
                    ->where("id",$user_id)
                    ->pluck("mobile_number")
                    ->first();

                //send otp via sms to customer
                $alert_text = "ارسال محموله به شماره سفارش".$order_code." با موفقیت انجام شد. سبد خرید شما بزودی از طریق پست یا باربری به دست شما خواهد رسید.";
                    AppConfig::sendSMS($mobile_number,$alert_text);
	    $results['api_message'] = "sucsses";
	}
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

//	


	    public function hook_query(&$query) {
	        //This method is to customize the sql query

	    }

	    public function hook_after($postdata,&$result) {
	        //This method will be execute after run the main process

	    }

	}