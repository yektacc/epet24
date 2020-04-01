<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiEmptyshoppingcartController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "emptyshoppingcart";    
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

                    if($order !== null){
                        $order_id = $order->id;
                        DB::table('prd_order_item')
                            ->where('order_id',$order_id)
                            ->delete();

                        DB::table('prd_order_statuses')
                            ->where('order_id',$order_id)
                            ->where('status_id',1)
                            ->orWhere('status_id',2)
                            ->delete();

                        DB::table('prd_orders')
                            ->where('id',$order_id)
                            ->delete();
                    }

                    $data['response_code'] = 0;
                    $results['api_status'] = 1;
                    $results['api_message'] = 'shopping cart removed';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
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