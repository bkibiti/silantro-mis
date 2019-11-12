@extends("layouts.master")

@section('content-title')
  Sales History
@endsection
@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Sales History</a> </li>
@endsection

@section("content")

<div class="col-md-12">
    <div class="card-block">
          <div class="tab-content" id="myTabContent">
                   <input type="hidden" value="{{$vat}}" id="vat">
               
                   <div class="row">
                    <div class="form-group col-md-6">
                    <label for="filter">Filter by Date</label>   
                   <input type="text" class="form-control" id="daterange"/>
                   </div>
                     <div class="form-group col-md-6">
                    <label for="Seach">Search</label>   
                   <input type="text" class="form-control" id="searching" placeholder="Search" />
                   </div>

                   </div>
                    <div class="table-responsive" id="sales">

                    <table id="sale_history_table" class="display table nowrap table-striped table-hover"
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
 @include('sales.sales_history.details')

@endsection

@push("page_scripts")
   <script src="{{asset("assets/apotek/js/sales.js")}}"></script>

    <script type="text/javascript">
            var config = {
                token:'{{ csrf_token() }}',
                routes: {
                    getSalesHistory: '{{route('getSalesHistory')}}'

                }
            };

        </script> 
  <script type="text/javascript">
    $(function() {

    var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#daterange').daterangepicker({
        startDate: start,
        endDate: end,
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
 
   


@endpush
