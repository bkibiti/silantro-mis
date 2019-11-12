@extends("layouts.master")
@section('content-title')
   Credits Tracking
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Credits Tracking</a> </li>
@endsection


@section("content")

<div class="col-sm-12">
    <div class="card-block">
        <div class="col-sm-12">
                <div class="tab-content" id="myTabContent">
                                    <div class="row">
                                       <input type="hidden" id="track" value="1">
                                        <input type="hidden" id="vat" value="">
                                           <div class="form-group col-md-4">
                                            <label for="filter">Sale Date</label>   
                                         <input type="text" name="date_of_sale" class="form-control" id="sales_date" value="" />
                                           </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="code">Customer</label>
                                                  <select name="customer_id" id="customer_payment" class="js-example-basic-single form-control">
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                 <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="code">Credit Status</label>
                                                  <select name="status" id="payment-status" class="js-example-basic-single form-control">
                                                <option value="all">All</option>
                                                <option value="not_paid">Not Paid</option>
                                                <option value="partial_paid">Partial Paid</option>
                                                <option value="full_paid">Full Paid</option>
                                            </select>
                                                </div>
                                        </div>
                                    </div>

                                     <div class="row" id="detail"><hr>
                                          @can('Credit Payment')
                                          <div id="can_pay"></div>
                                          @endcan
                                         <div class="table teble responsive" style="width: 100%;">
                                     <table id="credit_payment_table" class="display table nowrap table-striped table-hover"
                                   style="width:100%">

                                <thead>
                                <tr>
                                    <th>Receipt#</th>
                                    <th>Customer</th>
                                    <th>Sale Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                                    </div>

                                     </div>
                                   </div>
                                     @include('sales.credit_sales.create_payment')

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
        $('#sales_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#sales_date').daterangepicker({
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
            var config = {
                token:'{{ csrf_token() }}',
                routes: {
                    getCreditSale: '{{route('getCreditSale')}}'

                }
            }; 
        </script> 
<script src="{{asset("assets/apotek/js/sales.js")}}"></script>
@endpush


