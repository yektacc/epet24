<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetshoppingcartController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "getshoppingcart";    
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

                    $data = [];

                    if($order !== null){
                        $data = [
                            "order_code" => $order->order_code,
                            "shipment_time" => $order->shipment_time,
                            "total_amount" => $order->total_amount,
                        ];

                        $items = DB::table('shop_order_items')
                            ->where('order_id',$order->id)
                            ->get();

                        $data["items"] = [];

                        foreach ($items as $item){
                            $data["items"][] = [
                                "item_id" => $item->item_id,
                                "quantity" => $item->quantity,
                                "product_id" => $item->product_id,
                                "product_title" => $item->product_title,
                                "product_seller" => $item->product_seller,
                                "product_price" => $item->product_price,
                                "product_guarantee" => $item->product_guarantee,
                                "product_color" => $item->product_color
                            ];
                        }
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'shopping card';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
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