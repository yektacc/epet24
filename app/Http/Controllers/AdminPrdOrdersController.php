<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdOrdersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
//			$this->table = "prd_order_statuses";
			$this->table = "prd_orders";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"کد سفارش","name"=>"order_code"];
			$this->col[] = ["label"=>"تاریخ سفارش","name"=>"order_date"];
			$this->col[] = ["label"=>"شماره تماس مشتری","name"=>"customer_id","join"=>"app_users,mobile_number"];
//			$this->col[] = ["label"=>"مبلغ سفارش","name"=>"total_amount"];
			$this->col[] = ["label"=>"وضعیت سفارش","name"=>"id","join"=>"shop_orders,order_status_title"];
//			$this->col[] = ["label"=>"وضعیت سفارش","name"=>"(select prd_ostatus.title from ((prd_orders join prd_order_statuses on prd_orders.id = prd_order_statuses.order_id) join prd_order_statuses on prd_ostatus prd_order_statuses.status_id = prd_ostatus.id) where prd_order_statues.status_id = prd_orders.id)  as status"];
//			$this->col[] = ["label"=>"نوع حیوان","name"=>"(select aml_types.title from ((aml_animals join aml_types on aml_animals.type_id = aml_types.id) join fc_requests on aml_animals.id = fc_requests.animal_id) where fc_requests.id = request_id) as animal_type"];

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE		
//			$this->form = [];
//			$this->form[] = ['label'=>'Order Code','name'=>'order_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Customer Id','name'=>'customer_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'customer,id'];
//			$this->form[] = ['label'=>'Order Date','name'=>'order_date','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Shipment Time','name'=>'shipment_time','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Shipment Cost','name'=>'shipment_cost','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Coupon Value','name'=>'coupon_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Transferee','name'=>'transferee','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Delivery Date','name'=>'delivery_date','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Total Amount','name'=>'total_amount','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'Is Completed','name'=>'is_completed','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-10','dataenum'=>'Array'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Order Code","name"=>"order_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Customer Id","name"=>"customer_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"customer,id"];
			//$this->form[] = ["label"=>"Order Date","name"=>"order_date","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Shipment Time","name"=>"shipment_time","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Shipment Cost","name"=>"shipment_cost","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Coupon Value","name"=>"coupon_value","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Transferee","name"=>"transferee","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Delivery Date","name"=>"delivery_date","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Total Amount","name"=>"total_amount","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Is Completed","name"=>"is_completed","type"=>"radio","required"=>TRUE,"validation"=>"required|integer","dataenum"=>"Array"];
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
		 		["label"=>"مشاهده سبد","color"=>"success","url"=>CRUDBooster::mainpath('show-shopping-cart').'/[order_code]'],
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
/*	       //Your code here
            $userId = CRUDBooster::myId();
//	    echo $userId;
	    $isAdmin = CRUDBooster::isSuperadmin();
	    $storeAssignedtoUser = DB::table('srv_centers')
                    ->where('cms_user_id', '=', $userId)
                    ->first();
	    if ($isAdmin) {

	    }else {
	    $query->where('cms_users.id',$storeAssignedtoUser->cms_user_id);
	    }
*/
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
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

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
	    public function getShowShoppingCart($order_code){
        	if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                	CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            	}
			
			$cols[0] = "عنوان آیتم";
			$cols[1] = "تعداد";
			$cols[2] = "قیمت";
			$cols[3] = "فروشنده";
			$cols[4] = "آدرس گیرنده";
			$cols[5] = "تحویل گیرنده";
			$cols[6] = "وضعیت";
			$cols[7] = "شناسه";
			$cols[8] = "تاریخ ارسال";
			$cols[9] = "توضیح ارسال";
			$items = DB::table('shop_order_items')
