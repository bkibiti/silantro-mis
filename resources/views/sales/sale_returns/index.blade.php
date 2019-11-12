@extends("layouts.master")

@section('content-title')
Sales Return
@endsection
@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Sales Return</a></li>
@endsection
@section("content")
<div class="col-sm-12">
<div class="card-block">
<div class="tab-content" id="myTabContent">
<input type="hidden" value="{{$vat}}" id="vat">
<div class="table-responsive" id="items" style="display: none;">
<h4>Sale Items List</h4>
<table id="items_table" class="table nowrap table-striped table-hover" width="100%"></table>
<div class="btn-group" style="float: right;">
<button class="btn btn-rounded btn-danger" onclick="return false" id="cancel">Close</button>
</div>
</div>
<div id="sales">
<div class="row">
<div class="col-md-6">

<label>Date of Sale</label>
<input type="text" name="expire_date" class="form-control" id="sold_date" onchange="getSales()">
</div>
<div class="form-group col-md-6">
<label for="Seach">Search</label>   
<input type="text" class="form-control" id="searching_sales" placeholder="Search" />
</div>
</div>
<div class="table-responsive" >
<table id="sale_list_return_table" class="display table nowrap table-striped table-hover"
style="width:100%">
<thead>
<tr>
<th>Receipt #</th>
<th>Date</th>
<th>Sale Type</th>
<th>Sub Total</th>
<th>VAT</th>
<th>Discount</th>
<th>Amount</th>
<th>Action</th>
</tr>
</thead>
<tbody>


</tbody>
</table>
</div>
</div>


</div>

</div>


</div>
</div>
@include('sales.sale_returns.return')

@endsection
@push("page_scripts")
@include('partials.notification')
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
<script src="{{asset("assets/apotek/js/sales.js")}}"></script>

<script type="text/javascript">
$(function() {

var start = moment();
var end = moment();

function cb(start, end) {
$('#sold_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}
$('#sold_date').daterangepicker({
startDate: start,
endDate: end,
autoUpdateInput:true,
ranges: {
'Today': [moment(), moment()],
'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
'Last 7 Days': [moment().subtract(6, 'days'), moment()],
'Last 30 Days': [moment().subtract(29, 'days'), moment()],
'This Month': [moment().startOf('month'), moment().endOf('month')],
'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
'This Year': [moment().startOf('year'), moment()]
}
}, cb);

cb(start, end);



});

</script>

<script type="text/javascript">




function getSales(){
var range = document.getElementById("sold_date").value;
var date=range.split('-')
if(date){
$.ajax({
url : '{{route('getSales')}}',
data: {
"_token":'{{ csrf_token() }}',
"date":date
},
type: 'get',
dataType: 'json',
cache: false,
success: function(data)
{

sale_list_return_table.clear();
sale_list_return_table.rows.add(data);
sale_list_return_table.draw();

},
});
}
}


var sale_list_return_table = $('#sale_list_return_table').DataTable({
bPaginate:false,
bInfo: false,
dom: 't',
columns: [
{data: 'receipt_number'},
{data: 'date',render: function ( date ) {
return  moment(date).format('MMM DD,YYYY');
}},
{data: 'cost.name'},
{data: 'cost',render: function ( cost ) {
return formatMoney( ((cost.amount-cost.discount)/(1+ (cost.vat/cost.sub_total))));
}},

{data: 'cost',render: function ( cost ) {
return formatMoney( ((cost.amount-cost.discount)*(cost.vat/cost.sub_total)));
}},
{data: 'cost.discount'},
{data: 'cost',render: function ( cost ) {
return formatMoney( ((cost.amount-cost.discount)));
}},
{data: "action",defaultContent: "<button type='button' id='open_btn' class='btn btn-sm btn-rounded btn-success'>Open</button>"}
]
});


$('#sale_list_return_table tbody').on( 'click', '#open_btn', function () {
var row_data=  sale_list_return_table.row( $(this).parents('tr') ).data();
saleReturn(row_data.details);
});

$('#searching_sales').on( 'keyup', function () {
sale_list_return_table.search( this.value ).draw();
});

</script> 


@endpush
