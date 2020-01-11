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
                  <div class="form-group row">

                    <label for="product_name" class="col-md-4 col-form-label text-md-right">Product</label>

                    <div class="col-md-8">
                      <div class='col-sm-6'>
                        <input type='text' class="form-control" id='datetimepicker1' />
                    </div>
                    </div>
                </div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
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
                 
                    <div class="row">
                            <div class="col-md-2"> </div>

                            <div class="col-md-10">
                                <h5>Total Sales: {{number_format($total,2)}}</h5>
                            </div>
                    </div>

                </div>
            </div>
        </div>

        @endsection

@push("page_scripts")

@include('partials.notification')

 <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datepicker();
            });
        </script>          

  <script>

      $('#fixed-header1').DataTable({
          bAutoWidth: true,
      });




  </script>
@endpush
