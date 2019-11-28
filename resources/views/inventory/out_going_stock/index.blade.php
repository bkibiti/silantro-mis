@extends("layouts.master")

@section('page_css')
    <style>


    </style>
@endsection

@section('content-title')
    Outgoing Stock
@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> Inventory Management / Outgoing Stock </a></li>
@endsection

@section("content")

    <style>
        .datepicker > .datepicker-days {
            display: block;
        }

        ol.linenums {
            margin: 0 0 0 -8px;
        }

        #loading {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: fixed;
            display: none;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
            text-align: center;
        }

        #loading-image {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 100;
        }

    </style>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="form-group row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-3" style="margin-left: 2.5%">
                            <label style="margin-left: 80%" for="issued_date"
                                   class="col-form-label text-md-right">Date:</label>
                        </div>
                        <div class="col-md-3" style="margin-left: -3.1%">
                            <input style="width: 103.4%;" type="text" name="outgoing-date"
                                   onchange="getOutgoingDate()"
                                   class="form-control" id="outgoing-date" value=""/>
                        </div>
                    </div>

                    <div id="tbody" class="table-responsive">
                        <table id="fixed-header" class="display table nowrap table-striped table-hover"
                               style="width:100%">

                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Out Mode</th>
                                <th>Quantity</th>
                                <th>User</th>
                                <th>Out Date</th>
                            </tr>
                            </thead>

                        </table>

                    </div>

                    <div id="tbodyRePrintFilter" style="display: none" class="table-responsive">
                        <table id="fixed-header-ledger" class="display table nowrap table-striped table-hover"
                               style="width:100%">

                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Out Mode</th>
                                <th>Quantity</th>
                                <th>User</th>
                                <th>Out Date</th>
                            </tr>
                            </thead>
                            <tbody>

                        </table>

                    </div>


                    <div id="loading">
                        <image id="loading-image" src="{{asset('assets/images/spinner.gif')}}"></image>
                    </div>

                </div>
            </div>
        </div>

        @endsection




        @push("page_scripts")
            <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
            <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>

            @include('partials.notification')


            <script type="text/javascript">
                var config = {
                    routes: {
                        ledgerShow: '{{ route('outgoing-stock-show') }}'
                    }
                };

            </script>

            <script src="{{asset("assets/apotek/js/outgoing-stock.js")}}"></script>


    @endpush
