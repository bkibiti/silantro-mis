@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Daily Sale Report
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#"> Sales Management / Daily Sale Report </a></li>
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
                        {{-- <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Show</button>
                        </div> --}}
                        <div class="col-md-2">
                            {{-- @can('Add Expenses') --}}
                            <input type="button" name="create_report" value="Add Report"
                                class="form-control btn btn-primary" data-toggle="modal" data-target="#create">
                            {{-- @endcan --}}
                        </div>
                    </div>

                </form>
<hr>
             
                <div class="table-responsive">
                    <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sales</th>
                                <th>Other Income</th>
                                <th>Expenses</th>
                                <th>Other Expenses</th>
                                <th>Cash on Hand</th>
                                {{-- <th>Submission Remarks</th>
                                <th>Submitted By</th>
                                <th>Submiited At</th>
                                <th>Approver Remarks</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($DailySale as $s)
                            <tr>
                                <td>{{$s->report_date}}</td>
                                <td>{{number_format($s->sales,0)}}</td>
                                <td>{{number_format($s->other_income,2)}}</td>
                                <td>{{number_format($s->expenses,2)}}</td>
                                <td>{{number_format($s->other_expenses,2)}}</td>
                                <td>{{number_format($s->cash_on_hand,2)}}</td>
                                {{-- <td>{{$s->submission_remarks}}</td>
                                <td>{{$s->submitter->name}}</td>
                                <td>{{$s->submitted_at}}</td>
                                <td>{{$s->approver_remarks}}</td> --}}
                                <td>{{$s->status}}</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>



      
            </div>
        </div>
    </div>
</div>

@include("sales.daily_report.create")

@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')

<script>
    $('#fixed-header1').DataTable({
      bAutoWidth: true,
  });


$(function () {
    var start = moment();
    var end = moment();

    $('#report_date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        maxDate: end,
        autoUpdateInput: true,
        locale: {
            format: 'DD-M-YYYY'
        }
    });
});



</script>

@endpush