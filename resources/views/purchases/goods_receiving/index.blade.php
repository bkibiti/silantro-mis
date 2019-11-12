@extends("layouts.master")

@section('content-title')

    Goods Receiving

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Goods Receiving</a></li>
@endsection
@section("content")

    <style>
        .datepicker > .datepicker-days {
            display: block;
        }

        ol.linenums {
            margin: 0 0 0 -8px;
        }
    </style>

    <style type="text/css">
        .iti__flag {
            background-image: url("{{asset("assets/plugins/intl-tel-input/img/flags.png")}}");
        }

        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("{{asset("assets/plugins/intl-tel-input/img/flags@2x.png")}}");
            }
        }

        .iti {
            width: 100%;
        }
    </style>
    <div class="col-sm-12">
        <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="quotes_list-tab" data-toggle="pill"
                   href="#item-receive" role="tab"
                   aria-controls="quotes_list" aria-selected="true">Product Receiving</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" id="new_quotes-tab" data-toggle="pill" href="#order-receive"
                   role="tab" aria-controls="new_quotes" aria-selected="false">Order Receiving
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="order-receive" role="tabpanel" aria-labelledby="new_quotes-tab">
                <div class="table-responsive" id="items" style="display: none;">
                    <h4>Ordered Products List</h4>
                    <table id="items_table" class="table nowrap table-striped table-hover" width="100%"></table>
                    <div class="btn-group" style="float: right;">
                        <button class="btn btn-danger" onclick="return false" id="cancel">Close</button>
                    </div>
                </div>
                <div class="table-responsive" id="purchases">
                    <table id="sale_list_Table" class="display table nowrap table-striped table-hover"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td> {{$order->order_number}}</td>
                                <td> {{$order->supplier['name']}}</td>
                                <td>{{date('Y-m-d',strtotime($order->ordered_at))}}</td>
                                <td> {{number_format($order->total_amount)}}</td>
                                <td>
                                    @if ($order->status == '3')
                                        <h6 class="m-0 text-c-green">Received</h6>
                                    @endif
                                    @if ($order->status == '2')
                                        <h6 class="m-0 text-c-purple">Partial Received</h6>
                                    @endif
                                </td>
                                @if($order->status == '3')
                                    <td>
                                        <button class="btn btn-sm btn-rounded btn-info"
                                                onclick="orderReceive({{$order->details}})"
                                        >Preview Order
                                        </button>
                                    </td>
                                @else
                                    <td>
                                        <button class="btn btn-sm btn-rounded btn-warning"
                                                onclick="orderReceive({{$order->details}},{{$order->supplier['id']}})"
                                        >Receive Order
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade show active" id="item-receive" role="tabpanel"
                 aria-labelledby="quotes_list-tab">
                <form name="item" id="myFormId">
                    @csrf()
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code">Supplier Name <font color="red">*</font></label>
                                <select name="supplier" class="js-example-basic-single form-control"
                                        id="supplier_ids" required="true" onchange="filterInvoiceBySupplier()">
                                    <option selected="true" value="" disabled="disabled">Select Supplier...</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Products <font color="red">*</font></label>
                                <select id="selected-product" class="js-example-basic-single form-control"
                                        required="true">
                                    <option selected="true" value="" disabled="disabled">Select Product...</option>
                                    @foreach($current_stock as $stock)
                                        <option
                                            value="{{$stock['product_name'].'#@'.$stock['product_id'].'#@'.$stock['unit_cost']}}">{{$stock['product_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code">Invoice #</label>
                                <select name="invoice_no" class="form-control js-example-basic-single" id="invoice_id"
                                        required="true">
                                    <option selected="true" value="" disabled="disabled">Select Invoice..</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{$invoice->id}}">{{$invoice->invoice_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code">Batch # <font color="red">*</font></label>
                                <input type="text" name="batch_number" class="form-control" id="batch_n"
                                       required="true" value="{{session('batch_number')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="detail">
                        <hr>
                        <div class="table teble responsive" style="width: 100%;">
                            <table id="cart_table" class="table nowrap table-striped table-hover" width="100%"></table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding-top: 10px">
                                <div style="width: 99%">
                                    <label for="price_category">Price Category <font color="red">*</font></label>
                                    <select name="price_category" class="form-control js-example-basic-single"
                                            id="price_category" required="true" onchange="priceByCategory()">
                                        @foreach($price_categories as $price_category)
                                            <option value="{{$price_category->id}}">{{$price_category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding-top: 10px">
                                <label for="code">Buy Price<font color="red">*</font></label>
                                <input type="text" id="buy_price" name="unit_cost" class="form-control" min="0"
                                       value="0" required="true" onchange="amountCheck()"
                                       onkeypress="return isNumberKey(event,this)">
                                <span class="help-inline"></span>
                                <div class="text text-danger" class="price_error"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding-top: 10px">
                                <label for="code">Sell Price<font color="red">*</font></label>
                                <input type="text" name="sell_price" class="form-control" min="0" value="0"
                                       required="true" id="sell_price_id" onchange="amountCheck()"
                                       onkeypress="return isNumberKey(event,this)">
                                <span class="help-inline"></span>
                                <div class="amount_error text text-danger"></div>
                            </div>
                        </div>

                        <div class="col-md-3">

                            <div class="form-group" style="padding-top: 10px">
                                <label>Expire Date <font color="red">*</font></label>
                                <input type="text" name="expire_date" class="form-control" id="expire_date_21"
                                       autocomplete="off" required="true">

                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="expire_check"
                                           style="padding:10px" value="true" onchange="findselected()">
                                    <label class="form-check-label" for="expire_check">No Expire Date</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" id="received_cart" name="cart">
                    <input type="hidden" name="" id="sell">
                    <input type="hidden" name="" id="buy">
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" style="float: right;">
                                <button type="button" class="btn btn-danger" id="cancel-all" onclick="resetForms()">
                                    Clear
                                </button>
                                <button id="save_id"
                                        class="btn btn-primary">Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('purchases.goods_receiving.receive')
@endsection
@push("page_scripts")
    @include('partials.notification')

    <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
    <script src="{{asset("assets/apotek/js/goods-receiving.js")}}"></script>
    <script src="{{asset("assets/apotek/js/notification.js")}}"></script>


    <script>

        var config = {
            routes: {
                goodsreceiving: '{{route('receiving-price-category')}}',
                filterBySupplier: '{{route('filter-invoice')}}',
                itemFormSave: '{{route('goods-receiving.itemReceive')}}'
            }
        };

        $(document).ready(function () {
            resetForms();
        });

        function resetForms() {
            document.getElementById('myFormId').reset();
        }

        var a = 1;

        function findselected() {

            a = -a;
            if (a < 1) {
                document.getElementById("expire_date_21").setAttribute('disabled', false);
            } else {
                document.getElementById("expire_date_21").removeAttribute('disabled');
            }
        }

        function amountCheck() {

            var unit_price = document.getElementById('buy_price').value;
            var sell_price = document.getElementById('sell_price_id').value;
            var unit_price_parse = (parseFloat(unit_price.replace(/\,/g, ''), 10));
            var sell_price_parse = (parseFloat(sell_price.replace(/\,/g, ''), 10));

            document.getElementById('sell_price_id').value = formatMoney(parseFloat(sell_price.replace(/\,/g, ''), 10));
            document.getElementById('buy_price').value = formatMoney(parseFloat(unit_price.replace(/\,/g, ''), 10));

            if (Number(sell_price_parse) < Number(unit_price_parse) && Number(sell_price_parse) !== Number(0)
                && Number(unit_price_parse) !== Number(0)) {

                $('#save_id').prop('disabled', true);
                $('div.amount_error').text('Cannot be less than Buy Price');
            } else if (Number(sell_price_parse) === Number(unit_price_parse)) {
                $('#save_id').prop('disabled', true);
                $('div.amount_error').text('Cannot be equal to Buy Price');
            } else {

                $('#save_id').prop('disabled', false);
                $('div.amount_error').text('');

            }
        }

        function isNumberKey(evt, obj) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            var value = obj.value;
            var dotcontains = value.indexOf(".") !== -1;
            if (dotcontains)
                if (charCode === 46) return false;
            if (charCode === 46) return true;
            return !(charCode > 31 && (charCode < 48 || charCode > 57));

        }

        $(function () {
            var start = moment();
            var end = moment();

            $('#expire_date_21').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minDate: start,
                autoUpdateInput: true,
                locale: {
                    format: 'DD-M-YYYY'
                }
            });
        });

    </script>

@endpush
