@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
Stock Adjustment History
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Inventory / Stock Adjustment History</a></li>
@endsection

@section("content")


    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3">
                          
                        </div>
                    </div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Original Qty</th>
                                <th>Adjusted Qty</th>
                                <th>Remained Qty</th>
                                <th>Adjustment Reason</th>
                                <th>Adjusted By</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($adjustments as $adjust)
                                    <tr>
                                            <td>{{$adjust->product->name}}</td>
                                            <td>{{$adjust->original_qty}}</td>
                                            <td>{{$adjust->adjusted_qty}}</td>
                                            <td>{{$adjust->remain_qty}}</td>
                                            <td>{{$adjust->reason}}</td>
                                            <td>{{$adjust->user->name}}</td>

                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

        @endsection

        @push("page_scripts")

            @include('partials.notification')




            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

            </script>

    @endpush
