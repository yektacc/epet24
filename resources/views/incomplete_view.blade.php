@extends('crudbooster::admin_template')
@section('content')
    <div class='panel panel-default'>
        <div class='panel-heading'>فرم ثبت پاسخ درخواست</div>
        <div class='panel-body'>
            <form id="message-form" method="get" action="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath("set-incomplete/$id")}}">
                <div class='form-group'>
                    <label>متن پاسخ:</label>
                    <textarea name="message" class="form-control" required></textarea>
                </div>
            </form>
        </div>
        <div class='panel-footer'>
            <input type='submit' class='btn btn-primary' value='ثبت' form="message-form"/>
            <a class='btn btn-default' href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath()}}">
                <i class="fa fa-chevron-circle-left"></i>بازگشت
            </a>
        </div>
    </div>
    {{--@push('bottom')--}}
    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function () {--}}
            {{--$('#modal-incomplete').modal('show');--}}
        {{--});--}}
    {{--</script>--}}
    {{--<div id='modal-incomplete' class="modal" tabindex="-1" role="dialog">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form method="get" action="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::mainpath('set-incomplete')}}">--}}
                        {{--<textarea name="message" placeholder="متن پیام" class="form-control"></textarea>--}}
                        {{--<button class="btn btn-success" type="submit">ثبت</button>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button class="btn btn-success" type="submit" data-dismiss="modal">ثبت</button>--}}
                {{--</div>--}}

            {{--</div><!-- /.modal-content -->--}}
        {{--</div><!-- /.modal-dialog -->--}}
    {{--</div><!-- /.modal -->--}}
    {{--@endpush--}}
@endsection