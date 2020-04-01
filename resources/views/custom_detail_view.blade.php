@extends('crudbooster::admin_template')
@section('content')
    <div class='panel panel-default'>
        <div class='panel-heading'><?php echo $page_title;?></div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table id='table-detail' class='table table-striped'>
                    <?php
                    foreach ($fields as $field=>$value){?>
                    <tr>
                        <td><?php echo $field; ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                    <?php }
                    ?>
                </table>
            </div>
        </div>
        <div class='panel-footer'>
            <a class='btn btn-default' href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath()}}">
                <i class="fa fa-chevron-circle-left"></i>بازگشت
            </a>
        </div>
    </div>
@endsection