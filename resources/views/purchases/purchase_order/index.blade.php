@extends("layouts.master")
@section('content-title')
    Purchase Order

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Purchase Order</a></li>
@endsection


@section("content")
    <style>
        .ms-container {
            background: transparent url('../assets/plugins/multi-select/img/switch.png') no-repeat 50% 50%;
            width: 100%;
        }

        .ms-selectable, .ms-selection {
            background: #fff;
            color: #555555;
            float: left;
            width: 45%;
        }

    </style>
    <div class="col-sm-12">
        <div class="card-block">
            <div class="col-sm-12">
                <div class="tab-content" id="myTabContent">
                    <form action="{{ route('purchase-order.store') }}" method="post" enctype="multipart/form-data"
                          id="order_form">
                        @csrf()
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Supplier Name <font color="red">*</font></label>
                                    <select name="supplier" class="js-example-basic-single form-control" id="supplier"
                                            required="true" onchange="filterSupplierProduct()">
                                        <option selected="true" disabled="disabled" value="">Select Supplier...</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Products <font color="red">*</font></label>
                                    <select id="select_id" class="form-control">
                                        <option selected="true" disabled="disabled" value="">Select Product...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="detail">
                            <hr>
                            <div class="table responsive" style="width: 100%;">
                                <table id="cart_table" class="table nowrap table-striped table-hover"
                                       width="100%"></table>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="padding-top: 10px">
                                    <div style="width: 99%">
                                        <label> <b> Remarks</b></label>
                                        <textarea class="form-control" id="note" name="note" rows="2"
                                                  placeholder="Enter Remarks Here.."></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--   <div class="col-md-4">
                                  <div style="width: 99%">
                                  <label>Discount</label> -->
                            <input type="hidden" id="purchase_discount" name="discount_amount" class="form-control"
                                   value="0"/>
                            <!--       </div>
                              </div> -->
                            <div class="col-md-4">
                                <div class="row">
                                    <label class="col-md-6 col-form-label text-md-right"><b>Sub Total:</b></label>
                                    <div class="col-md-6" style="display: flex; justify-content: flex-end">
                                        <input type="text" id="sub_total" name="sub_total_amount"
                                               class="form-control-plaintext text-md-right" readonly value="0.0"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <label
                                        class="col-md-6 col-form-label text-md-right"><b>VAT:</b></label>
                                    <div class="col-md-6"
                                         style="float: right; display: flex; justify-content: flex-end">
                                        <input type="text" id="vat" name="vat_total_amount"
                                               class="form-control-plaintext text-md-right" readonly value="0.0"/>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-md-6 col-form-label text-md-right"><b>Total
                                            Amount:</b></label>
                                    <div class="col-md-6" style="display: flex; justify-content: flex-end">
                                        <input type="text" id="total" name="total_amount"
                                               class="form-control-plaintext text-md-right"
                                               readonly value="0.00"/>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <hr>
                        <input type="hidden" id="order_cart" name="cart">
                        <input type="hidden" id="id_vat" name="vat">
                        <input type="hidden" id="total_price" name="total_amount">
                        <input type="hidden" id="sub_total_price" name="sub_total_amount"/>
                        <input type="hidden" value="{{$vat}}" id="vats">
                        <input type="hidden" id="supplier_ids" name="supplier_ids">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group" style="float: right;">
                                    <button type="button" class="btn btn-danger" id="deselect-all"
                                            onclick="return false">Cancel
                                    </button>
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
        var config = {
            routes: {
                filterSupplierProduct: '{{route('filter-product')}}',
                filterSupplierProductInput: '{{route('filter-product-input')}}'
            }
        };
    </script>

    <script src="{{asset("assets/apotek/js/purchases.js")}}"></script>
    <script src="{{asset("assets/apotek/js/notification.js")}}"></script>


@endpush
