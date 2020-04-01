<?php namespace App\Http\Controllers;

        use Session;
        use Request;
        use DB;
        use CRUDBooster;

class ApiGetproductdetailsController extends \crocodicstudio\crudbooster\controllers\ApiController
{

    function __construct()
    {    
        $this->table       = "prd_products";        
        $this->permalink   = "getproductdetails";    
        $this->method_type = "post";    
    }
        

    public function hook_before(&$postdata)
    {
        //This method will be execute before run the main process
        $where_clause = "1";
        $where_parameters = [];
        try{
            if(isset($postdata['prd_sale_item_id'])){
                $where_clause .= " AND (prd_sale_item_id = ?)";
                $where_parameters[] = $postdata['prd_sale_item_id'];
            }
            $rows = DB::table('product_details')
                    ->whereRaw($where_clause, $where_parameters)
                    ->where('id', '=', $postdata['product_id'])
                    ->get();
            $query_result = [];
            $rows = $rows->toArray();
//		var_dump($rows); exit;
            foreach ($rows as $row){
                $seller_score = DB::table('srv_center_score')
				->where('srv_center_id', '=', $row->seller_id)
				->get();

                $seller_exist = DB::table('srv_centers')
                ->where('id', '=', $row->seller_id)
                ->where('deleted_at', '=', NULL)
                ->get();
                $seller_exist = $seller_exist->toArray();
                if($seller_exist[0]->id >0)
                {
                   // var_dump($seller_exist); exit;
                }
                $seller_score_rows = $seller_score->toArray();
                $srv_score_sum = 0;
                foreach($seller_score_rows as $seller_score_row)
                {
                
                    $srv_score_sum += intval($seller_score_row->score);
                }
               
               //var_dump($srv_score_sum); exit;
               if(count($seller_score_rows) > 0)
                    $seller_score_avg = $srv_score_sum / count($seller_score_rows);
                else
                    $seller_score_avg = 0;                

                $prd_score = DB::table('prd_sale_item_score')->where('prd_sale_item_id', '=', $row->prd_sale_item_id)->get();
                $prd_score_rows = $prd_score->toArray();
                $prd_score_sum = 0;
                foreach($prd_score_rows as $prd_score_row)
                {
                
                    $prd_score_sum += intval($prd_score_row->score);
                }
               
               //var_dump($srv_score_sum); exit;
               if(count($prd_score_rows) > 0)
                    $prd_score_avg = $prd_score_sum / count($prd_score_rows);
                else
                    $prd_score_avg = 0;  

               
                if (count($query_result) == 0 && $seller_exist[0]->id >0 && $row->deleted_at == NULL){
                    $query_result[] = [
                        "product_id" => $row->id,
                        "product_code" => $row->code,
                        "product_title_fa" => $row->title_fa,
                        "product_title_en" => $row->title_en,
                        "product_positive_points" => $row->posititve_points,
                        "product_nagative_points" => $row->negative_points,
                        "product_weight" => $row->weight,
                        "product_unit" => $row->unit,
                        "product_size" => $row->size,
                        "product_score" => $row->score,
                        "product_description" => $row->description,
                        "product_titles" => $row->product_titles,
                        "product_brand" => $row->brand_name_fa,
                        "variants" => [
                            [
                                "variant_id" => $row->variant_id, //todo: variant special properties must be added
                                "product_color" => $row->color,
                                "shops" => [
                                    [
                                        "product_seller_id" => $row->seller_id,
                                        "product_seller_score" => $seller_score_avg,
                                        "product_score" => $prd_score_avg,
                                        "product_seller_shop_id" => $row->seller_shop_id,
                                        "product_sale_item_id" => $row->sale_item_id,
                                        "product_shop_name" => $row->center_name,
                                        "product_shop_city" => $row->shop_city,
                                        "product_shop_city_id" => $row->shop_city_id,
                                        "product_shipment_time" => $row->shipment_time,
                                        "product_main_price" => $row->main_price,
                                        "product_sale_price" => $row->sale_price,
                                        "product_sale_discount" => $row->sale_discount,
                                        "product_guarantee" => $row->guarantee,
                                        "product_maximum_orderable" => $row->maxmimum_orderable,
                                        "product_is_exist" => $row->is_exist,
                                        "sale_count" => $row->sale_count,
                                        "stock_quantity" => $row->stock_quantity,
                                        "visit_count" => $row->visit_count,
                                    ]
                                ]
                            ]
                        ]
                    ];
                    continue;
                }

                for ($i = 0 ; $i < count($query_result) ; $i++){

                    if($row->id == $query_result[$i]["product_id"] && $seller_exist[0]->id >0 && $row->deleted_at == NULL){

                        for($j = 0 ; $j < count($query_result[$i]["variants"]) ; $j++) {
                            if ($row->variant_id == $query_result[$i]["variants"][$j]['variant_id']) {
                                $query_result[$i]["variants"][$j]["shops"][] = [
                                    "product_seller_id" => $row->seller_id,
                                    "product_seller_score" => $seller_score_avg,
                                    "product_score" => $prd_score_avg,
                                    "product_seller_shop_id" => $row->seller_shop_id,
                                    "product_sale_item_id" => $row->sale_item_id,
                                    "product_shop_name" => $row->center_name,
                                    "product_shop_city" => $row->shop_city,
                                    "product_shop_city_id" => $row->shop_city_id,
                                    "product_shipment_time" => $row->shipment_time,
                                    "product_main_price" => $row->main_price,
                                    "product_sale_price" => $row->sale_price,
                                    "product_sale_discount" => $row->sale_discount,
                                    "product_guarantee" => $row->guarantee,
                                    "product_maximum_orderable" => $row->maxmimum_orderable,
                                    "product_is_exist" => $row->is_exist,
                                    "sale_count" => $row->sale_count,
                                    "stock_quantity" => $row->stock_quantity,
                                    "visit_count" => $row->visit_count,
                                ];
                                break;
                            }
                        }

                        if ($j == count($query_result[$i]["variants"]) && $seller_exist[0]->id >0 && $row->deleted_at == NULL) {
                            $query_result[$i]["variants"][] = [
                                "variant_id" => $row->variant_id,
                                "product_color" => $row->color,
                                "shops" => [
                                    [
                                        "product_seller_id" => $row->seller_id,
                                        "product_seller_score" => $seller_score_avg,
                                        "product_score" => $prd_score_avg,
                                        "product_seller_shop_id" => $row->seller_shop_id,
                                        "product_sale_item_id" => $row->sale_item_id,
                                        "product_shop_name" => $row->center_name,
                                        "product_shop_city" => $row->shop_city,
                                        "product_shop_city_id" => $row->shop_city_id,
                                        "product_shipment_time" => $row->shipment_time,
                                        "product_main_price" => $row->main_price,
                                        "product_sale_price" => $row->sale_price,
                                        "product_sale_discount" => $row->sale_discount,
                                        "product_guarantee" => $row->guarantee,
                                        "product_maximum_orderable" => $row->maxmimum_orderable,
                                        "product_is_exist" => $row->is_exist,
                                    ]
                                ]
                            ];
                        }
                        break;
                    }
                }

                if ($i == count($query_result) && $seller_exist[0]->id >0 && $row->deleted_at == NULL){
                    $query_result[]=[
                        "product_id" => $row->id,
                        "product_code" => $row->code,
                        "product_title_fa" => $row->title_fa,
                        "product_title_en" => $row->title_en,
                        "product_positive_points" => $row->posititve_points,
                        "product_nagative_points" => $row->negative_points,
                        "product_weight" => $row->weight,
                        "product_unit" => $row->unit,
                        "product_size" => $row->size,
                        "product_score" => $row->score,
                        "product_description" => $row->description,
                        "product_titles" => $row->product_titles,
                        "variants" => [
                            [
                                "variant_id" => $row->variant_id,
                                "product_color" => $row->color,
                                "shops"=> [
                                    [
                                        "product_seller_id" => $row->seller_id,
                                        "product_seller_score" => $seller_score_avg,
                                        "product_score" => $prd_score_avg,
                                        "product_seller_shop_id" => $row->seller_shop_id,
                                        "product_sale_item_id" => $row->sale_item_id,
                                        "product_shop_name" => $row->center_name,
                                        "product_shop_city" => $row->shop_city,
                                        "product_shop_city_id" => $row->shop_city_id,
                                        "product_shipment_time" => $row->shipment_time,
                                        "product_main_price" => $row->main_price,
                                        "product_sale_price" => $row->sale_price,
                                        "product_sale_discount" => $row->sale_discount,
                                        "product_guarantee" => $row->guarantee,
                                        "product_maximum_orderable" => $row->maxmimum_orderable,
                                        "product_is_exist" => $row->is_exist,
                                        "sale_count" => $row->sale_count,
                                        "stock_quantity" => $row->stock_quantity,
                                        "visit_count" => $row->visit_count,
                                        
                                    ]
                                ]
                            ]

                        ]
                    ];
                }
            }

            $category = DB::table('product_category')->where('id', '=', $postdata['product_id'])->get();

            $results['api_status'] = 1;
            $results['api_message'] = 'product detail';
            $results['data'] = [
                "detail" => $query_result,
                "category" => $category
            ];
        }
        catch (\Exception $exception){
            $results['api_status'] = 0;
            $results['api_message'] = 'server error'.$exception;
        }

        echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        die;
    }

    public function hook_query(&$query)
    {
        //This method is to customize the sql query

    }

    public function hook_after($postdata,&$result)
    {
        //This method will be execute after run the main process

    }

}

