@extends("layouts.master")

@section('page_css')
    <style>

    </style>
@endsection

@section('content-title')
    Purchase History
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase / Purchase History </a></li>
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
                                <th>Purchase Date</th>
                                <th>Item</th>
                                <th>Purchase Qty</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Supplier</th>
                                <th>User</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($purchases as $p)
                                    <tr>
                                            <td>{{date_format($p->created_at,'d M Y')}}</td>
                                            <td>{{$p->product->name}}</td>
                                            <td>{{number_format($p->quantity,0) . " (". $p->product->purchase_uom .")"}}</td>
                                            <td>{{number_format($p->unit_cost,2)}}</td>
                                            <td>{{number_format(($p->unit_cost * $p->quantity), 2)}}</td>
                                            <td>{{$p->supplier->name}}</td>
                                            <td>{{$p->user->name}}</td>
                                       
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                        </table>
                        
                    </div>
                 <hr>
                    <div class="row">
             
                            <div class="col-md-12">
                                <h4>Total Purchases (Tshs): {{number_format($total,2)}}</h4>
                            </div>
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
