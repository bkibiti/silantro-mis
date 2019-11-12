@extends("layouts.master")

@section('content-title')
Sale Returns Approval
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Sale Returns Approval</a> </li>
@endsection


@section("content")
<div class="col-sm-12">            
<div class="tab-content" id="myTabContent">
<div class="row">

<div class="col-md-4">

<label>Date of Return</label>
<input type="text" class="form-control" id="returned_date" onchange="getRetunedProducts()">
</div>
<div class="col-md-4">
<label for="code">Status</label>
<select  id="retun_status"
class="js-example-basic-single form-control" onchange="getRetunedProducts()">
<option value="2">Pending</option>
<option value="3">Approved</option>
<option value="4">Rejected</option>
</select>
</div>

<div class="form-group col-md-4">
<label for="Search">Search</label>   
<input type="text" class="form-control" id="searching_returns" placeholder="Search" />
</div>

</div>
<div class="table-responsive">
<table  id="return_table" class="display table nowrap table-striped table-hover" style="width:100%">
<thead>
<tr>
<th>Product Name</th>
<th>Buy Date</th>
<th>Qty Bought</th>
<th>Return Date</th>
<th>Qty Returned</th>
<th>Refund</th>
<th>Action</th>
</tr>
</thead>
<tbody>

</tbody>

</table>

</div>
</div>
</div>
@endsection
@push("page_scripts")
@include('partials.notification')

<script type="text/javascript">

$(function() {
var start = moment();
var end = moment();
function cb(start, end) {
$('#returned_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}
$('#returned_date').daterangepicker({
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


$('#return_table tbody').on( 'click', '#approve', function () {
var product =  return_table.row( $(this).parents('tr') ).data();
getRetunedProducts('approve',product.item_returned)
});

$('#return_table tbody').on( 'click', '#reject', function () {
var product =  return_table.row( $(this).parents('tr') ).data();
getRetunedProducts('reject',product.item_returned)
});


function getRetunedProducts(action,product){
var status =document.getElementById("retun_status").value;
var range = document.getElementById("returned_date").value;
var date=range.split('-')
if(date){
$.ajax({
url : '{{route('getRetunedProducts')}}',
data: {
"_token":'{{ csrf_token() }}',
"date":date,
"status":status,
"action":action,
"product":product
},
type: 'get',
dataType: 'json',
cache: false,
success: function(data)
{
if(status == 3){

return_table.column(6).visible (false);
data.forEach(function(data){
if(data.status==5){
data.item_returned.bought_qty += data.item_returned.rtn_qty;//This calculate the original bought qty
data.item_returned.amount= (data.item_returned.amount/data.item_returned.rtn_qty)*  data.item_returned.bought_qty;
}

});
}
else if(status==4){
return_table.column(6).visible (false);
}
else{
return_table.column(6).visible (true);  
}
return_table.clear();
return_table.rows.add(data);
return_table.draw();

},
});
}
}

var return_table = $('#return_table').DataTable({
bPaginate:true,
bInfo: false,
dom:'t',
columns: [
{data: 'item_returned.name'},
{data:'item_returned.b_date',render: function ( date ) {
return  moment(date).format('MMM DD,YYYY');
}},
{data:'item_returned.bought_qty'},
{data: 'date',render: function ( date ) {
return  moment(date).format('MMM DD,YYYY');
}},
{data:'item_returned.rtn_qty'},
{data: 'item_returned',render: function ( item_returned ) {
return formatMoney((item_returned.rtn_qty/item_returned.bought_qty)*(item_returned.amount-item_returned.discount));
}},
{data: "action",defaultContent: "<button type='button' id='approve' class='btn btn-sm btn-rounded btn-primary'>Approve</button><button type='button' id='reject' class='btn btn-sm btn-rounded btn-danger'>Reject</button>"}

]
});

$('#searching_returns').on( 'keyup', function () {
return_table.search( this.value ).draw();
});




</script>

<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>

<script src="{{asset("assets/js/pages/sales.js")}}"></script>





@endpush
{{--@if($count>0)
<tr>
@foreach($sales_returns as $s_return)
<td>{{$s_return->item_returned->name}}</td>
<td>{{date('j M, Y', strtotime($s_return->item_returned->b_date))}}</td>
@if($s_return->item_returned->status == 3||$s_return->item_returned->status == 5)

<td>{{$s_return->item_returned->bought_qty+$s_return->quantity}}</td>
@else
<td>{{$s_return->item_returned->bought_qty}}</td>
@endif


<td>{{date('j M, Y', strtotime($s_return->date))}}</td>
<td>{{$s_return->quantity}}</td>
@if($s_return->item_returned->bought_qty != 0)
<td>{{number_format((($s_return->item_returned->amount-$s_return->item_returned->discount)/$s_return->item_returned->bought_qty)*$s_return->quantity,2)}}</td>
@else
<td>{{($s_return->item_returned->amount/1)*$s_return->quantity}}</td>
@endif
<td>
@if($s_return->item_returned->status == 2)
<button class="btn btn-sm btn-rounded btn-success"
data-r_qty="{{$s_return->quantity}}"
data-stock-id="{{$s_return->item_returned->stock_id}}"
data-qty="{{$s_return->item_returned->bought_qty}}"
data-item_detail_id="{{$s_return->item_returned->item_detail_id}}"
data-reason="{{$s_return->reason}}"
type="button"data-toggle="modal"
data-target="#approve">Approve
</button>
<button class="btn btn-sm btn-rounded btn-danger"
data-id="{{$s_return->id}}"
data-item_detail_id="{{$s_return->item_returned->item_detail_id}}"
data-reason="{{$s_return->reason}}"
type="button"data-toggle="modal"
data-target="#reject">Reject
</button>
@elseif($s_return->item_returned->status == 4)
<h4><span class="badge badge-danger">Rejected !</span></h4>
@else
<h4><span class="badge badge-success">Approved</span></h4>

@endif

</td>
</tr> 

@endforeach
@endif--}}