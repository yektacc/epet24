<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSavefinalshoppingorderController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "savefinalshoppingorder";    
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
                        ->where("status_id",2)
                        ->first();

                    DB::table('prd_customer_addresses')
                         ->where("id",$postdata['transferee_address_id'])
                         ->update([
                             'editable' => 2
                         ]);

                    if($order !== null){
                        $order_id = $order->id;

                        DB::table('prd_order_statuses')
                            ->where('order_id',$order_id)
                            ->where('status_id',1)
                            ->orWhere('status_id',2)
                            ->delete();

//                        $status_id = 4;
//
//                        if($postdata['payment_method'] == 1)
//                            $status_id = 3;
                        $status_id = ($postdata['payment_method'] == 1) ? 4 : 3;

                        DB::table('prd_order_statuses')->insert([
                            'id' => NULL,
                            'status_id' => $status_id,
                            'order_id' => $order->id
                        ]);

                        if($postdata['payment_method'] == 2){
                            DB::table('prd_order_statuses')->insert([
                            'id' => NULL,
                            'status_id' => 3,
                            'order_id' => $order->id
                            ]);
                        }

                        // Adding to sale_count in prd_sale_item 
                        $order_item = DB::table('prd_order_item')
                        ->where("order_id",$order->id)
                        ->get(); 

                        
                        foreach($order_item as $ord_itm)
                        {
                        $quantity = $ord_itm->quantity;
                            DB::table('prd_sale_item')
                                    ->where('id',$ord_itm->item_id)
                                    ->update([
                                'sale_count' => DB::raw('sale_count' + '$quantity')
                            ]);
                        }
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