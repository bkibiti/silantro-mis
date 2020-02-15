<form action="{{route('sales-reports.getreport')}}" method="get">
    @csrf()

    <div class="form-group row">
        <div class="col-md-4">
            <div style="border: 2px solid white; border-radius: 6px;">
                <select name="report" class="form-control" required>
                    <option >Select Report</option>
                    <option value="1" >Total Daily Sales</option>
                    <option value="2" >Total Monthly Sales</option>
                    <option value="3" >Total Monthly Sales</option>


                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div style="border: 2px solid white; border-radius: 6px;">
                <input type="text" name="from_date" class="form-control" id="from_date" >
            </div> 
        </div>
        <div class="col-md-2">
            <div style="border: 2px solid white; border-radius: 6px;">
                <input type="text" name="to_date" class="form-control" id="to_date" >
            </div>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success">View Report</button>
        </div>
       

    </div>

</form>