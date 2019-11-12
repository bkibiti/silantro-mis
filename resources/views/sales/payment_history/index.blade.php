@extends("layouts.master")
@section('content-title')
  Payment History
@endsection
@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Sales Management / Payment History</a> </li>
@endsection

@section("content")

<div class="col-sm-12">
    <div class="card-block">
        <div class="col-sm-12">
                <div class="tab-content" id="myTabContent">
                     
                        <div class="table-responsive">
                            <table id="fixed-header" class="display table nowrap table-striped table-hover"style="width:100%">
                            <thead>
                                <tr>
                                   <th>Customer Name</th>
                                   <th>Payment Date</th>
                                   <th>Amount</th>
                                </tr>
                            </thead>
                                 <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                          <td>{{$payment->name}}</td>
                                          <td>{{date('j M, Y', strtotime($payment->created_at))}}</td>
                                          <td>{{number_format($payment->paid_amount,2)}}</td>
                                    @endforeach 
                                        </tr>
                                        </tbody>
                                </table>
                            </div>
                   
            </div>
    </div>                       
</div>
        

@endsection


@push("page_scripts")
@include('partials.notification')
@endpush
