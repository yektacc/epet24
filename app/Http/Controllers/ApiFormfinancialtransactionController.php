<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiFormfinancialtransactionController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_payments";        
				$this->permalink   = "formfinancialtransaction";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

				if(isset($postdata['payment_id']))
				{
					$payment_order = DB::table("prd_order_payments") 
									->where('payment_id',$postdata['payment_id']) 
									->get();

					//var_dump($payment_order[0]->order_id); exit;
					$payment_trans_rows = DB::table("prd_order_item")   
						->join("prd_orders",'prd_orders.id','=','prd_order_item.order_id')
						->join("prd_sale_item",'prd_sale_item.id','=','prd_order_item.item_id')
						//->join("prd_order_tariff",'prd_order_tariff.order_id','=','prd_order_item.order_id')
					//	->join("prd_product_variants",'prd_product_variants.id','=','prd_sale_item.variant_id')
						//->select('prd_product_variants.category_fee')
						->where('prd_orders.id',$payment_order[0]->order_id)
						->orderby('prd_sale_item.seller_id', 'ASC')
					->get();
					var_dump($payment_trans_rows); exit;
					
					$payment_trans_rows = $payment_trans_rows->toArray();
					$admin_income = 0;
					$seller_income = 0;
					
					$jsontext = array();
					$jsontext2 = array();
					$jsontext_final = array();
					$admin_json = array();

					$cur_seller = $payment_trans_rows[0]->seller_id;
					//echo "payment_trans_rows->seller_id: ".$payment_trans_rows[0]->seller_id;
					foreach($payment_trans_rows as $row)
					{
						
						$cat_fee = DB::table("prd_product_variants")
							->where('id','=',$row->variant_id)
							->pluck('category_fee')
							->first();

						$tariff_coupon = DB::table("prd_order_seller_transaction")
							->where('seller_id','=',$row->seller_id)
							->where('order_id','=',$payment_order[0]->order_id)
							->select('tariff_amount','coupon_code_id')
							->get();							
					//	echo " seller_id: ".$row->seller_id." order_id: ".$payment_order[0]->order_id;
						$tariff_amount 	= $tariff_coupon[0]->tariff_amount;
						$coupon_code_id = $tariff_coupon[0]->coupon_code_id;
						$coupon_amount = DB::table("prd_coupons")
							->join("prd_coupons_codes",'prd_coupons_codes.prd_coupon_id','=','prd_coupons.id')
							->where('prd_coupons_codes.id','=',$coupon_code_id)
							->pluck('discount_price')
							->first();
											

						if($row->seller_id == $cur_seller)
						{
							//echo "cat_fee: ". $cat_fee. " seller_id: ".$row->seller_id. " cur_seller: ". $cur_seller;
							$seller_income += (((($row->unit_price * $row->quantity) * (100 - $cat_fee))/100) * 1.09);
							$seller_coupon_amount = $coupon_amount;

							// make the coupn is_used
							DB::table('prd_coupons_codes')
							->where('id',$coupon_code_id)
							->update([ //save incomes
								'is_used' => 1
							]);	
						}
						else
						{
							$seller_income += $tariff_amount;
							$seller_income -= $seller_coupon_amount;

							$seller_json = array("seller_id"=>$cur_seller, "seller_income"=>$seller_income);
							array_push($jsontext,$seller_json);

							$admin_income += (((($row->unit_price * $row->quantity) * $cat_fee)/100) * 1.09);

							DB::table('prd_order_seller_transaction')
								->where('seller_id',$cur_seller)
								->where('order_id',$payment_order[0]->order_id)
								->update([ //save incomes
									'seller_income' => $seller_income
								]);	

							DB::table('prd_order_admin_transaction')
							->where('order_id',$payment_order[0]->order_id)
							->update([ //save incomes
								'admin_income' => $seller_income
							]);	

							$seller_income = 0;
							$admin_income = 0;
							$cur_seller = $row->seller_id;
							$seller_income += (((($row->unit_price * $row->quantity) * (100 - $cat_fee))/100) * 1.09);
							$admin_income += (((($row->unit_price * $row->quantity) * $cat_fee)/100) * 1.09);
						}
						
					/*	
						
						echo " unit_price: ". $row->unit_price;
						echo " quantity: ".$row->quantity;
						echo " tariff: ". $row->tariff_amount;
						echo "fee: ". $row->category_fee;
						*/

						

					}
					$seller_income += $tariff_amount;
					$seller_income -= $coupon_amount;
					$seller_json = array("seller_id"=>$cur_seller, "seller_income"=>$seller_income);
					array_push($jsontext,$seller_json);						
						

					$seller_json_final['sellers'] = $jsontext;

					array_push($jsontext_final, $seller_json_final);
					
					
					
					$admin_json['store'] = array("admin_id"=>1, "admin_income"=>$admin_income);

					array_push($jsontext_final, $admin_json);

					
					DB::table('prd_order_seller_transaction')
					->where('seller_id',$cur_seller)
					->where('order_id',$payment_order[0]->order_id)
					->update([ //save incomes
						'seller_income' => $seller_income
					]);	

					DB::table('prd_order_admin_transaction')
					//->where('order_id',$payment_order[0]->order_id)
					->insert([ //save incomes
						'admin_income' => $admin_income,
						'order_id'=>$payment_order[0]->order_id
					]);						

					//var_dump(json_encode($jsontext)); exit;
					DB::table("prd_order_payments")
					->where('id',$payment_order[0]->id)
					->update([
						"income" => json_encode($jsontext_final)
					]);

					$rows = DB::table("prd_order_payments")
					->where('id',$payment_order[0]->id)
					->get();
					
					$income_rows = $rows[0]->income;//->toArray();
					
					
					//$income_rows = $rows[0]->income;
					$income_rows = json_decode($income_rows);	
					//var_dump($income_rows[0]->sellers);
					$seller_total = 0;
					//var_dump($income_rows[0]->sellers[0]->seller_id);
					$count = 0;
					foreach($income_rows as $income_row)
					{
					//	$income_row     = get_object_vars($income_row);
						
						$seller_count = count($income_rows[0]->sellers);
						for($i=0; $i<$seller_count; $i++)
						{
							var_dump($income_row->sellers[$i]->seller_id);
						}
											
						$count++;	
					}
					//var_dump($seller_total);
					exit;
				}
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}