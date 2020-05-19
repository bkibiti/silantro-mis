@extends("layouts.master")

@section('page_css')
@endsection

@section('content-title')
   Reminders
@endsection

@section('content-sub-title')

<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Reminders</a> </li>


@endsection

@section("content")


<div class="col-sm-12">
    <div class="card-block">
        
        <div class="col-sm-12">
                <div class="card" >
                    <div class="card-body" >
                        @can('Add Reminders')
                            <button style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                        title="Add Reminder" data-target="#create"><i class="feather icon-plus"></i></button>
                        @endcan
                        <div class="table-responsive">
                            <table id="fixed-header" class="display table nowrap table-striped table-hover"style="width:100%">
                            <thead>
                                <tr>
                                    <th>Reminder</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days to Get Riminder</th>
                                    <th>Status</th>
                                    @if(Auth::user()->can('Edit Reminders') || Auth::user()->can('Delete Reminders'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                                 <tbody>
                                    @foreach($reminders as $r)
                                        <tr>
                                            <td>{{$r->name}}</td>
                                            <td>{{date_format(new DateTime($r->start_date),'d M Y')}}</td>
                                            <td>{{date_format(new DateTime($r->end_date),'d M Y')}}</td>
                                            <td>{{$r->days_to_remind}}</td>
                                            <td>
                                                @if ($r->status == "On")
                                                    <span class="badge badge-pill badge-success">{{$r->status}}</span>
                                                @else
                                                    <span class="badge badge-pill badge-danger">{{$r->status}}</span>
                                                @endif
                                                
                                            </td>

                                            @if(Auth::user()->can('Edit Reminders') || Auth::user()->can('Delete Reminders'))
                                                <td>
                                                    @can('Edit Reminders')
                                                        <a href="#">
                                                            <button class="btn btn-sm btn-rounded btn-info"
                                                                    data-id="{{$r->id}}"
                                                                    data-name="{{$r->name}}"
                                                                    data-start_date="{{$r->start_date}}"
                                                                    data-end_date="{{$r->end_date}}"
                                                                    data-days_to_remind="{{$r->days_to_remind}}"
                                                                    data-status="{{$r->status}}"
                                                                    type="button"
                                                                    data-toggle="modal" data-target="#edit">Edit
                                                            </button>
                                                        </a>
                                                    @endcan
                                                    @can('Delete Reminders')
                                                        <a href="#">
                                                            <button class="btn btn-sm btn-rounded btn-danger"
                                                                data-id="{{$r->id}}"
                                                                data-name="{{$r->name}}"
                                                                type="button"
                                                                data-toggle="modal"
                                                                data-target="#delete"> Delete
                                                            </button>
                                                        </a>
                                                    @endcan
                                                </td>
                                           @endif
                                        </tr>
                                    @endforeach
                                        </tbody>
                                </table>
                            </div>
                    </div>
            </div>
        </div>
</div>

    @include('reminders.create')
    @include('reminders.delete')
    @include('reminders.edit')

@endsection


@push("page_scripts")
@include('partials.notification')
<script>
    var title = document.title;
    document.title = title.concat(" | Reminders");
</script>
 <script>
    $(function () {
        var start = moment();
        var end = moment();

        $('#start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });

    $(function () {
        var start = moment();
        var end = moment();

        $('#end_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });

    $(function () {
        var start = moment();
        var end = moment();

        $('#start_date_edit').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });

    $(function () {
        var start = moment();
        var end = moment();

        $('#end_date_edit').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });

            $('#edit').on('show.bs.modal', function (event) {
                  var button = $(event.relatedTarget);
                  var modal = $(this);

                  modal.find('.modal-body #id').val(button.data('id'));
                  modal.find('.modal-body #name_edit').val(button.data('name'));
                  modal.find('.modal-body #start_date_edit').val(button.data('start_date'));
                  modal.find('.modal-body #end_date_edit').val(button.data('end_date'));
                  modal.find('.modal-body #days_edit').val(button.data('days_to_remind'));
                  modal.find('.modal-body #status').val(button.data('status'));

            });

            $('#delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var message = "Are you sure you want to delete '".concat(button.data('name'), "'?");
                var modal = $(this);
                modal.find('.modal-body #message').text(message);
                modal.find('.modal-body #id_del').val(button.data('id'));

            })

</script>

  
   

@endpush
