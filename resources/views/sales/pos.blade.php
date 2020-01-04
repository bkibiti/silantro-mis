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
                                                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-success selectproduct"
                                                                data-id="{{$stock->id}}"
                                                                data-name="{{$stock->name}}"
                                                                data-sale_price_1="{{$stock->sale_price_1}}"
                                                                data-quantity="{{$stock->quantity}}"
                                                                data-toggle="button"><i class="feather icon-plus"></i>
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
                        
                        </table>

                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-6"><h6> Total Order Amount </h6></div>
                        <div  class="col-sm-4"><h6 id ="total">  </h6></div>
                        
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
            
            var order_table = $('#order').DataTable({
                searching: false,
                bPaginate: false,
                ordering: false,
                bInfo: false,
                columns: [
                    { title: "Id" },
                    { title: "QOH" },
                    { title: "Item" },
                    { title: "Quantity" },
                    { title: "Price" },
                    { title: "Sub Total" },
                    { title: "Action", defaultContent:  '<button type="button" id="edit_btn" class="btn btn-icon btn-rounded btn-sm btn-primary"><i class="feather icon-edit"></i></button><button type="button" id="delete_btn" class="btn btn-icon btn-rounded btn-sm btn-danger"><i class="feather icon-trash-2"></i></button>'},
                   
                ]
            });

            var order_list = []; //hold data displayed in datatable


            $('.selectproduct').click(function() {
                var data = [];
                if(! rowExist($(this).data('id'),order_list)){

                        data.push($(this).data('id'));
                        data.push($(this).data('quantity'));
                        data.push($(this).data('name'));
                        data.push(1);
                        data.push(formatMoney($(this).data('sale_price_1')));
                        data.push(formatMoney($(this).data('sale_price_1')));
                        order_list.push(data);
                        
                        order_table.clear();
                        order_table.rows.add(order_list).draw();
                        $('#total').text(formatMoney(totalOrder(order_list)));
                }
               
            });
     
            $('#order tbody').on('click', '#edit_btn', function () {
                var row_data = order_table.row($(this).parents('tr')).data();
                var index = order_table.row($(this).parents('tr')).index();
                quantity = row_data[3];
                row_data[3] = "<input type='number' min='1' class='form-control' id='edit_quantity' required  onkeypress='return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57'>";
                order_list[index] = row_data;
                order_table.clear();
                order_table.rows.add(order_list);
                order_table.draw();
                document.getElementById("edit_quantity").value = quantity;
            });

            $('#order tbody').on('change', '#edit_quantity', function () {
                var row_data = order_table.row($(this).parents('tr')).data();
                var index = order_table.row($(this).parents('tr')).index();
                row_data[3] = Number((document.getElementById("edit_quantity").value));

                if (row_data[3] < 1) { row_data[3] = 1 }
        
                if (row_data[3] > row_data[1]) { //OrderQty > QOH
                    row_data[3] = row_data[1];
                    row_data[5] = formatMoney(row_data[3] * parseFloat(row_data[4].replace(/\,/g, '')));
                }
                else {
                    row_data[5] = formatMoney(row_data[3] * parseFloat(row_data[4].replace(/\,/g, '')));
                }

                order_list[index] = row_data;
                order_table.clear();
                order_table.rows.add(order_list);
                order_table.draw();
                $('#total').text(formatMoney(totalOrder(order_list)));

                console.log(order_list);
            });
            
            $('#order tbody').on('click', '#delete_btn', function () {
	            var index = order_table.row($(this).parents('tr')).index();
                order_list.splice(index, 1);
                order_table.clear();
                order_table.rows.add(order_list);
                order_table.draw();
                $('#total').text(formatMoney(totalOrder(order_list)));

            });

            //check if items is already added on the table
            function rowExist(value2Check,Array2D) {
                var count = 0;
                for (var i = 0; i <Array2D.length; i++) {
                    if(Array2D[i][0] == value2Check){
                        count++;
                    }
                }
                if (count > 0 ) {
                    return true;
                }else{
                    return false;
                }
            };
            //sum sub total to get total amount of the order
            function totalOrder(array2d){
                var sum = 0;
                for(var i=0;i<array2d.length;i++){
                    sum +=  parseFloat(array2d[i][5].replace(/\,/g, ''));
                }
                return sum;
            }

            function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
                try {
                    decimalCount = Math.abs(decimalCount);
                    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
                    const negativeSign = amount < 0 ? "-" : "";
                    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                    let j = (i.length > 3) ? i.length % 3 : 0;
                    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
                } catch (e) {
                }
            }

          
    
        </script>
@endpush
