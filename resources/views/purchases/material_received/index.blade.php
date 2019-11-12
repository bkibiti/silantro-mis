@extends("layouts.master")
@section('content-title')
    Material Received
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management /Material Received</a></li>
@endsection


@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">

                        <label>Date</label>
                        <input type="text" name="expire_date" class="form-control" id="receive_date"
                               onchange="getMaterialsReceived()">
                    </div>
                    <div class="col-md-4">
                        <label for="code">Product</label>
                        <select id="received_product"
                                class="js-example-basic-single form-control" onchange="getMaterialsReceived()">
                            <option value="">Select Product</option>
                            @foreach($products as $stock)
                                <option value="{{$stock->id}}">{{$stock->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="code">Supplier Name</label>
                            <select class="js-example-basic-single form-control" id="supplier"
                                    onchange="getMaterialsReceived()">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                </div>
                <div id="tbody1" class="table-responsive">
                    <table id="received_material_table" class="display table nowrap table-striped table-hover"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Expire Date</th>
                            <th>Buy Price</th>
                            <th>Total Buy</th>
                            <th>Sell Price</th>
                            <th>Total Sell</th>
                            <th>Profit</th>
                            <!-- <th>Supplier</th> -->
                            <th>Receive Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <hr>
                <div class="col-md-6" style="float: right;">
                <div class="row" >
                    
                        <label class="col-md-6 col-form-label text-md-right"><b style="font-size: 1.4em" class="col-md-6 text-md-right">Total Cost: </b></label>
                        <div class="col-md-6" style="display: flex; justify-content: flex-end">
                        <span id="total_bp" style="font-size: 1.4em"></span>
                    </div>
                </div>
                <div class="row">
                        <label class="col-md-6 col-form-label text-md-right"><b style="font-size: 1.4em" class="col-md-6 text-md-right">Total Sell: </b></label>
                        <div class="col-md-6" style="display: flex; justify-content: flex-end">
                        <span id="total_sp" style="font-size: 1.4em"></span>
                    </div>
                </div>
                <div class="row">
                        <label class="col-md-6 col-form-label text-md-right"><b style="font-size: 1.4em" class="col-md-6 text-md-right">Total Profit: </b></label>
                         <div class="col-md-6" style="display: flex; justify-content: flex-end">
                        <span id="total_pf" style="font-size: 1.4em"></span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <hr>
@endsection
@push("page_scripts")
    @include('partials.notification')
    <script type="text/javascript">
        $(function () {
            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#receive_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#receive_date').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: true,
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
        function getMaterialsReceived() {
            var product_id = document.getElementById("received_product").value;
            var supplier_id = document.getElementById("supplier").value;
            var range = document.getElementById("receive_date").value;
            var date = range.split('-')
            if (product_id || date || supplier_id) {
                $.ajax({
                    url: '{{route('getMaterialsReceived')}}',
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "product_id": product_id,
                        "supplier_id": supplier_id,
                        "date": date
                    },
                    type: 'get',
                    dataType: 'json',
                    success: function (data) {

                        document.getElementById("total_bp").innerHTML = formatMoney(data[0][1]);
                        document.getElementById("total_sp").innerHTML = formatMoney(data[0][2]);
                        document.getElementById("total_pf").innerHTML = formatMoney(data[0][3]);
                        received_material_table.clear();
                        received_material_table.rows.add(data[0][0]);
                        received_material_table.draw();
                    },
                });
            }
        }

        var received_material_table = $('#received_material_table').DataTable({
            searching: true,
            bPaginate: true,
            bInfo: false,
            columns: [
                {data: 'product.name'},
                {data: 'quantity'},
                {
                    data: 'expire_date', render: function (expire_date) {
                        return moment(expire_date).format('MMM DD,YYYY')
                    }
                },
                {
                    data: 'unit_cost', render: function (unit_cost) {
                        return formatMoney(unit_cost)
                    }
                },
                {
                    data: 'total_cost', render: function (total_cost) {
                        return formatMoney(total_cost)
                    }
                },
                {
                    data: 'sell_price', render: function (sell_price) {
                        return formatMoney(sell_price)
                    }
                },
                {
                    data: 'total_sell', render: function (total_sell) {
                        return formatMoney(total_sell)
                    }
                },
                {
                    data: 'item_profit', render: function (item_profit) {
                        return formatMoney(item_profit)
                    }
                },
// {data:'supplier.name'},
                {
                    data: 'date', render: function (date) {
                        return moment(date).format('MMM DD,YYYY');
                    }
                }
            ]
        });


        function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        }

    </script>

@endpush
