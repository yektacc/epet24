<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdSellerShopController extends \crocodicstudio\crudbooster\controllers\CBController
    {

        public function cbInit()
        {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
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
			$this->table = "prd_seller";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"کد فروشگاه","name"=>"seller_code"];
			$this->col[] = ["label"=>"Seller Type","name"=>"seller_type","join"=>"prd_company_type,title"];
			$this->col[] = ["label"=>"Shop Name_fa","name"=>"shop_name_fa"];
			$this->col[] = ["label"=>"Personal Id","name"=>"personal_id","join"=>"prd_seller_personal,firstname"];
			$this->col[] = ["label"=>"Company Id","name"=>"company_id","join"=>"prd_seller_company,company_name_fa"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'نوع فروشنده','name'=>'seller_type','type'=>'datamodal','validation'=>'required','width'=>'col-sm-6','datamodal_table'=>'prd_seller_type','datamodal_columns'=>'title'];
			$this->form[] = ['label'=>'حقیقی','name'=>'personal_id','type'=>'select2','width'=>'col-sm-9','datatable'=>'prd_seller_personal,lastname,'];
			$this->form[] = ['label'=>'حقوقی','name'=>'company_id','type'=>'select2','width'=>'col-sm-9','datatable'=>'prd_seller_company,company_name_fa'];
			$this->form[] = ['label'=>'Shop Name_fa','name'=>'shop_name_fa','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Shop Name_en','name'=>'shop_name_en','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Seller Shaba','name'=>'seller_shaba','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'نوع فروشنده','name'=>'seller_type','type'=>'datamodal','validation'=>'required','width'=>'col-sm-6','datamodal_table'=>'prd_seller_type','datamodal_columns'=>'title'];
			//$this->form[] = ['label'=>'حقیقی','name'=>'personal_id','type'=>'select2','width'=>'col-sm-9','datatable'=>'prd_seller_personal,lastname,'];
			//$this->form[] = ['label'=>'حقوقی','name'=>'company_id','type'=>'select2','width'=>'col-sm-9','datatable'=>'prd_seller_company,company_name_fa'];
			//$this->form[] = ['label'=>'Shop Name_fa','name'=>'shop_name_fa','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Shop Name_en','name'=>'shop_name_en','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'Seller Shaba','name'=>'seller_shaba','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Address Id','name'=>'address_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'addresses,remained'];
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
            $this->addaction = array();


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
            $this->alert = array();


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
        public function actionButtonSelected($id_selected, $button_name)
        {
            //Your code here

        }


        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate query of index result
        | ----------------------------------------------------------------------
        | @query = current sql query
        |
        */
        public function hook_query_index(&$query)
        {
            //Your code here

        }

        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate row of index table html
        | ----------------------------------------------------------------------
        |
        */
        public function hook_row_index($column_index, &$column_value)
        {
            //Your code here
        }

        /*
        | ----------------------------------------------------------------------
        | Hook for manipulate data input before add data is execute
        | ----------------------------------------------------------------------
        | @arr
        |
        */
        public function hook_before_add(&$postdata)
        {
            //Your code here

            DB::transaction(function () use (&$postdata) {
                $address_id = DB::table('addresses')->insertGetId([
                    'id' => NULL,
                    'city_id' => $postdata['city_id'],
                    'district_id' => $postdata['district_id'],
                    'remained' => $postdata['remained'],
                    'postal_code' => $postdata['postal_code']
                ]);

                $shop_id = DB::table('prd_shop')->insertGetId([
                    'id' => NULL,
                    'shop_name_fa' => $postdata['shop_name_fa'],
                    'shop_name_en' => $postdata['shop_name_en'],
                    'seller_shaba' => $postdata['seller_shaba'],
                    'shop_address' => $address_id,
                    'has_stock' => $postdata['has_stock'],
                    'has_vta' => $postdata['has_vta'],
                    'shop_phone' => $postdata['shop_phone']
                ], 'id');

                DB::table('prd_seller_shop')->insert([
                    'id' => NULL,
                    'seller_id' => $postdata['seller_id'],
                    'shop_id' => $shop_id
                ]);
            });

            CRUDBooster::redirect(CRUDBooster::mainPath(), NULL, "success");

        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after add public static function called
        | ----------------------------------------------------------------------
        | @id = last insert id
        |
        */
        public function hook_after_add($id)
        {
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
        public function hook_before_edit(&$postdata, $id)
        {
            //Your code here

            DB::transaction(function () use (&$postdata, $id) {
//                $address_id = DB::table('addresses')->where()->update([
//                    'id'=>NULL,
//                    'city_id'=>$postdata['city_id'],
//                    'district_id'=>$postdata['district_id'],
//                    'remained'=>$postdata['remained'],
//                    'postal_code'=>$postdata['postal_code']
//                ]);
//
//                DB::table('prd_shop')->where()->update([
//                    'shop_name_fa'=>$postdata['shop_name_fa'],
//                    'shop_name_en'=>$postdata['shop_name_en'],
//                    'seller_shaba'=>$postdata['seller_shaba'],
//                    'shop_address'=>$address_id,
//                    'has_stock'=>$postdata['has_stock'],
//                    'has_vta'=>$postdata['has_vta'],
//                    'shop_phone'=>$postdata['shop_phone']
//                ]);

                DB::table('prd_seller_shop')->where('id', $id)->update([
                    'seller_id' => $postdata['seller_id'],
                ]);
            });

            CRUDBooster::redirect(CRUDBooster::mainPath(), NULL, "success");

        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after edit public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_after_edit($id)
        {
            //Your code here

        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command before delete public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_before_delete($id)
        {
            //Your code here

        }

        /*
        | ----------------------------------------------------------------------
        | Hook for execute command after delete public static function called
        | ----------------------------------------------------------------------
        | @id       = current id
        |
        */
        public function hook_after_delete($id)
        {
            //Your code here

        }


        //By the way, you can still create your own method in here... :)


        public function getDetail($id)
        {
            if (!CRUDBooster::isRead() && $this->global_privilege == FALSE || $this->button_edit == FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
            }

            $data = [];
            $data['page_title'] = 'جزئیات اطلاعات فروشگاه ها';

            $row = DB::table('prd_seller_shop')
                ->where('id', $id)
                ->first();

            $seller = DB::table('seller_details')
                ->where('id', $row->seller_id)
                ->first();

            $shop = DB::table('prd_shop')
                ->where('id', $row->shop_id)
                ->first();

            $address = DB::table('addresses_details')
                ->where('id',$shop->shop_address)
                ->first();

            $stock = DB::table('prd_existence_status')
                ->where('id',$shop->has_stock)
                ->first();

            $vta = DB::table('prd_existence_status')
                ->where('id',$shop->has_vta)
                ->first();


            $fields['فروشنده'] = $seller->firstname." ".$seller->lastname;
            $fields['نوع فروشنده'] = $seller->seller_type;
            $fields['شماره شبا'] = $shop->seller_shaba;
            $fields['نام فارسی فروشگاه'] = $shop->shop_name_fa;
            $fields['نام انگلیسی فروشگاه'] = $shop->shop_name_en;;
            $fields['استان محل فروشگاه'] = $address->province_name;
            $fields['شهر محل فروشگاه'] = $address->city_name;
            $fields['منطقه محل فروشگاه'] = $address->district_name;
            $fields['آدرس'] = $address->remained;
            $fields['تلفن'] = $shop->shop_phone;
            $fields['کد پستی'] = $address->postal_code;
            $fields['انبار'] = $stock->title;
            $fields['گواهی ارزش افزوده'] = $vta->title;
            $data['fields'] = $fields;
            $this->cbView('custom_detail_view', $data);
            
        }
    }