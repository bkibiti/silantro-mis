<!DOCTYPE html>
<html>
<head>
    <title>Inventory Count Sheet</title>


    <style>

        body {
            /*font-size: 30px;*/
        }

        table, th, td {
            /*border: 1px solid black;*/
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
            /*border-spacing: 5px;*/
            width: 100%;
        }

        #table-top-detail {
            /*border-spacing: 5px;*/
            width: 100%;
            margin-top: -10%;
            margin-bottom: 3%;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table-info {
            width: 50%;
            border-spacing: 5px;
        }

        .tab {
            display: inline-block;
            margin-left: 20px;
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

    </style>


</head>
<body>

<h4 align="center">{{$pharmacy['name']}}</h4>
<h3 align="center" style="margin-top: -2%">{{$pharmacy['address']}}</h3>
<h2 align="center" style="margin-top: -2%">Inventory Count Sheet</h2>

<div class="row" style="margin-top: 10%">
    <div class="col-md-12">

        <table id="table-top-detail" align="center">
            <thead>
            <tr style="background: #1f273b; color: white; font-size: 0.9em">
                <th>Date</th>
                <th>Store</th>
                <th>Performed By</th>
            </tr>
            <tr style="height:20px;">
                <td>{{date('d-m-Y')}}</td>
                <td>{{$data[0]['store']}}</td>
                <td>{{Auth::user()->name}}</td>
            </tr>
            </thead>
        </table>

        <table id="table-detail" align="center">
            <thead>
            <tr style="background: #1f273b; color: white; font-size: 0.9em">
                <th>Code</th>
                <th>Product Name</th>
                <th>Shelf No</th>
                <th>QOH</th>
                <th>Physical</th>
            </tr>
            </thead>
            <!-- loop the product names here -->
            @foreach($data as $stock)
                <tr>
                    <td>{{$stock['product_id']}}</td>
                    <td>{{$stock['product_name']}}</td>
                    <td>{{$stock['shelf_no']}}</td>
                    <td align="right">
                        <div style="margin-right: 50%">{{$stock['quantity_on_hand']}}</div>
                    </td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>


</body>
</html>

