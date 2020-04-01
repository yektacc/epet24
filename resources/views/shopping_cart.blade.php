@extends('crudbooster::admin_template')
@section('content')
    <div class='panel panel-default'>
        <div class='panel-heading'>سبد خرید</div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table id='table-detail' class='table table-striped'>
                    <thead>
                            <td>انتخاب</td>
                            <?php for ($i = 0 ; $i < count($cols) ; $i++){ ?>
                            <th><?php echo $cols[$i]; ?> </th>
                            <?php } ?>
                    </thead>
                    <tbody>
                    <form id="message-form" method="get" action="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath("edit-order-item")}}">
                    <?php
                        
                        for($i = 0 ; $i < count($values) ; $i++){
                    ?>
                            <tr>
                                <td>
                                    <input type="radio" name="order_item_id2" value='<?= $values[$i][7]; ?>' />
                                </td>
                                <?php for ($j = 0 ; $j < count($values[$i]) ; $j++){ ?>
                                    
                                        <td><?php echo $values[$i][$j]; ?> </td>

                                    <?php 
                                    } 
                                    
                                    $order_item_id = $values[$i][$j-1];
                                    ?>
                                
                            </tr>
                        <?php
                        }
                        ?>
                    
                        </form>
                        </tbody>
                            </table>
                            </div>
                        </div>
                        
                        <div class='panel-footer'>
                            <input type='submit' class='btn btn-primary' value='ویرایش' form="message-form"/>
                            <a class='btn btn-default' href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath()}}">
                                <i class="fa fa-chevron-circle-left"></i> بازگشت
                            </a>
                        </div>
                    </div>
@endsection
