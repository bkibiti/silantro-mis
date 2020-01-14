<div class="modal fade" id="create" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                    @endif
                    <form id="expense_form" action="{{route('sales.daily-generate')}}" method="post">
                        @csrf()

                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="expense_date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <div id="date" style="border: 2px solid white; border-radius: 6px;">
                                        <input type="text" name="report_date" class="form-control" id="report_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                     
                            <div class="form-group row">
                                <label for="expense_amount" class="col-md-4 col-form-label text-md-right">{{ __('Other Income
                                    ') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" id="other_income" class="form-control" name="other_income" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expense_amount" class="col-md-4 col-form-label text-md-right">{{ __('Other Expenses
                                    ') }}<span style="color: red;">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" id="other_expenses" class="form-control" name="other_expenses" required>
                                </div>
                            </div>
                 
                            <div class="form-group row">
                                <label for="expense_description"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Submission Remarks') }}</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="submission_remarks" rows="3" required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>