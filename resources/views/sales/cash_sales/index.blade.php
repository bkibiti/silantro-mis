@extends("layouts.master")
@section('content-title')
    Point of Sale
@endsection
@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Sales Management / Point of Sale</a></li>
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

    <div class="col-sm-12">
        <div class="card-block">
            <div class="col-sm-12">
                <div class="tab-content" id="myTabContent">
                    <form id="sales_form" action="{{ route('cash-sales.storeCashSale') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf()
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="cat_label">Sales Type<font color="red">*</font></label>
                                    <select id="price_category" class="js-example-basic-single form-control">
                                        <option value="">Select Type</option>
                                        @foreach($price_category as $price)
                                            <option value="{{$price->id}}">{{$price->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Products<font color="red">*</font></label>
                                    <select id="products" class="js-example-basic-single form-control">
                                        <option value="" disabled selected style="display:none;">Select Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="code">Customer Name </label>
                                    <select name="customer_id" class="js-example-basic-single form-control">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="detail">
                            <hr>
                            <div class="table teble responsive" style="width: 100%;">
                                <table id="cart_table" class="table nowrap table-striped table-hover"
                                       width="100%"></table>
                            </div>

                        </div>
                        <hr>
                        @if($back_date=="NO")
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="width: 99%">
                                        <label>Discount</label>
                                        <input type="text" onchange="discount()" id="sale_discount" class="form-control"
                                               value="0"/>
                                                 <span class="help-inline">
                                          <div class="text text-danger" style="display: none;" id="discount_error">Invalid Discount</div>
                                           </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="width: 99%" hidden>
                                        {{--<label><b>Sub Total</b></label>--}}
                                        {{--<input type="text" id="sub_total" class="form-control" readonly value="0" />--}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label class="col-md-6 col-form-label text-md-right"><b>Sub Total:</b></label>
                                        <div class="col-md-6" style="display: flex; justify-content: flex-end">
                                            <input type="text" id="sub_total" class="form-control-plaintext"
                                                   readonly value="0"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label
                                            class="col-md-6 col-form-label text-md-right"><b>VAT:</b></label>
                                        <div class="col-md-6" style="display: flex; justify-content: flex-end">
                                            <input type="text" id="total_vat" class="form-control-plaintext"
                                                   readonly value="0"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6 col-form-label text-md-right"><b>Total
                                                Amount:</b></label>
                                        <div class="col-md-6" style="display: flex; justify-content: flex-end">
                                            <input type="text" id="total" class="form-control-plaintext"
                                                   readonly value="0"/>

                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="price_cat" name="price_category_id">
                                <input type="hidden" id="discount_value" name="discount_amount">
                                <input type="hidden" id="order_cart" name="cart">
                                <input type="hidden" value="{{$vat}}" id="vat">
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-3">
                                    <div style="width: 99%">
                                        <label>Sale Date<font color="red">*</font></label>
                                        <input type="text" name="sale_date" class="form-control" id="cash_sale_date"
                                               autocomplete="off" required="true">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="width: 99%">
                                        <label>Discount</label>
                                        <input type="text" onchange="discount()" id="sale_discount" class="form-control"
                                               value="0"/>
                                    </div>
                                          <span class="help-inline">
                                                        <div class="text text-danger" style="display: none;"
                                                             id="discount_error">Invalid Discount</div>
                                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <div style="width: 99%">
                                        <label><b>Sub Total</b></label>
                                        <input type="text" id="sub_total" class="form-control" readonly value="0"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="width: 99%">
                                        <label><b>Total</b></label>
                                        <input type="text" id="total" class="form-control" readonly value="0"/>
                                    </div>

                                </div>
                                <input type="hidden" id="price_cat" name="price_category_id">
                                <input type="hidden" id="discount_value" name="discount_amount">
                                <input type="hidden" id="order_cart" name="cart">
                                <input type="hidden" value="{{$vat}}" id="vat">
                                <input type="hidden" value="" id="total_vat">
                            </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div style="width: 99%">
                                    <label><b>Paid</b></label>
                                    <input type="text" onchange="discount()" id="sale_paid" class="form-control"
                                           value="0"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="width: 99%">
                                    <label><b>Change</b></label>
                                    <input type="text" id="change_amount" class="form-control" value="0" readonly/>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="btn-group" style="float: right;">
                                    <button class="btn btn-danger" id="deselect-all" onclick="return false">Cancel</button>
                                    <button class="btn btn-primary" id="save_btn">Save</button>
                                </div>
                            </div>
                        </div>

                    </form>


                </div>

            </div>
        </div>
    </div>


@endsection
@push("page_scripts")
    @include('partials.notification')
    <script type="text/javascript">
        var config = {
            token: '{{ csrf_token() }}',
            routes: {
                selectProducts: '{{route('selectProducts')}}'

            }
        };

 $('#sales_form').on('submit', function () {
            window.open('#', '_blank');
            window.open(this.href, '_self');
            location.reload();
        });


    </script>
    <script src="{{asset("assets/apotek/js/sales.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>

@endpush
