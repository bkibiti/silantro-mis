@extends("layouts.master")
@section('content-title')
Credit Sales
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Credit Sales</a></li>
@endsection

@section("content")

<style>
.iti__flag {background-image: url("{{asset("assets/plugins/intl-tel-input/img/flags.png")}}");}

@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
.iti__flag {background-image: url("{{asset("assets/plugins/intl-tel-input/img/flags@2x.png")}}");}
}
.iti { width: 100%; }
.datepicker > .datepicker-days {
display: block;
}

ol.linenums {
margin: 0 0 0 -8px;
}
</style>

<div class="col-sm-12">
<div class="card-block">
<div class="col-sm-12">
<div class="tab-content" id="myTabContent">
<form action="{{ route('credit-sales.storeCreditSale') }}" method="post"
enctype="multipart/form-data" id="sales_form">
@csrf()
<div class="row">
<div class="col-md-12">
<button style="float: right;margin-bottom: 2%;" type="button"
class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#create"> Add
New Customer
</button>
</div>

</div>

<div id="sale-panel">
<div class="row">
<div class="col-md-3">
<div class="form-group">
<label id="cat_label">Sales Type<font color="red">*</font></label>
<select id="price_category" class="js-example-basic-single form-control">
<option value="">Select Type</option>
@foreach($price_category as $price)
<option value="{{$price->id}}">{{$price->name}}</option>
@endforeach
</select>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label>Products<font color="red">*</font></label>
<select id="products" class="js-example-basic-single form-control">
<option value="" disabled selected style="display:none;">Select Product
</option>
</select>
</div>
</div>
<div class="col-md-3">
<div class="form-group">
<label for="code">Customer Name<font color="red">*</font></label>
<select name="customer_id" id="customer"
class="js-example-basic-single form-control" title="Customer" required>
<option class="btn btn-secondary" value="{}">Select Customer</option>
@foreach($customers as $customer)
<option value="{{$customer}}">{{$customer->name}}</option>
@endforeach
</select>
</div>
</div>
</div>

<div class="row" id="detail">
<hr>
<div class="table teble responsive" style="width: 100%;">
<table id="cart_table" class="table nowrap table-striped table-hover"
width="100%"></table>
</div>

</div>
<hr>
@if($back_date=="NO")
<div class="row">
<div class="col-md-4">
<div style="width: 99%">
<label>Discount</label>
<input type="text" onchange="discount()" id="sale_discount"
class="form-control" value="0"/>
</div>
<span class="help-inline">
<div class="text text-danger" style="display: none;" id="discount_error">Invalid Discount!</div>
</span>
</div>
<div class="col-md-4">
<div style="width: 99%">
<label>Paid</label>
<input type="text" onchange="discount()" id="sale_paid" class="form-control"
value="0"/>
</div>
</div>
<div class="col-md-4">
<div style="width: 99%">
<label>Grace Period</label>
<select  class="js-example-basic-single form-control" name="grace_period">
<option value="1">1 Day</option>
<option value="7">7 Days</option>
<option value="14">14 Days</option>
<option value="21">21 Days</option>
<option value="30">30 Days</option>
<option value="60">60 Days</option>
<option value="90">90 Days</option>  
</select>

</div>
</div>

<input type="hidden" id="price_cat" name="price_category_id">
<input type="hidden" id="discount_value" name="discount_amount">
<input type="hidden" id="paid_value" name="paid_amount">
<input type="hidden" id="credit_sale" name="credit" value="Yes">
<input type="hidden" id="order_cart" name="cart">
<input type="hidden" value="{{$vat}}" id="vat">
</div>
@else
<div class="row">
<div class="col-md-3">
<div style="width: 99%">
<label>Sale Date<font color="red">*</font></label>
<input type="text" name="sale_date" class="form-control"
id="credit_sale_date" autocomplete="off" required="true">
</div>
</div>
<div class="col-md-3">
<div style="width: 99%">
<label>Discount</label>
<input type="text" onchange="discount()" id="sale_discount"
class="form-control" value="0"/>
</div>
<span class="help-inline">
<div class="text text-danger" style="display: none;" id="discount_error">Invalid Discount!</div>
</span>
</div>
<div class="col-md-3">
<div style="width: 99%">
<label>Paid</label>
<input type="text" onchange="discount()" id="sale_paid" class="form-control"
value="0"/>
</div>
</div>
<div class="col-md-3">
<div style="width: 99%">
<label>Grace Period (Days)</label>
<input type="number" min="0" name="grace_period" class="form-control"
value="0"/>
</div>
</div>

<input type="hidden" id="price_cat" name="price_category_id">
<input type="hidden" id="discount_value" name="discount_amount">
<input type="hidden" id="paid_value" name="paid_amount">
<input type="hidden" id="credit_sale" name="credit" value="Yes">
<input type="hidden" id="order_cart" name="cart">
<input type="hidden" value="{{$vat}}" id="vat">
</div>
@endif
<hr>
<div class="row">
<div class="col-md-8">
<div class="form-group">
<textarea name="remark" class="form-control" rows="3"
placeholder="Enter Remarks Here"></textarea>
</div>
</div>

<div class="col-md-4">
<div class="row">
<div class="col-md-6">
<b>Sub Total:</b>
</div>
<div class="sub-total col-md-6"
style="display: flex; justify-content: flex-end">0.00
</div>
</div>
<div class="row">
<div class="col-md-6">
<b>VAT:</b>
</div>
<div class="tax-amount col-md-6"
style="display: flex; justify-content: flex-end">0.00
</div>
</div>
<div class="row">
<div class="col-md-6">
<b>Total Amount:</b>
</div>
<div class="total-amount col-md-6"
style="display: flex; justify-content: flex-end">0.00
</div>
</div>
<div class="row">
<div class="col-md-6">
<b>Balance:</b>
</div>
<div class="balance-amount col-md-6"
style="display: flex; justify-content: flex-end">0.00
</div>
</div>
<div class="row">
<div class="col-md-6">
<b>Max. Credit:</b>
</div>
<div class="credit_max col-md-6"
style="display: flex; justify-content: flex-end">0.00
</div>
</div>
</div>
<input type="hidden" id="total">
<input type="hidden" id="sub_total">
<input type="hidden" id="total_vat" value="0">
</div>
<hr>
<div class="row">
<div class="col-md-6"></div>
<div class="col-md-6">
<div class="btn-group" style="float: right;">
<button class="btn btn-danger" id="deselect-all">Cancel</button>
<button class="btn btn-primary" id="save_btn">Save</button>
</div>
</div>
</div>

</div>
</form>
</div>
</div>
</div>
</div>

@include('sales.customers.create')

@endsection

@push("page_scripts")
@include('partials.notification')
<script type="text/javascript">
var config = {
token: '{{ csrf_token() }}',
routes: {
selectProducts: '{{route('selectProducts')}}'

}
};
   $('#sales_form').on('submit', function () {
            window.open('#', '_blank');
            window.open(this.href, '_self');
            location.reload();
        });
</script>

<script src="{{asset("assets/apotek/js/sales.js")}}"></script>
<script src="{{asset("assets/apotek/js/customer.js")}}"></script>
<script
src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@endpush
