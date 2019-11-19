@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Products
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Masters / Products </a></li>
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
                            <div class="form-group">
                                <button style="float: right;margin-bottom: 2%;" type="button"
                                        class="btn btn-secondary btn-sm"
                                        data-toggle="modal"
                                        data-target="#create">
                                    Add Product
                                </button>
                            </div>
                        </div>
                    </div>


                    <div id="product-table" class="table-responsive">
                        <table id="fixed-header1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Id #</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Purchase Unit</th>
                                <th>Qty in Unit</th>
                                <th>Min Qty</th>
                                <th>Re-Order Level</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($products as $prod)
                                    <tr>
                                            <td>{{$prod->id}}</td>
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->category->name}}</td>
                                            <td>{{$prod->purchase_uom}}</td>
                                            <td>{{$prod->quantity_per_unit}}</td>
                                            <td>{{$prod->min_quantinty}}</td>
                                            <td>{{$prod->reorder_level}}</td>
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-info"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-category_id="{{$prod->category->id}}"
                                                            data-purchase_uom="{{$prod->purchase_uom}}"
                                                            data-quantity_per_unit="{{$prod->quantity_per_unit}}"
                                                            data-min_quantinty="{{$prod->min_quantinty}}"
                                                            data-reorder_level="{{$prod->reorder_level}}"
                                                            type="button"
                                                            data-toggle="modal" data-target="#edit">Edit
                                                    </button>
                                                </a>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-danger"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            type="button"
                                                            data-toggle="modal"
                                                            data-target="#delete">
                                                        Delete
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

        @include('masters.products.create')
        @include('masters.products.edit')
        @include('masters.products.delete')

        @endsection

        @push("page_scripts")

            @include('partials.notification')


            <script>

                $('#fixed-header1').DataTable({
                    bAutoWidth: true,
                });

                $('#edit').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);

                    modal.find('.modal-body #id').val(button.data('id'));
                    modal.find('.modal-body #name_edit').val(button.data('name'))
                    modal.find('.modal-body #category_id_edit').val(button.data('category_id'))
                    modal.find('.modal-body #purchase_uom_edit').val(button.data('purchase_uom'))
                    modal.find('.modal-body #quantity_per_unit_edit').val(button.data('quantity_per_unit'))
                    modal.find('.modal-body #min_quantinty_edit').val(button.data('min_quantinty'))
                    modal.find('.modal-body #reorder_level_edit').val(button.data('reorder_level'))
                });

                $('#delete').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
                    var modal = $(this);

                    modal.find('.modal-body #message').text(message);
                    modal.find('.modal-body #id').val(button.data('id'));
                });



            </script>

    @endpush
