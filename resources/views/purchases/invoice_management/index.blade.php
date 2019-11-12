@extends("layouts.master")

@section('content-title')
    Invoices

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Invoice Management</a></li>
@endsection

@section("content")
    <style>
        .datepicker > .datepicker-days {
            display: block;
            margin-top: auto;
        }

        ol.linenums {
            margin: 0 0 0 -10px;
        }
    </style>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <div class="form-group row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <button style="float: right; margin-right: -0.3%" type="button" class="btn btn-secondary btn-sm"
                                data-toggle="modal"
                                data-target="#create">
                            Add New Invoice
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-3" style="margin-left: 2.5%">
                        <label style="margin-left: 74.5%" for=""
                               class="col-form-label text-md-right">Date:</label>
                    </div>
                    <div class="col-md-3" style="margin-left: -4.4%;">
                        <input style="width: 108%;" type="text" name="invoice_filter"
                               onchange="getInvoice()"
                               class="form-control" id="date_filter"/>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="invoice_data_table" class="display table nowrap table-striped table-hover"
                           style="width:100%">
                        <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Supplier</th>
                            <th>Invoice Date</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('purchases.invoice_management.create')
    @include('purchases.invoice_management.edit')
    @include('purchases.invoice_management.show')

@endsection

@push("page_scripts")
    @include('partials.notification')
    <script src="{{asset("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/ac-datepicker.js")}}"></script>
    <script src="{{asset("assets/apotek/js/notification.js")}}"></script>
    <script src="{{asset("assets/apotek/js/notification.js")}}"></script>

    <script>

        $('#invoice_data_table tbody').on('click', '#edit_btn', function () {
            var data = invoice_data_table.row($(this).parents('tr')).data();
            var index = invoice_data_table.row($(this).parents('tr')).index();

            $('#edit').modal('show');
            $('#edit').find('.modal-body #id_edit').val(data.id);
            $('#edit').find('.modal-body #number_edit').val(data.invoice_no);
            $('#edit').find('.modal-body #date_edit').val(data.invoice_date);
            $('#edit').find('.modal-body #supplier_edit').val(data.supplier_id);
            $('#edit').find('.modal-body #amount_edit').val(formatMoney(data.invoice_amount));
            $('#edit').find('.modal-body #amount_paid_edit').val(formatMoney(data.paid_amount));
            $('#edit').find('.modal-body #balance_edit').val(formatMoney(data.remain_balance));
            $('#edit').find('.modal-body #period_edit').val(data.grace_period);
            $('#edit').find('.modal-body #received_status_edit').val(data.received_status);
            $('#edit').find('.modal-body #due_date_edit').val(data.payment_due_date);
            $('#edit').find('.modal-body #remarks_edit').val(data.remarks);
        });

        $('#invoice_data_table tbody').on('click', '#dtl_btn', function () {
            var data = invoice_data_table.row($(this).parents('tr')).data();
            var index = invoice_data_table.row($(this).parents('tr')).index();

            $('#show').modal('show');
            $('#show').find('.modal-body #inv_no').val(data.invoice_no);
            $('#show').find('.modal-body #supplier').val(data.supplier.name);
            $('#show').find('.modal-body #inv_date').val(data.date);
            $('#show').find('.modal-body #amount').val(formatMoney(data.invoice_amount));
            $('#show').find('.modal-body #paid').val(formatMoney(data.paid_amount));
            $('#show').find('.modal-body #balance').val(formatMoney(data.remain_balance));
            $('#show').find('.modal-body #period').val(data.grace_period);
            $('#show').find('.modal-body #due').val(data.due_date);
            $('#show').find('.modal-body #status').val(data.received_status);
            $('#show').find('.modal-body #remarks').val(data.remarks);
        });
        $(document).ready(subtract());
        $(document).ready(editSubtract());
        $(document).ready(editdueDate());

        $('#d_auto').on('change', function () {
            setdueDate();
        });

        $('#period_id').on('change', function () {
            setdueDate();
        });

        function setdueDate() {

            var grace_period = Number(document.getElementById("period_id").value);
            var date_string = document.getElementById("d_auto").value;

            invoice_date = new Date(date_string);

            if (invoice_date.toString() === 'Invalid Date') {
                return false;
            }

            var payment_due_date = invoice_date.setDate(invoice_date.getDate() + grace_period);

            var month = Number(invoice_date.getMonth()) + 1;
            if (typeof month == 'number') {
                document.getElementById("due_d").value = invoice_date.getFullYear() + '-' + month + '-' + invoice_date.getDate();
            }

        }

        function isNumberKey(evt, obj) {

            var charCode = (evt.which) ? evt.which : event.keyCode;
            var value = obj.value;
            var dotcontains = value.indexOf(".") !== -1;
            if (dotcontains)
                if (charCode === 46) return false;
            if (charCode === 46) return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
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


        $(function () {
            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#date_filter span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#date_filter').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment()]
                }
            }, cb);

            cb(start, end);

        });

        function getInvoice() {

            var range = document.getElementById("date_filter").value;
            var date = range.split('-')
            $.ajax({
                url: '{{route('getInvoice')}}',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "date": date
                },
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    invoice_data_table.clear();
                    invoice_data_table.rows.add(data);
                    invoice_data_table.draw();
                },
            });

        }

        // render: function (invoice_date) {
        //     return moment(invoice_date).format('MMM DD,YYYY');
        // }

        var invoice_data_table = $('#invoice_data_table').DataTable({
            searching: true,
            bPaginate: true,
            bInfo: true,
            columns: [

                {data: 'invoice_no'},
                {data: 'supplier.name'},
                {
                    data: 'date'
                },
                {
                    data: 'invoice_amount', render: function (invoice_amount) {
                        return formatMoney(invoice_amount);
                    }
                },
                {
                    data: 'remain_balance', render: function (remain_balance) {
                        return formatMoney(remain_balance);
                    }
                },
                {
                    data: 'due_date'
                },

                {
                    data: "action",
                    defaultContent: "<input type='button' value='Show' id='dtl_btn' class='btn btn-success btn-rounded btn-sm' size='2'/><input type='button' value='Edit' id='edit_btn' class='btn btn-primary btn-rounded btn-sm' size='2' />"
                }
            ]
        });

        $('#invoice_form').on('submit', function () {
            /*check the dropdowns if are selected*/
            var supplier = document.getElementById('supplier');
            var supplier_id = supplier.options[supplier.selectedIndex].value;

            var period = document.getElementById('period_id');
            var period_id = period.options[period.selectedIndex].value;

            var status = document.getElementById('received_status');
            var status_id = status.options[status.selectedIndex].value;

            if (Number(supplier_id) === 0) {
                document.getElementById('supplier_warning').style.display = 'block';
                document.getElementById('period_warning').style.display = 'none';
                document.getElementById('status_warning').style.display = 'none';
                return false;
            } else if (Number(period_id) === 0) {
                document.getElementById('period_warning').style.display = 'block';
                document.getElementById('supplier_warning').style.display = 'none';
                document.getElementById('status_warning').style.display = 'none';
                return false;
            } else if (Number(status_id) === 0) {
                document.getElementById('status_warning').style.display = 'block';
                document.getElementById('supplier_warning').style.display = 'none';
                document.getElementById('period_warning').style.display = 'none';
                return false;
            }

        });

        $('#supplier').select2({
            dropdownParent: $('#create')
        });

        $("#period_id").select2({
            dropdownParent: $('#create')
        });

        $("#received_status").select2({
            dropdownParent: $('#create')
        });


    </script>

@endpush

