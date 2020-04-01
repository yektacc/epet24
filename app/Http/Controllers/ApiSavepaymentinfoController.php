<?php namespace App\Http\Controllers;

        use Carbon\Carbon;
        use App\Helper\AppConfig;
        use Illuminate\Contracts\Auth\CanResetPassword;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSavepaymentinfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_payments";        
				$this->permalink   = "savepaymentinfo";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{

                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

                    $order = DB::table('prd_orders')
                        ->where("order_code",$postdata['order_code'])
                        ->first();
                        
                    DB::table('prd_orders')
                                ->where('id',$order->id)
                                ->update([
                                    'is_completed' => 1
                                ]);
                    
                    //var_dump($order); exit;
                    if($order !== null){
                        $payment_status = 2; //unsuccessful payment

                        if($postdata['status'] > 0) {
                           
                            DB::table("prd_order_statuses")
                                ->where('order_id',$order->id)
                                ->update([
                                    "status_id" => 4 // change status of order to shippable
                                ]);
                            $payment_status = 1;

                            DB::table('prd_shippable_orders')->insert([ //save order in shippable orders list
                                'id' => NULL,
                                'order_id' => $order->id
                            ]);
                        }

                        $payment_id = DB::table("payments")
                            ->insertGetId([
                                "id" => NULL,
                                "ref_id" => $postdata['ref_id'],
                                "amount" => $postdata['amount'],
                                "status" => $payment_status,
                                "payment_date" => Carbon::now()
                            ],'id');

                        
                        DB::table("prd_order_payments")
                            ->insert([
                                "id" => NULL,
                                "order_id" => $order->id,
                                "payment_id" =>$payment_id
                            ]);

                        $order_items = DB::table('prd_order_item')
                            ->where("order_id",$order->id)
                            ->get();
                        foreach($order_items as $oi)
                        {
                            $update_sale_item = DB::table("prd_sale_item")
                                ->where('id',$oi->item_id)
                                ->update([
                                    'stock_quantity' => DB::raw('stock_quantity -'. intval($oi->quantity)) 
                            ]);
                        }

                        // save the coupon using for the order
                        if(isset($postdata['coupon_code_id']))
                        {
                            $order_seller_coupon = DB::table("prd_order_seller_transaction")
                                ->where('order_id',$order->id)
                                ->where('seller_id',$postdata['seller_id'])
                                ->update([
                                    'coupon_code_id' => $postdata['coupon_code_id']
                            ]);
                        }
                    }

                    $data['response_code'] = 0;

                    $results['api_status'] = 1;
                    $results['payment_id'] = $payment_id;
                    $results['api_message'] = 'payment information saved';
                    $results['data'] = $data;
                    
                    $mobile_number = DB::table("app_users")
                    ->where("id",$user_id)
                    ->pluck("mobile_number")
                    ->first();
                    
                    //send otp via sms to customer
                    $alert_text = "پرداخت سفارش شماره ".$order->order_code." با موفقیت انجام شد. سبد خرید شما بزودی برای بسته بندی و ارسال مورد پردازش قرار خواهد گرفت.";
                    AppConfig::sendSMS($mobile_number,$alert_text);
                    
                    
                    $seller_shop = DB::table("shop_order_items")
                    ->join('srv_centers','srv_centers.id','=','shop_order_items.product_seller_id2')
                    ->where('shop_order_items.order_id',$order->id)
                    //->pluck('srv_centers.center_mobile')
                    ->select('srv_centers.center_mobile')
                    ->get();
                   
                    $seller_shop = $seller_shop->toArray();
                   // var_dump($seller_shop);exit;
                    foreach($seller_shop as $ssp)
                    {
                        //send otp via sms to seller
                        
                        $results['mobile'] = $ssp->center_mobile;
                        $alert_text = "کالایی در سایت epet24 تحت سفارش شماره ".$order->order_code." ثبت شده است که تعدادی از کالاها مربوط به فروشگاه شماست. لطفا چک فرمایید.";
                        AppConfig::sendSMS($ssp->center_mobile,$alert_text);         
                    }
                           
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