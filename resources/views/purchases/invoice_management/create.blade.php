<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="invoice_form" action="{{ route('invoice-management.store') }}" method="post">
                        @csrf()
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="code">Invoice Number</label><font color="red">*</font>
                                        <input type="text" class="form-control" id="invoice_number"
                                               name="invoice_number" aria-describedby="emailHelp"
                                               required="true">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="code">Invoice Date</label><font color="red">*</font>
                                        <input type="text" class="form-control" id="d_auto" name="invoice_date"
                                               aria-describedby="emailHelp"
                                               autocomplete="off" required="true">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="supplier">Supplier Name</label><font color="red">*</font>
                                        <select name="supplier" class="form-control" id="supplier" required="true"
                                                required="true">
                                            <option selected="true" value="0" disabled="disabled">Select Supplier...
                                            </option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="supplier_warning" style="display: none; color: red; font-size: 0.9em">supplier required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Amount">Invoice Amount</label><font color="red">*</font>
                                        <input type="text" class="form-control" id="amount_id" name="invoice_amount"
                                               aria-describedby="emailHelp" onchange="subtract()"
                                               required="true" onkeypress="return isNumberKey(event,this)">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Amount">Amount Paid</label><font color="red">*</font>
                                        <input type="text" class="form-control" id="amount_paid_id" name="paid_amount"
                                               aria-describedby="emailHelp"
                                               onchange="subtract()"
                                               required="true">
                                        <span class="help-inline" onkeypress="return isNumberKey(event,this)">
                                        </span>
                                        <div class="text text-danger" id="amount_error"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Amount">Remain Balance</label>
                                        <input type="text" class="form-control" id="balance_id" name="balance"
                                               aria-describedby="emailHelp" readonly value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Amount">Grace Period (In Days)</label><font color="red">*</font>
                                        <select type="number" class="form-control" id="period_id" name="grace_period"
                                                aria-describedby="emailHelp"
                                                required="true">
                                            <option selected="true" value="0" disabled="disabled">Select Period...
                                            </option>
                                            <option value="1">1</option>
                                            <option value="7">7</option>
                                            <option value="14">14</option>
                                            <option value="21">21</option>
                                            <option value="28">28</option>
                                            <option value="30">30</option>
                                            <option value="60">60</option>
                                            <option value="60">90</option>
                                            <option value="28">28</option>
                                        </select>
                                        <span id="period_warning" style="display: none; color: red; font-size: 0.9em">period required</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Payment Due Date</label>
                                        <input type="text" name="payment_due_date" class="form-control" id="due_d"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="received_status">Received Status</label><font color="red">*</font>
                                        <select name="received_status" id="received_status" class="form-control">
                                            <option selected="true" value="" disabled="disabled">Select Status...
                                            </option>
                                            <option>All Received</option>
                                            <option>Partial Received</option>
                                        </select>
                                        <span id="status_warning" style="display: none; color: red; font-size: 0.9em">status required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="code">Remarks</label>
                                        <textarea name="remarks" id="remarks_id" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //validating amount paid and invoice amount
    function subtract() {

        var invoice_amounts = document.getElementById("amount_id").value;
        var paid_amounts = document.getElementById("amount_paid_id").value;
        var paid_amounts_format = formatMoney(parseFloat(paid_amounts.replace(/\,/g, ''), 10));
        var invoice_amounts_format = formatMoney(parseFloat(invoice_amounts.replace(/\,/g, ''), 10));
        document.getElementById("amount_id").value = invoice_amounts_format;
        document.getElementById("amount_paid_id").value = paid_amounts_format;
        var grace_period = document.getElementById('period_id').value;

        var paid_amount = parseFloat(paid_amounts_format.replace(/\,/g, ''), 10);
        var invoice_amount = parseFloat(invoice_amounts_format.replace(/\,/g, ''), 10);

        if ((Number(invoice_amount) >= Number(paid_amount)) && (Number(invoice_amount) && Number(paid_amount) !== Number(0))) {

            //not zero then sub
            var remain_amount = invoice_amount - paid_amount;
            document.getElementById("balance_id").value = formatMoney(remain_amount);
            document.getElementById('amount_error').style.display = 'none';

        } else if ((Number(invoice_amount) < Number(paid_amount)) && (Number(invoice_amount) && Number(paid_amount) !== Number(0))) {

            //do no substract
            document.getElementById("amount_paid_id").value = 0;
            document.getElementById('amount_error').style.display = 'block';
            $('#create').find('.modal-body #amount_error').text('Cannot exceed invoice amount');
        }

    }

</script>
