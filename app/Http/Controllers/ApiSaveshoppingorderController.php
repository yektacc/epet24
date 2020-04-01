<?php namespace App\Http\Controllers;

		use Carbon\Carbon;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSaveshoppingorderController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "saveshoppingorder";
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

                    $order = DB::table('shop_orders')
                        ->where("customer_id",$user_id)
                        ->where("is_completed",0)
                        ->where("status_id",1)
                        ->first();

                    DB::table('prd_orders')
                        ->where("id",$order->id)
                        ->update([
                            'order_date' => Carbon::now(),
                            'shipment_cost' => $postdata['shipment_cost'],
                            'transferee' => $postdata['transferee_address_id'],
                            'total_amount' => $postdata['total_amount'],
                            'delivery_date' => $postdata['delivery_date'],
                        ]);

//                    DB::table('prd_customer_addresses')
//                        ->where("id",$postdata['transferee_address_id'])
//                        ->update([
//                            'editable' => 2
//                        ]);

                    $status_row = DB::table('prd_order_statuses')
                        ->where('order_id',$order->id)
                        ->where('status_id',2)
                        ->first();
                    
//                    var_dump($orser); exit;
                    if($status_row == null){
                        DB::table('prd_order_statuses')->insert([
                            'id' => NULL,
                            'status_id' => 2,
                            'order_id' => $order->id
                        ]);
                    }

                    $data['response_code'] = 0;

                    $results['api_status'] = 1;
                    $results['api_message'] = 'shopping order saved';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
                }
//
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