//				->join("prd_orders",'shop_order_items.order_id','=','prd_orders.id')
				->where('shop_order_items.order_code',$order_code)
				->get();
			for($i = 0 ; $i < count($items) ; $i++){
				
				$values[$i][0] = $items[$i]->product_title.' '. $items[$i]->product_color;
				$values[$i][1] = $items[$i]->quantity;
				$values[$i][2] = $items[$i]->product_price;
				$values[$i][3] = $items[$i]->shop_city.'-'. $items[$i]->product_seller;
				$values[$i][4] = $items[$i]->province_name.'-'. $items[$i]->city_name.'-'. $items[$i]->remained;
				$values[$i][5] = $items[$i]->transferee_mobile_number.' - '. $items[$i]->transferee_name;
				$values[$i][7] = $items[$i]->order_item_id;
				$values[$i][8] = $items[$i]->order_transfer_time;
				$values[$i][9] = $items[$i]->transfer_comment;

				if($items[$i]->is_completed == 0)
					$values[$i][6] = "پرداخت نشده";
				else if($items[$i]->is_completed == 1)
				$values[$i][6] = "پرداخت شده";
			}

			$data['cols'] = $cols;
			$data['values'] = $values;
				$this->cbView("shopping_cart", $data);
	    }

		public function getEditOrderItem(){
		
			if(isset($_GET['status']))
			{
				if($_GET['status'] == "editing")
				{
					$order_id 		= $_GET['order_id'];
					$order_item_id 	= $_GET['order_item_id2'];
					$quantity 		= $_GET['quantity'];
					$price 			= $_GET['price'];
					$tariff 		= $_GET['tariff'];

					$old_price		= $_GET['old_price'];
					$old_quantity 	= $_GET['old_quantity'];

					$tariff_status 	= $_GET['tariff_status'];

					$total_amount = 0;
					$total_amount_orig = 0;
					
					$items_ = DB::table('shop_order_items')
					->join("prd_orders",'shop_order_items.order_id','=','prd_orders.id')
					->where('shop_order_items.order_id',$order_id)
					->get();

					$customer_id = DB::table('app_users')
					->join("prd_orders",'app_users.id','=','prd_orders.customer_id')
					->where('prd_orders.id',$order_id)
					->pluck('app_users.id')
					->first();

					for($i = 0 ; $i < count($items_); $i++)
					{
						
						if($items_[$i]->order_item_id == $order_item_id)
						{
							$s = "occured";
							$tot = $items_[$i]->total_amount;
							$total_amount = $total_amount + ($price * $quantity);
						
							$result_query = DB::table('prd_order_change')->insert([
								'order_id' => $order_id,
								'order_item_id' => $order_item_id,
								'quantity_before' => $old_quantity,
								'quantity_after' => $quantity,
								'price_before' => $old_price,
								'price_after' => $price,
								'tariff' => intval($tariff),
								'tariff_status' => $tariff_status
								]);  
							

						}
						else
							$total_amount = $total_amount + ($items_[$i]->quantity * $items_[$i]->product_price);
						
						//calculate original total_amount for next uses
						$total_amount_orig 	= $total_amount_orig + ($items_[$i]->quantity * $items_[$i]->product_price);
						


					}
					$tariff_orig 		= $tot - $total_amount_orig;

					if($tariff_status == 1)
					{
						$total_amount 		= $total_amount + $tariff_orig;
						DB::table("app_users")->where("id", $customer_id)->update([
							'balance' => DB::raw('balance +'. intval($tariff)) 
						]);
					}
					else
						$total_amount 		= $total_amount + $tariff_orig + intval($tariff);
					
					DB::table("prd_orders")->where("id",$order_id)->update([
                        'total_amount' => $total_amount
					]);
					
					DB::table("prd_order_item")->where("id",$order_item_id)->update([
                        'quantity' => $quantity
					]);

					$status = "edited";
					$data['status'] = $status;
					$data['total_amount'] = $total_amount;
					$data['order_item_id'] = $order_item_id;
					$data['items'] = $items_;
					$data['s'] =$s;
					$data['o1'] = $items_[0]->order_item_id;
					$data['o2'] = $items_[1]->order_item_id;
					$data['o3'] = $items_[2]->order_item_id;
					$data['oq'] = $old_quantity;
					$data['op'] = $old_price;
					$data['tot'] = $tot;
					$data['tariff'] = $tariff;
					$data['total_amount_orig'] = $total_amount_orig;


				}
			}

			$change_items_price = DB::table('prd_order_change')
			->where('order_item_id',$_GET['order_item_id2'])
			->pluck('price_after')
			->first();

			$items = DB::table('shop_order_items')
			->join("prd_orders",'shop_order_items.order_id','=','prd_orders.id')
			->where('shop_order_items.order_item_id',$_GET['order_item_id2'])
			->get();

					$values[0] = $items[0]->product_title.' '. $items[0]->product_color;
					$values[1] = $items[0]->quantity;
					if($change_items_price)
						$values[2] = $change_items_price;
					else
						$values[2] = $items[0]->product_price;
					$values[3] = $items[0]->shop_city.'-'. $items[0]->product_seller;
					$values[4] = $items[0]->order_item_id;	
					$values[5] = $items[0]->order_id;	

			$data['values'] = $values;	
			$data['items'] = $items;				
			$this->cbView("edit_order_item",$data);
		}

		public function getSubmitEditOrderItem($order_item_id)
		{
			
			$this->cbView("edit_order_item",$data);
		}

	}
