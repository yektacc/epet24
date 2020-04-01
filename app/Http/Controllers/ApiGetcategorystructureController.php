<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetcategorystructureController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_second_third_categories";        
				$this->permalink   = "getcategorystructure";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                DB::table('api_call_stat')
                                ->where('api_name', '=', 'getcategorystructure')
                                ->update(['count'=> DB::raw('count + 1')]);
                    $rows = DB::table('products_category_structure')->get();
                    $query_result = [];
//			var_dump($rows);
                    $rows = $rows->toArray();
//			var_dump($rows);
                    foreach ($rows as $row){

                        if (count($query_result) == 0){
                            $query_result[] = [
                                "basecategory_id" => $row->basecategory_id,
                                "basecategory_name_fa" => $row->basecategory_name_fa,
                                "basecategory_name_en" => $row->basecategory_name_en,
                                "subcategories" => [
                                    [
                                        "subcategory_id" => $row->subcategory_id,
                                        "subcategory_name_fa" => $row->subcategory_name_fa,
                                        "subcategory_name_en" => $row->subcategory_name_en,
                                        "product_types" => [
                                            [
                                                "type_id" => $row->type_id,
                                                "type_name_fa" => $row->type_name_fa,
                                                "type_name_en" => $row->type_name_en
                                            ]
                                        ]
                                    ]
                                ]
                            ];
                            continue;
                        }

                        for ($i = 0 ; $i < count($query_result) ; $i++){

                            if($row->basecategory_id == $query_result[$i]["basecategory_id"]){

                                for($j = 0 ; $j < count($query_result[$i]["subcategories"]) ; $j++) {
                                    if ($row->subcategory_id == $query_result[$i]["subcategories"][$j]['subcategory_id']) {
                                        $query_result[$i]["subcategories"][$j]["product_types"][] = [
                                            "type_id" => $row->type_id,
                                            "type_name_fa" => $row->type_name_fa,
                                            "type_name_en" => $row->type_name_en,
                                        ];
                                        break;
                                    }
                                }

                                if ($j == count($query_result[$i]["subcategories"])) {
                                    $query_result[$i]["subcategories"][] = [
                                        "subcategory_id" => $row->subcategory_id,
                                        "subcategory_name_fa" => $row->subcategory_name_fa,
                                        "subcategory_name_en" => $row->subcategory_name_en,
                                        "product_types" => [
                                            [
                                                "type_id" => $row->type_id,
                                                "type_name_fa" => $row->type_name_fa,
                                                "type_name_en" => $row->type_name_en
                                            ]
                                        ]
                                    ];
                                }
                                break;
                            }
                        }

                        if ($i == count($query_result)){
                            $query_result[]=[
                                "basecategory_id" => $row->basecategory_id,
                                "basecategory_name_fa" => $row->basecategory_name_fa,
                                "basecategory_name_en" => $row->basecategory_name_en,
                                "subcategories" => [
                                    [
                                        "subcategory_id" => $row->subcategory_id,
                                        "subcategory_name_fa" => $row->subcategory_name_fa,
                                        "subcategory_name_en" => $row->subcategory_name_en,
                                        "product_types" => [
                                            [
                                                "type_id" => $row->type_id,
                                                "type_name_fa" => $row->type_name_fa,
                                                "type_name_en" => $row->type_name_en,
                                            ]
                                        ]
                                    ]
                                ]
                            ];
                        }
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'category structure';
//                $results['data'] = $query_result->toArray();
                    $results['data'] = $query_result;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
                }

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
