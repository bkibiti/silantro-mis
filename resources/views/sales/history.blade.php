@extends("layouts.master")

@section('page_css')
<style>

</style>

@endsection

@section('content-title')
Sales History
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales / Sales History </a></li>
@endsection

@section("content")


<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <form id="expense_form" action="{{route('sales.history-search')}}" method="post">
                    @csrf()

                    <div class="form-group row">

                        <label for="product_name" class="col-md-1 col-form-label text-md-left">From :</label>
                        <div class="col-md-2">
                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="from_date" class="form-control" id="from_date" required>
                            </div>
                        </div>
                        <label for="product_name" class="col-md-1 col-form-label text-md-right">To Date:</label>
                        <div class="col-md-2">
                            <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                <input type="text" name="to_date" class="form-control" id="to_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Show</button>
                        </div>
                    </div>

                </form>

                <hr>
                <div id="product-table" class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $s)
                            <tr>
                                <td>{{$s->receipt_number}}</td>
                                <td>{{date_format($s->created_at,'d M Y')}}</td>
                                <td>{{$s->product->name}}</td>
                                <td>{{number_format($s->quantity,0)}}</td>
                                <td>{{number_format($s->selling_price,2)}}</td>
                                <td>{{number_format(($s->selling_price * $s->quantity), 2)}}</td>
                                <td>{{$s->user->name}}</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
<hr>
                <div class="row">

                    <div class="col-md-12">
                        <h4>Total Sales (Tshs): {{number_format($total,2)}}</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endsection

    @push("page_scripts")

    @include('partials.notification')
    <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>


    <script>
        $('#fixed-header1').DataTable({
          bAutoWidth: true,
      });

   
    $(function () {
        var start = moment();
        var end = moment();

        $('#from_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });
    $(function () {
        var start = moment();
        var end = moment();

        $('#to_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });



    </script>
    @endpush