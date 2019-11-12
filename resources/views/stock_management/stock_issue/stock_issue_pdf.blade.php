<!DOCTYPE html>
<html>
<head>
    <title>Stock Issue</title>

    <style>

        body {
            /*font-size: 30px;*/
        }

        table, th, td {
            border-collapse: collapse;
            padding: 10px;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }

        #table-detail {
            border-collapse: collapse;
            width: 100%;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #receiver-sign {
            top: 10%;
        }

        #sender-sign {
            margin-top: 0%;
        }

        .topcorner {
            position: absolute;
            top: 0;
            right: 0;
            margin-top: -4%;
            margin-left: 78%;
        }

        .topcorner > p {
            font-size: 10px;
        }

        h3 {
            font-weight: normal;
        }

        h4 {
            font-weight: normal;
        }


        #table-detail-top {
            border-collapse: collapse;
            width: 50%;
            margin-top: 1%;
        }

        #table-detail-total {
            margin-top: 1%;
            width: 50%;
            margin-right: 20%;
        }

    </style>

</head>
<body>

<h4 align="center">{{$pharmacy['name']}}</h4>
<h3 align="center" style="margin-top: -2%">{{$pharmacy['address']}}</h3>
<h2 align="center" style="margin-top: -2%">Stock Issue</h2>

{{--<div class="topcorner">--}}
{{--    <p>{{$data[0]['issue_no']}}</p>--}}
{{--</div>--}}

<div class="row">
    <div class="col-md-12">

        <table id="table-detail-top">
            <tr style="background: #f2f2f2; color: black; font-size: 0.9em">
                <th>Issue No</th>
                <td>{{$data[0]['issue_no']}}</td>
            </tr>
            <tr style="background: white; color: black; font-size: 0.9em">
                <th>Date</th>
                <td>{{date('d-m-Y', strtotime($data[0]['created_at']))}}</td>
            </tr>
            <tr style="background: #f2f2f2; color: black; font-size: 0.9em">
                <th>Issue To</th>
                <td>{{$data[0]->issueLocation['name']}}</td>
            </tr>
        </table>

        <table id="table-detail" align="center">
            <thead>
            <tr style="background: #1f273b; color: white; font-size: 0.9em">
                <th>Product Name</th>
                <th>Quantity Issued</th>
                <th>Sub Total</th>
            </tr>
            </thead>
            <!-- loop the product names here -->
            @foreach($data as $issue)
                <tr style="align-items: center">
                    <td>{{ $issue->currentStock['product']['name'] }}</td>
                    <td align="right">
                        <div style="margin-right: 50%">{{ floatval($issue->quantity) }}</div>
                    </td>
                    <td align="right">{{number_format($issue->sub_total)}}</td>
                </tr>
            @endforeach

        </table>

        <div style="margin-left: 70%;width: 29.6%;background: #f2f2f2;margin-top: 2%; padding: 1%"><b>Total: </b>
        </div>
        <div align="right"
             style="margin-top: -10%; padding-top: 1%; padding-left: 1%">{{number_format(session('issue_total'))}}</div>


    </div>
</div>

</body>
</html>

