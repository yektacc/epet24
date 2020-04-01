<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetproductsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_products";        
				$this->permalink   = "getproducts";    
				$this->method_type = "post";    
		    }
		
            private function get_min_of_variants($q,$p_id, $min_base)
            {
                $rows =  $q->toArray();
                $query_result2 = [];
               
                
                
                foreach($rows as $row)
                {
                    if($row->product_id == $p_id)
                    {
                        
                        if($row->sale_price <= $min_base)
                        {
                            $min = $row->sale_price;
                            $prd_sale_item_id = $row->prd_sale_item_id;
                        }
                    }
                }
                //var_dump($min); exit;
                //var_dump($min)
                return $prd_sale_item_id;
            }
		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				try{
                    $where_clause = "1";
                    $where_parameters = [];

                    if(isset($postdata['maincategory_id'])){
                        $where_clause .= " AND (basecategory_id = ?)";
                        $where_parameters[] = $postdata['maincategory_id'];
                    }


                    if (isset($postdata['subcategory_id'])) {
                        $where_clause .= " AND (subcategory_id = ?)";
                        $where_parameters[] = $postdata['subcategory_id'];
                    }
                    
                    if (isset($postdata['type_id'])) {
                        $where_clause .= " AND (product_type_id = ?)";
                        $where_parameters[] = $postdata['type_id'];
                    }

                    if (isset($postdata['product_id'])) {
                        $where_clause .= " AND (product_id = ?)";
                        $where_parameters[] = $postdata['product_id'];
                    }
                    if(isset($postdata['product_title'])){
                        $where_clause = " (product_title_fa like ?)";
                        $where_parameters[] = '%'.$postdata['product_title'].'%';
                    }

                    $query_result = DB::table('products')
                        ->whereRaw($where_clause, $where_parameters);
//                        ->min('sale_price');


                //    if(isset($postdata['per_page'])){
                //        $query_result = $query_result->paginate($postdata['per_page']);
                //    }
                //    else{
                    $query_result = $query_result->get();
                //    } 
//                  var_dump($query_result); exit;
                    $rows =  $query_result->toArray();
                    
                    $last_prd_id = 0;
                    $cur_prd_id = 1;
                    $min_prd_sale_items = array();
//                    var_dump($min_prd_sale_items);
                    foreach($rows as $row)
                    {
                        $cur_prd_id = $row->product_id; 
                        if($cur_prd_id != $last_prd_id)
                        {

                            $a = $this->get_min_of_variants($query_result, $cur_prd_id, $row->sale_price);
// var_dump($a);
                            array_push($min_prd_sale_items, $a);

                            $last_prd_id = $cur_prd_id;
                        }
                    }
                    
                    $query_result_ = DB::table('products')
                        ->whereRaw($where_clause, $where_parameters)
                        ->where("prd_sale_item_deleted_at", NULL)
                        ->whereIn("prd_sale_item_id",$min_prd_sale_items);


                    if(isset($postdata['per_page'])){
                       // $result = $a->unionAll($b)->->paginate(15);
                        $query_result_ = $query_result_->paginate($postdata['per_page']);
                    }
                    else{
                        $query_result_ = $query_result_->get();
                    }  
                   // var_dump($min_prd_sale_items); exit;

//                $query_result = DB::table('products')
//                    ->whereRaw($where_clause, $where_parameters)
//                    ->paginate(PAGE_SIZE);

                    $results['api_status'] = 1;
                    $results['api_message'] = 'products';
                    $results['data'] = $query_result_->toArray();
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
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