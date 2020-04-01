@extends('crudbooster::admin_template')
@section('content')
<div class='panel panel-default'>
        <div class='panel-heading'>ویرایش کالای سفارشی</div>
        <?php
            
            $order_item_id = $values[4];
            //echo "order_item_id: ". $order_item_id;
            if($status == "edited")
            {
        ?>
                
                    <?php
                            echo '<div style="color:green; font-size: 15px; text-align:center;">';
                            echo "اطلاعات با موفقیت ویرایش گردید";
                            echo '</div>';
                        //    echo "ok". $total_amount." and order_item_id: ".$order_item_id." o1: ".$o1." o2: ".$o2." o3: ".$o3." s:".$s." op:".$op." oq:".$oq." tot:".$tot." tariff:".$tariff." total_amount_orig:".$total_amount_orig;
                        // var_dump($items);
            }
        ?>

        <form id="message-form" method="get" action="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath("edit-order-item/$order_item_id")}}"></br>
        <div class="form-control0"> &nbsp;
            <label for="product_name">نام کالا : &hairsp;</label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &hairsp; &emsp13;
            <input name="product_name" type='text' size="76px" align="center" class='form-control0' value='<?= $values[0]; ?>' form="message-form" disabled/>
        </div>
        <br>
        <div class="form-control1"> &nbsp;
            <label for="quantity">تعداد : &nbsp; &thinsp;</label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &hairsp; &ensp;
            <input name="quantity" type='text' size="76px" height="60%" class='form-control1' value='<?= $values[1]; ?>' form="message-form"/><br>
        </div>
        </br>
        <div class="form-control2"> &nbsp;
            <label for="price">قیمت واحد(تومان) : &nbsp; &hairsp;</label> &hairsp; &ensp; &hairsp; &hairsp; &nbsp;
            <input name="price" type='text' size="72px" class='form-control2' value='<?= $values[2]; ?>' form="message-form"/><br>
	</div>
	<br>
        <div class="form-control3"> &nbsp;
            <label for="fname">هزینه پستی :</label> &nbsp; &nbsp; &nbsp; &nbsp; &hairsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;
            <input type="radio"  name="tariff_status"  class='form-control3' value='1'/>
            <label for="fname">بستانکار (طلب مشتری)</label> &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;
            <input type="radio"  name="tariff_status"  class='form-control3' value='0'/>
            <label for="fname">بدهکار (بدهکاری مشتری) </label><br>
        </div>    
        <br>
            <div class="form-control4"> &nbsp;
            <label for="tariff">مبلغ پستی(تومان)  :</label> &hairsp; &nbsp; &nbsp; &hairsp; &nbsp; &nbsp; &nbsp; &hairsp;
            <input name="tariff" type='text' size="72px" class='form-control4' form="message-form"/>
        </div>
        <br>
            <input name="order_item_id2" type='hidden' class='form-control' value='<?= $values[4]; ?>' form="message-form"/>
            <input name="order_id" type='hidden' class='form-control' value='<?= $values[5]; ?>' form="message-form"/>
            <input name="status" type='hidden' class='form-control' value='editing' form="message-form"/>
            <input name="old_quantity" type='hidden' class='form-control' value='<?= $values[1]; ?>' form="message-form"/>
            <input name="old_price" type='hidden' class='form-control' value='<?= $values[2]; ?>' form="message-form"/>
           
       
            <div class='panel-footer'> &nbsp;
                <input type='submit' class='btn btn-primary' value='ویرایش' form="message-form"/>
                <a class='btn btn-default' href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath()}}">
                    <i class="fa fa-chevron-circle-left"></i>  بازگشت
                </a>
            </div>
            
        </form>
    </div>
@endsection