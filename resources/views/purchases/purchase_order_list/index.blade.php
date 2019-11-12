@extends("layouts.master")
@section('content-title')

    Order History

@endsection
@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchases Management / Purchase Order History</a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="purchases">
                    <div class="form-group row">
                        <div class="col-md-6">
                        </div>
                        <label for="" class="col-md-3 col-form-label text-md-right"
                               style="margin-right: -2%">Date:</label>
                        <div class="col-md-3">
                            <input style="width: 109%;" type="text" name="order_filter" class="form-control"
                                   onchange="getOrderHistory()"
                                   id="date_filter">
                        </div>
                    </div>
                    <form action="{{route('printOrder')}}" method="post">
                        @csrf()
                        <table id="order_history_datatable" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Supplier</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div hidden>
                            <input type="text" name="order_no" id="order_no">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('purchases.purchase_order_list.delete')
    @include('purchases.purchase_order_list.details')

@endsection
@push("page_scripts")
    <script src="{{asset("assets/apotek/js/orderlist.js")}}"></script>

    <script type="text/javascript">

        var config = {
            routes: {
                getOrderHistory: '{{route('getOrderHistory')}}'

            }
        };

    </script>
@endpush
