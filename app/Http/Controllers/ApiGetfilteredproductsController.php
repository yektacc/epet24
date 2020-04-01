<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetfilteredproductsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_products";        
				$this->permalink   = "getfilteredproducts";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
                //This method will be execute before run the main process
                
                //var_dump($postdata['filter_type']); exit;
                DB::table('api_call_stat')
                                ->where('api_name', '=', 'getfilteredproducts')
                                ->update(['count'=> DB::raw('count + 1')]);
                $where_clause = "1";
                $where_parameters = [];
                //var_dump($postdata['maincategory_id']); exit;
                if (isset($postdata['maincategory_id'])){
                    $where_clause .= " AND (basecategory_id = ?)";
                    $where_parameters[] = $postdata['maincategory_id'];
                }
                if(isset($postdata['product_title'])){
                    $where_clause .= " AND (product_title_fa like ?)";
                    $where_parameters[] = '%'.$postdata['product_title'].'%';
                }

                if (isset($postdata['subcategory_id'])){
                    $where_clause .= " AND (subcategory_id = ?)";
                    $where_parameters[] = $postdata['subcategory_id'];
                }

                if (isset($postdata['type_id'])){
                    $where_clause .= " AND (product_type_id = ?)";
                    $where_parameters[] = $postdata['type_id'];
                }

                if (isset($postdata['brands'])){
                    $where_clause .= " AND (";
                    $brands = explode(',',$postdata['brands']);
                    $count = count($brands);

                    for ($i = 0 ; $i < $count ; $i++){
                        $where_clause .= "brand_id = ? ";
                        $where_parameters[] = $brands[$i];
                        if ($i+1 < $count)
                            $where_clause .= "OR ";
                    }
                    $where_clause .= ")";
                }

                if(isset($postdata['min_price']) && isset($postdata['max_price'])){
                    $where_clause .= " AND (products.main_price > 0) AND (products.main_price BETWEEN ? AND ?)";
                    $where_parameters[] = $postdata['min_price'];
                    $where_parameters[] = $postdata['max_price'];
                }

                if (isset($postdata['is_exist']) && $postdata['is_exist'] == 1){
                    $where_clause .= " AND (products.is_exist > 0)";
                }


                if(isset($postdata['filter_type']))
                {
                    if($postdata['filter_type'] == "best_sale") // best_sale
                    {
                       $where_clause .= " AND (products.seller_center_id = prd_sale_item.seller_id)";
                        $query_result = DB::table('products')
                            ->join("prd_sale_item",'prd_sale_item.variant_id','=','products.variant_id')
                            ->whereRaw($where_clause,$where_parameters)
                            ->where('prd_sale_item.deleted_at', NULL)
                            ->where('prd_sale_item.tag_is_active', 1)
                            ->where('prd_sale_item.is_exist', 1)
                            ->where('prd_sale_item.is_exist', '>=', 0)
                            ->orderby('prd_sale_item.sale_count', 'DESC')
                            ->paginate(PAGE_SIZE);
                    }


                    if($postdata['filter_type'] == "best_visit")
                    {

                        $where_clause .= " AND (products.seller_center_id = prd_sale_item.seller_id)";
                        $query_result = DB::table('products')
                            ->join("prd_sale_item",'prd_sale_item.variant_id','=','products.variant_id')
                            ->whereRaw($where_clause,$where_parameters)
                            ->where('prd_sale_item.deleted_at', NULL)
                            ->where('prd_sale_item.tag_is_active', 1)
                            ->where('prd_sale_item.is_exist', 1)
                            ->where('prd_sale_item.is_exist', '>=', 0)
                            ->orderby('prd_sale_item.visit_count', 'DESC')
                            ->paginate(PAGE_SIZE);                     
                    }
                    if($postdata['filter_type'] == "newest")
                    {
                        $where_clause .= " AND (products.seller_center_id = prd_sale_item.seller_id)";                   
                        $query_result = DB::table('products')
                            ->join("prd_sale_item",'prd_sale_item.variant_id','=','products.variant_id')
                            ->whereRaw($where_clause,$where_parameters)
                            ->where('prd_sale_item.deleted_at', NULL)
                            ->where('prd_sale_item.tag_is_active', 1)
                            ->where('prd_sale_item.is_exist', 1)
                            ->where('prd_sale_item.is_exist', '>=', 0)
                            ->orderby('prd_sale_item.id', 'DESC')
                            ->paginate(PAGE_SIZE);                     
                    }   
                    if($postdata['filter_type'] == "most-expensive")
                    {
                        $where_clause .= " AND (products.seller_center_id = prd_sale_item.seller_id)";                   
                        $query_result = DB::table('products')
                            ->join("prd_sale_item",'prd_sale_item.variant_id','=','products.variant_id')
                            ->whereRaw($where_clause,$where_parameters)
                            ->where('prd_sale_item.deleted_at', NULL)
                            ->where('prd_sale_item.tag_is_active', 1)
                            ->where('prd_sale_item.is_exist', 1)
                            ->where('prd_sale_item.is_exist', '>=', 0)
                            ->orderby('prd_sale_item.sale_price', 'DESC')
                            ->paginate(PAGE_SIZE);                     
                    }   
                    if($postdata['filter_type'] == "cheapest")
                    {
                        $where_clause .= " AND (products.seller_center_id = prd_sale_item.seller_id)";                   
                        $query_result = DB::table('products')
                            ->join("prd_sale_item",'prd_sale_item.variant_id','=','products.variant_id')
                            ->whereRaw($where_clause,$where_parameters)
                            ->where('prd_sale_item.deleted_at', NULL)
                            ->where('prd_sale_item.tag_is_active', 1)
                            ->where('prd_sale_item.is_exist', 1)
                            ->where('prd_sale_item.is_exist', '>=', 0)
                            ->orderby('prd_sale_item.sale_price', 'ASC')
                            ->paginate(PAGE_SIZE);                     
                    }                                                         
                }                
                else
                {
                    $query_result = DB::table('products')
                        ->whereRaw($where_clause,$where_parameters)
                        ->paginate(PAGE_SIZE);
                    
                }

                $results['api_status'] = 1;
                $results['api_message'] = 'products';
                $results['data'] = $query_result->toArray();
                echo json_encode($results,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
			    
		    }

		}