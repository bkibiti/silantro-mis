@extends("layouts.master")

@section('content-title')
  Sales Quotes
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Sales Quotes</a> </li>
@endsection


@section("content")
  <style>
      .ms-container{
  background: transparent url('../assets/plugins/multi-select/img/switch.png') no-repeat 50% 50%;
  width: 100%;
}
.ms-selectable,.ms-selection{
  background: #fff;
  color: #555555;
  float: left;
  width: 45%;
}
</style>
<div class="col-sm-12">
 <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">New</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Quotes List</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <form action="{{ route('sale-quotes.store') }}" method="post"  enctype="multipart/form-data">
                                    @csrf()
                                <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label id="cat_label">Sales Type</label>
                                                <select id="price_category" class="js-example-basic-single form-control">
                                                <option value="">Select Sales Type</option>
                                                @foreach($price_category as $price)
                                                 <option value="{{$price->id}}">{{$price->name}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                <label>Products</label>
                                                <select id="products" class="js-example-basic-single form-control">
                                                     <option value="" disabled selected style="display:none;">Select Product</option>
                                                </select>
                                                </div>
                                            </div>
                                        <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="code">Customer Name</label>
                                                  <select name="customer_id" class="js-example-basic-single form-control">
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                 <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                                </div>
                                            </div>
                                    </div>

                                     <div class="row" id="detail"><hr>
                                         <div class="table teble responsive" style="width: 100%;">
                                    <table id="cart_table" class="table nowrap table-striped table-hover" width="100%"></table>
                                    </div>

                                     </div>
                                       <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div style="width: 99%">
                                            <label>Discount</label>
                                         <input type="text"  onchange="discount()" id="sale_discount" class="form-control" value="0"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div style="width: 99%">
                                            <label><b>Sub Total</b></label>
                                            <input type="text" id="sub_total" class="form-control" readonly value="0" />
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                            <div style="width: 99%">
                                            <label><b>Total Amount</b></label>
                                            <input type="text" id="total"class="form-control" readonly value="0" />
                                            </div>
                                            <span class="help-inline">
                                          <div class="text text-danger" style="display: none;" id="discount_error">Invalid Total Amount (Negative Value!)</div>
                                           </span>
                                        </div>
                                        <input type="hidden" value="" id="total_vat">

                                         <input type="hidden" value="{{$vat}}" id="vat">
                                         <input type="hidden" value="0" id="sale_paid">
                                         <input type="hidden" value="Yes" id="quotes_page">
                                         <input type="hidden" value="0" id="change_amount">
                                        <input type="hidden" id="price_cat" name="price_category_id">
                                        <input type="hidden" id="discount_value" name="discount_amount">
                                        <input type="hidden" id="order_cart" name="cart">
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                 <label>Remarks</label>
                                            <textarea name="remark" class="form-control" ></textarea>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <div class="btn-group"  style="float: right;">
                                            <button class="btn btn-danger" id="deselect-all">Cancel</button>
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                        </div>
                                    </div>
                            
                        <form>
                                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                
                    <div class="table-responsive" id="sales">
                       <table id="sale_quotes-Table" class="display table nowrap table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
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
                          @if($count>0)
                        <tr>
                           @foreach($sale_quotes as $quote)
                                 <td>{{date('j M, Y', strtotime($quote->date))}}</td>
                                 <td>{{$quote->cost->name}}</td>
                                 <td>{{number_format($quote->cost->sub_total,2)}}</td>
                                 <td>{{number_format($quote->cost->vat,2)}}</td>
                                <td>{{number_format($quote->cost->discount,2)}}</td>
                                 <td>{{number_format($quote->cost->amount,2)}}</td>
                                <td>
                                 <button class="btn btn-sm btn-rounded btn-success" type="button" 
                                  onclick="quoteDetails('{{$quote->remark}}',{{$quote->details}})"
                                  id="quote_details"
                                >Details
                                 </button>
                    </td>
                         </tr>
                    @endforeach
                    @endif

                        </tbody>
                    </table> 
                    </div>
                                        </div>
                                    </div>
                                </div>
 @include('sales.sale_quotes.details')
@endsection

@push("page_scripts")
@include('partials.notification') 
 <script type="text/javascript">
            var config = {
                token:'{{ csrf_token() }}',
                routes: {
                    selectProducts: '{{route('selectProducts')}}'

                }
            };

    $(document).ready(function() {
    $('#sale_quotes-Table').DataTable( {
         language: {
         emptyTable: "No Sales Quote Data Available in the Table"
         },
    } );
} );

    //Maintain the current Pill on reload
$(function() { 
    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('lastPill', $(this).attr('href'));
    });
        var lastPill = localStorage.getItem('lastPill');
        

    if (lastPill) {
        $('[href="' + lastPill + '"]').tab('show');
    }
});

        </script> 
<script src="{{asset("assets/apotek/js/sales.js")}}"></script>


@endpush
