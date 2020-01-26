@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Staff Losses
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Expense Management / Staff Losses </a></li>
@endsection

@section("content")



<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form id="expense_form" action="" method="post">
                    @csrf()

                    <div class="form-group row">

               
                     
                        <div class="col-md-10">
                        </div>
                  
                        <div class="col-md-2">
                            @can('Add Staff Loss')
                                <input type="button" name="create_report" value="Add"
                                    class="form-control btn btn-primary" data-toggle="modal" data-target="#create">
                            @endcan
                        </div>
                    </div>

                </form>
<hr>
             
                <div class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Staff Name</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Remarks</th>
                                @can('Edit Staff Loss')
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($StaffAdvance as $s)
                            <tr>
                                <td>{{$s->date}}</td>
                                <td>{{$s->user->name}}</td>
                                <td>{{number_format($s->amount,2)}}</td>
                                <td>{{$s->type}}</td>
                                <td>{{ $s->remarks }}</td>
                            
                                    @can('Edit Staff Loss')
                                    <td>

                                        <a href="#">
                                            <button class="btn btn-warning btn-sm" data-remarks="{{$s->remarks}}"
                                                data-id="{{$s->id}}"   data-user="{{$s->user_id}}"    data-type="{{$s->type}}"
                                                data-amount="{{$s->amount}}"    data-date="{{$s->date}}"
                                                type="button" data-toggle="modal" data-target="#edit">Edit</button>
                                        </a>
                                    </td>

                                    @endcan


                

                            </tr>
                            @endforeach

                        </tbody>
                
                    </table>
                    <div >
                       
                    </div>
    
                </div>
                
                
      
            </div>
        </div>
    </div>
</div>

@include("expense.staff_advance.create")
@include("expense.staff_advance.edit")



@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')

<script>
    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      order: [[0, "desc"]]
    });


    $(function () {
    var start = moment();
    var end = moment();

    $('#date').daterangepicker({
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

    $('#date1').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            maxDate: end,
            autoUpdateInput: true,
            locale: {
                format: 'DD-M-YYYY'
            }
        });
    });



      $('#edit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
    
        modal.find('.modal-body #date1').val(button.data('date'));
        modal.find('.modal-body #amount1').val(button.data('amount'));
        modal.find('.modal-body #type1').val(button.data('type'));
        modal.find('.modal-body #remarks1').val(button.data('remarks'));
        modal.find('.modal-body #user1').val(button.data('user'));
        modal.find('.modal-body #id').val(button.data('id'));
      });//end edit

  

      
</script>

@endpush