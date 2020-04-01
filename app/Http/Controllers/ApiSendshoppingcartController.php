<?php namespace App\Http\Controllers;

        use App\Helper\AppConfig;
		use Carbon\Carbon;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendshoppingcartController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "sendshoppingcart";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
                //This method will be execute before run the main process
               // var_dump($postdata['cart_items']); 
               // var_dump("test");
               // exit;
                try{
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

                    //check if the user has incomplete order
                    $order = DB::table('shop_orders')
                        ->where("customer_id",$user_id)
                        ->where("is_completed",0)
                        ->where("status_id",1)
                        ->first();

                    if($order !== null){
                        //if the user has incomplete order, it's older order items must be deleted
                        //and order date and total amount should be updated
                        $order_id = $order->id;
                        DB::table('prd_order_item')
                            ->where('order_id',$order_id)
                            ->delete();
//todo-mohajeri : why not deleted?
                        DB::table('prd_orders')
                            ->where('id',$order_id)
                            ->update([
                                  'deleted_at' => Carbon::now()
//                                'order_date' => Carbon::now(),
//                                'total_amount'=> $postdata['total_amount']
                            ]);

                        DB::table('prd_order_statuses')
                            ->where('order_id',$order_id)
                            ->where('status_id',2)
                            ->update([
                            'deleted_at' => Carbon::now()
                            ]);
//                            ->delete();

                        $order_code = $order->order_code;
                    }
                    else{
                        //if the user does'nt have any incomplete order, new order inserted and it's status be set
                        $order_code = AppConfig::generate_order_code();
                        $order_id = DB::table('prd_orders')->insertGetId([
                            'id' => NULL,
                            'order_code' => $order_code,
                            'customer_id' => $user_id,
                            'total_amount' => $postdata['total_amount']
                        ],'id');
			    
                        DB::table('prd_order_statuses')->insert([
                            'id' => NULL,
                            'status_id' => 1,
                            'order_id' => $order_id
                        ]);
                    }

                    //order items must be inserted
                    $list = json_decode($postdata['cart_items'],true);
//                    var_dump($list); exit;
                    $items = $list['items'];

                    
                    $shipment_time = 0;
                    $counter = 0;
//            var_dump($items[0]['item_id']); exit;
		if($postdata['request_type'] == "web"){
                    for($i = 0 ; $i < count($items) ; $i++){

                        $time = DB::table('prd_sale_item')
                            ->where('id',$items[$i]['item_id'])
                            ->pluck('shipment_time')
                            ->first();

                        $shipment_time = $time > $shipment_time ? $time : $shipment_time;
                        
//                    $time = DB::table('prd_sale_item')
//                            ->where('id',$items[$i][$counter]['item_id'])
//                            ->update([
//                            'stock_quantity' => DB::raw('stock_quantity' - $items[$i][$counter]['quantity'])
//                            ]);
                        
                        DB::table('prd_order_item')->insert([
                            'id' => NULL,
                            'order_id' => $order_id,
                            'item_id' => $items[$i][0]['item_id'],
                            'quantity' => $items[$i][0]['quantity'],
                            'unit_price' => $items[$i][0]['unit_price']
                        ]);
                    }
                    }
                    elseif($postdata['request_type'] == "app"){
                    for($i = 0 ; $i < count($items) ; $i++){

                        $time = DB::table('prd_sale_item')
                            ->where('id',$items[$i]['item_id'])
                            ->pluck('shipment_time')
                            ->first();

                        $shipment_time = $time > $shipment_time ? $time : $shipment_time;
                        
//                    $time = DB::table('prd_sale_item')
//                            ->where('id',$items[$i][$counter]['item_id'])
//                            ->update([
//                            'stock_quantity' => DB::raw('stock_quantity' - $items[$i][$counter]['quantity'])
//                            ]);
                        
                        DB::table('prd_order_item')->insert([
                            'id' => NULL,
                            'order_id' => $order_id,
                            'item_id' => $items[$i]['item_id'],
                            'quantity' => $items[$i]['quantity'],
                            'unit_price' => $items[$i]['unit_price']
                        ]);
                    }
                    }

                    DB::table('prd_orders')
                        ->where('id',$order_id)
                        ->update([
                            'shipment_time'=> $shipment_time,
                            'total_amount' => $postdata['total_amount']
                        ]);

                    $data['response_code'] = 0;
                    $data['order_id'] = $order_id;
                    $data['order_code'] = $order_code;
                    $data['shipment_time'] = $shipment_time;

                    $results['api_status'] = 1;
                    $results['api_message'] = 'shopping card saved';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'. $exception;
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