@extends("layouts.master")
@section('content-title')
    Point of Sale
@endsection



@section("content")

    <div class="col-sm-5">
            <div class="card">
                <div class="card-body">
               
                    <div id="product-table" class="table-responsive">
                        <table id="products" class="display table nowrap table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($current_stock as $stock)
                                    <tr>
                                        <td>{{ $stock->name }}</td>
                                        <td>
                                                <a href="#">
                                                        <button class="btn btn-sm btn-rounded btn-info selectproduct"
                                                                data-id="{{$stock->id}}"
                                                                data-name="{{$stock->name}}"
                                                                data-sale_price_1="{{$stock->sale_price_1}}"
                                                                data-quantity="{{$stock->quantity}}"
                                                                data-toggle="button">+
                                                        </button>
                                                    </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div> 
            </div>
        </div>

    </div>

    <div class="col-sm-7">
        <div class="card">
                <div class="card-body">
                    <div id="order-table" class="table-responsive">
                        <table id="order" class="display table nowrap table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                                <tbody>
                          
                                </tbody>
                        </table>
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
            $('#products').DataTable({
                bAutoWidth: true,
                lengthChange: false,
            });
    
            var t = $('#order').DataTable({
                bAutoWidth: false,
                lengthChange: false,
                paging:   false,
                ordering: false,
                info:     false,
                searching: false
            });

            var addedItems = [];

            $('.selectproduct').click(function() {
            
              if(rowDoesNotExist($(this).data('id'),addedItems)){
                   
                    t.row.add( [
                        $(this).data('id'),
                        $(this).data('name'),
                        1,
                        $(this).data('sale_price_1'),
                        $(this).data('sale_price_1')
                       
                    ] ).draw( false );

                    addedItems.push( $(this).data('id'));
              }
               
            });
     

            
        function rowDoesNotExist(number,numArray) {
           var result = jQuery.inArray( number, numArray);
           
            if (result >= 0 ) {
                return false;
            }else{
                return true;
            }
            
        };
    
          
    
        </script>
@endpush
