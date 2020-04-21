@extends("layouts.master")

@section('page_css')
<style>


</style>
@endsection

@section('content-title')
Total Monthly Sales Reports
@endsection


@section("content")

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">

            @include('reports.search')
    <hr>
            <div class="table-responsive">
                <table id="fixed-header1" class="display table nowrap table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td>{{$d->month}}</td>
                                <td>{{number_format($d->amount,2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
            
                </table>
            

            </div>

            <hr>
 
  
        </div>
    </div>
</div>


@endsection

@push("page_scripts")
<script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
<script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
@include('partials.notification')
@include('reports.hide_show_filters')

<script>

    $('#fixed-header1').DataTable({
      bAutoWidth: true,
      order: [[0, "desc"]]
    });


    $(function () {
        var start = moment();
        var end = moment();

        $('#from_date').daterangepicker({
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

        $('#to_date').daterangepicker({
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