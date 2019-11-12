@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Price Categories
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Masters / Price Categories </a></li>
@endsection

@section("content")

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <button style="float: right;margin-bottom: 2%;" type="button" class="btn btn-secondary btn-sm"
                            data-toggle="modal"
                            data-target="#create">
                        Add Price Category
                    </button>
                    <div class="table-responsive">
                        <table id="fixed-header-1" class="display table nowrap table-striped table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Price Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($price_category as $price)
                                <tr>
                                    <td>{{$price->name}}</td>
                                    <td>{{$price->type}}</td>
                                    <td>
                                        <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-info" data-id="{{$price->id}}"
                                                    data-name="{{$price->name}}"
                                                    data-type="{{$price->type}}"
                                                    type="button"
                                                    data-toggle="modal" data-target="#edit">Edit
                                            </button>
                                        </a>
                                        <a href="#">
                                            <button class="btn btn-sm btn-rounded btn-danger"
                                                    data-id="{{ $price->id }}"
                                                    data-name="{{$price->name}}" type="button"
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
    </div>
    @include('masters.price_category.create')
    @include('masters.price_category.edit')
    @include('masters.price_category.delete')
@endsection

@push("page_scripts")

    @include('partials.notification')


    <script>

        $('#edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-body #price_category_id').val(button.data('id'));
            modal.find('.modal-body #name_edit').val(button.data('name'));
            modal.find('.modal-body #code_edit').val(button.data('type'))

        });

        $('#delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);

            var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
            var modal = $(this);

            modal.find('.modal-body #message').text(message);
            modal.find('.modal-body #price_category_id').val(button.data('id'))
        });

        $('#fixed-header-1').DataTable({
            bAutoWidth: false,
            columnDefs: [
                {
                    "targets": 2,
                    "className": "text-center"
                }
            ]
        });


    </script>

@endpush
