<!DOCTYPE html>
<html>

<head>
    <title>Total Daily Sales Reports</title>

</head>

<body>
    <div class="row">
        <div id="container">
            <div class="logo-container">
                {{-- <img src="{{public_path('fileStore/logo/'.$pharmacy['logo'])}}" /> --}}
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: -2%">

        <h4 align="center">Total Daily Sales Reports</h4>


        <div class="row">

            <table width="100%" style="width:100%" border="1">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>{{date_format(new DateTime($d->date),'d M Y')}}</td>
                        <td>{{number_format($d->amount,2)}}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
   
    </div>



</body>

</html>