@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Current Stock
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Inventory / Current Stock </a></li>
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
                                <th>Name</th>
                                <th>Category</th>
                                <th>QOH</th>
                                @if ($PriceCategory[0]->active =='Yes')
                                    <th> {{$PriceCategory[0]->description}}</th>
                                @endif
                                @if ($PriceCategory[1]->active =='Yes')
                                    <th> {{$PriceCategory[1]->description}}</th>
                                @endif
                                @if ($PriceCategory[2]->active =='Yes')
                                    <th> {{$PriceCategory[2]->description}}</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($Products as $prod)
                                    <tr>
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->category->name}}</td>
                                            <td>{{$prod->quantity}}</td>
                                           
                                            @if ($PriceCategory[0]->active =='Yes')
                                                <td>{{$prod->sale_price_1}}</td>
                                            @endif
                                            @if ($PriceCategory[1]->active =='Yes')
                                                <td>{{$prod->sale_price_2}}</td>
                                            @endif
                                            @if ($PriceCategory[2]->active =='Yes')
                                                <td>{{$prod->sale_price_3}}</td>
                                            @endif

                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-info"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-sale_price_1="{{$prod->sale_price_1}}"
                                                            data-sale_price_2="{{$prod->sale_price_2}}"
                                                            data-sale_price_3="{{$prod->sale_price_3}}"
                                                            type="button"
                                                            data-toggle="modal" data-target="#edit">Set Price
                                                    </button>
                                                </a>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-success"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-quantity="{{$prod->quantity}}"
                                                            type="button"
                                                            data-toggle="modal" data-target="#adjust">Adjust
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

        @endsection

        @push("page_scripts")

            @include('partials.notification')
            @include('inventory.set_price_modal')
            @include('inventory.stock_adjustment_modal')



            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

                $('#edit').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);

                    modal.find('.modal-body #id').val(button.data('id'));
                    modal.find('.modal-body #name').val(button.data('name'));
                    modal.find('.modal-body #sale_price_1').val(button.data('sale_price_1'))
                    modal.find('.modal-body #sale_price_2').val(button.data('sale_price_2'))
                    modal.find('.modal-body #sale_price_3').val(button.data('sale_price_3'))
                });


                $('#adjust').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);

                    modal.find('.modal-body #id').val(button.data('id'));
                    modal.find('.modal-body #name').val(button.data('name'));
                    modal.find('.modal-body #quantity').val(button.data('quantity'))
                  
                });
                
        



            </script>

    @endpush
