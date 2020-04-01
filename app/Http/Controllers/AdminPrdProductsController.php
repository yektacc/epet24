<?php namespace App\Http\Controllers;

	use App\Helper\AppConfig;
    use Carbon\Carbon;
    use function foo\func;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdProductsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "product_title_fa";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "prd_products";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"کد محصول","name"=>"product_code"];
			$this->col[] = ["label"=>"عنوان فارسی محصول","name"=>"product_title_fa"];
			$this->col[] = ["label"=>"عنوان انگلیسی محصول","name"=>"product_title_en"];
			$this->col[] = ["label"=>"برند","name"=>"product_brand","join"=>"prd_brands,brand_name_fa"];
			$this->col[] = ["label"=>"واحد اندازه گیری","name"=>"weight_unit","join"=>"prd_weight_units,title"];
			$this->col[] = ["label"=>"مقدار","name"=>"product_weight"];
//			$this->col[] = ["label"=>"اندازه","name"=>"product_size"];
			$this->col[] = ["label"=>"وضعیت نهایی","name"=>"id","join"=>"product_statuses,status_title"];
			$this->col[] = ["label"=>"وضعیت فعال بودن","name"=>"is_active","join"=>"activation_status,title"];
//			$this->col[] = ["label"=>"رنگ","name"=>"product_main_color"];
			$this->col[] = ["label"=>"درصد از فروش","name"=>"category_fee"];
			$this->col[] = ["label"=>"سطح اول","name"=>"id","join"=>"product_category,basecategory_name"];
			$this->col[] = ["label"=>"سطح دوم","name"=>"id","join"=>"product_category,subcategory_name"];
			$this->col[] = ["label"=>"سطح سوم","name"=>"id","join"=>"product_category,type_name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'عنوان فارسی محصول','name'=>'product_title_fa','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'ترتیب نامگذاری: ماهیت محصول، جنسیت، برند، مدل، ویژگی خاص، سایز یا ظرفیت'];
			$this->form[] = ['label'=>'عنوان انگلیسی محصول','name'=>'product_title_en','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'نکات مثبت','name'=>'positive_points','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'نکات منفی','name'=>'negative_points','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'شرح','name'=>'product_description','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'عناوین دیگر محصول','name'=>'product_titles','type'=>'textarea','width'=>'col-sm-10','placeholder'=>'عناوین با کاما از هم جدا شود'];
			$this->form[] = ['label'=>'وزن','name'=>'product_weight','type'=>'number','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'واحد وزن','name'=>'weight_unit','type'=>'select','width'=>'col-sm-10','datatable'=>'prd_weight_units,title'];
			$this->form[] = ['label'=>'اندازه','name'=>'product_size','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'رنگ','name'=>'product_main_color','type'=>'checkbox','width'=>'col-sm-10','datatable'=>'prd_colors,color_title'];
			$this->form[] = ['label'=>'تصویر محصول','name'=>'product_main_image','type'=>'upload','validation'=>'required|image','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'برند','name'=>'product_brand','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_brands,brand_name_fa'];
			$this->form[] = ['label'=>'دسته','name'=>'product_category','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_base_second_third,sub_category','relationship_table'=>'prd_product_categories'];
			$this->form[] = ['label'=>'درصد از فروش','name'=>'category_fee','type'=>'number','validation'=>'numeric','width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'عنوان فارسی محصول','name'=>'product_title_fa','type'=>'text','validation'=>'required','width'=>'col-sm-10','placeholder'=>'ترتیب نامگذاری: ماهیت محصول، جنسیت، برند، مدل، ویژگی خاص، سایز یا ظرفیت'];
			//$this->form[] = ['label'=>'عنوان انگلیسی محصول','name'=>'product_title_en','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'نکات مثبت','name'=>'positive_points','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'نکات منفی','name'=>'negative_points','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'شرح','name'=>'product_description','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'عناوین دیگر محصول','name'=>'product_titles','type'=>'textarea','width'=>'col-sm-10','placeholder'=>'عناوین با کاما از هم جدا شود'];
			//$this->form[] = ['label'=>'وزن','name'=>'product_weight','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'واحد وزن','name'=>'weight_unit','type'=>'select','width'=>'col-sm-10','datatable'=>'prd_weight_units,title'];
			//$this->form[] = ['label'=>'اندازه','name'=>'product_size','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'رنگ','name'=>'product_main_color','type'=>'checkbox','width'=>'col-sm-10','datatable'=>'prd_colors,color_title'];
			//$this->form[] = ['label'=>'تصویر محصول','name'=>'product_main_image','type'=>'upload','validation'=>'required|image','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'برند','name'=>'product_brand','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_brands,brand_name_fa'];
			//$this->form[] = ['label'=>'دسته','name'=>'product_category','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_base_second_third,sub_category','relationship_table'=>'prd_product_categories'];
			//$this->form[] = ['label'=>'درصد از فروش','name'=>'category_fee','type'=>'number','validation'=>'required','width'=>'col-sm-9'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array(
                ["label"=>"","icon"=>"fa fa-check","color"=>"warning","url"=>CRUDBooster::mainpath('enable-product/[id]'),"showIf"=>"[is_active] == 0"],
//                ["label"=>"بررسی","icon"=>"fa fa-edit","color"=>"warning","url"=>CRUDBooster::mainpath('set-message/[id]'),"showIf"=>"[is_active] == 0"],
                ["label"=>"","icon"=>"fa fa-ban","color"=>"danger","url"=>CRUDBooster::mainpath('set-message/[id]'),"showIf"=>"[is_active] == 0"],
            );


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
//            $query->where('prd_products.is_active','1');
        }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

            DB::transaction(function() use (&$postdata){
                $product_code = AppConfig::generate_product_code();
                $postdata['product_code'] = $product_code;
                $colors = Request::post('product_main_color');
                $categories = Request::post('product_category');
		$cat_base_sec_third_fee = DB::table('prd_third_categories')
					->where('id',$categories[0])
					->pluck('category_fee')
					->first();
//		$color = explode(";",$colors);
//		var_dump($explode_colors);
//		$explode_colors = explode(';',$colors);
//		$var_dump = ($product_code.'::'.var_dump($colors).'::'.var_dump($categories).'::'.var_dump($cat_base_sec_third_fee));
//		$var_dump = ($colors[0].'::'.$colors[1].'::'.$colors[2]);
//		$var_dump = ($categories[0].'::'.$cat_base_sec_third_fee);
		$product_id = DB::table('prd_products')->insertGetId([
                    'id' => NULL,
                    'product_code' => $postdata['product_code'],
                    'product_title_fa' => $postdata['product_title_fa'],
                    'product_title_en' => $postdata['product_title_en'],
                    'positive_points' => $postdata['positive_points'],
                    'negative_points' => $postdata['negative_points'],
                    'product_description' => $postdata['product_description'],
                    'product_titles' => $postdata['product_titles'],
                    'product_weight' => $postdata['product_weight'],
                    'weight_unit' => $postdata['weight_unit'],
                    'product_length' => $postdata['product_length'],
                    'product_width' => $postdata['product_width'],
                    'product_height' => $postdata['product_height'],
                    'product_size' => $postdata['product_size'],
                    'product_main_image' => $postdata['product_main_image'],
//                  'product_main_color' => is_null($colors)?NULL:$colors[0].','.$colors[1].','.$colors[2],
                    'product_main_color' => $postdata['product_main_color'],
                    'product_score' => 3,
                    'product_brand' => $postdata['product_brand'],
                    'category_fee' => $cat_base_sec_third_fee,
                    'is_active' => 0,
                    'var_dump' => $var_dump
                    ],'id');

		$prd_product_img = DB::table('prd_product_pictures')->insertGetId([
			'id' => NULL,
			'product_id' => $product_id,
			'product_picture' => $postdata['product_main_image']
			],'id');

                foreach ($categories as $category){
                    DB::table('prd_product_categories')
                        ->insert([
                            'id' => NULL,
                            'prd_products_id' => $product_id,
                            'prd_base_second_third_id' => $category
                                 ]);
                        }

                $add_variant = function ($colors, $product_id, $cat_base_sec_third_fee){
                $varaint_code = AppConfig::generate_variant_code();
                    DB::table('prd_product_variants')->insert([
                        'id' => NULL,
                        'variant_code' => $varaint_code,
                        'prd_products_id' => $product_id,
                        'prd_colors_id' => $colors[$i],
                        'properties' => NULL,
                        'description' => '',
                        'category_fee' => $cat_base_sec_third_fee,
                        'created_at' => Carbon::now(),
                        'updated_at' => NULL,
                        'deleted_at' => NULL,
                    ]);
                };

                if(is_null($colors)){
                    $add_variant(NULL, $product_id, $cat_base_sec_third_fee);
                                    }
                else {
                    for ($i = 0; $i < count($colors); $i++)
                        $add_variant($colors[$i], $product_id, $cat_base_sec_third_fee);
                     }

                });
            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");
	    }
	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 

	    */

//todo-mohajeri 98-12-08

	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
/*
            DB::transaction(function() use (&$postdata,&$id){
                $categories = Request::post('product_category');

                DB::table('prd_products')->where('id',$id)->update([
                    'product_title_fa' => $postdata['product_title_fa'],
                    'product_title_en' => $postdata['product_title_en'],
                    'positive_points' => $postdata['positive_points'],
                    'negative_points' => $postdata['negative_points'],
                    'product_description' => $postdata['product_description'],
                    'product_titles' => $postdata['product_titles'],
                    'product_weight' => $postdata['product_weight'],
                    'weight_unit' => $postdata['weight_unit'],
                    'product_length' => $postdata['product_length'],
                    'product_width' => $postdata['product_width'],
                    'product_height' => $postdata['product_height'],
                    'product_size' => $postdata['product_size'],
                    'product_main_image' => $postdata['product_main_image'],
                    'product_main_color' => $postdata['product_main_color'],
                    'product_brand' => $postdata['product_brand'],
                ]);

                //todo: following code must be completed for updating

                foreach ($categories as $category){
                    DB::table('prd_product_categories')
                        ->update([
                            'id' => NULL,
                            'prd_products_id' => $product_id,
                            'prd_base_second_third_id' => $category
                        ]);
                }

                $add_variant = function ($colors,$product_id){
//                    $varaint_code = AppConfig::generate_variant_code();
                    DB::table('prd_product_variants')->update([
                        'id' => NULL,
                        'variant_code' => $varaint_code,
                        'prd_products_id' => $product_id,
                        'prd_colors_id' => $color,
                        'properties' => NULL,
                        'description' => '',
                        'category_fee' => $prd_products->category_fee,
                        'created_at' => Carbon::now(),
                        'updated_at' => NULL,
                        'deleted_at' => NULL,
                    ]);
                };

                if(is_null($colors)){
                    $add_variant(NULL,$product_id);
                }
                else {
                    for ($i = 0; $i < count($colors); $i++)
                        $add_variant($colors[$i], $product_id);

                }
            });

            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");
*/
	    }
// todo-mohajeri

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
			//Your code here 
/*
			DB::table('prd_products')
			->where('id' , $id)
			->update([
				'category_fee' => $postdata['category_fee']
			]);             
*/
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :)


        public function getEnableProduct($id){
            DB::statement("call enable_product(?)",[$id]);
            CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"وضعیت محصول با موفقیت بروزرسانی شد!","success");
        }

        public function getSetMessage($id){
            if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

            $data = [];
            $data['id'] = $id;
            $this->cbView("incomplete_view", $data);
        }

        public function getSetIncomplete($id){
            $message = $_GET['message'];
            DB::table('prd_product_statuses')->where('product_id',$id)->update(['status_id'=>3,'message'=>$message,'updated_at'=>Carbon::now()]);
            CRUDBooster::redirect(CRUDBooster::mainPath(),"وضعیت محصول با موفقیت بروزرسانی شد!","success");
        }


	}