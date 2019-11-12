<!DOCTYPE html>
<html>
<head>
    <title>Daily Stock Count</title>


    <style>

        body {
            font-size: 170%;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        #table-detail {
            border-spacing: 5px;
            width: 100%;
            margin-top: -1%;
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
            font-size: 10;
        }

    </style>


</head>
<body>

<h1 align="center">Daily Stock Count</h1>

<div class="row" style="margin-top: 10%">
    <div class="col-md-12">
        <table id="table-detail" align="center">
            <tr>
                <th>Product Name</th>
                <th>Sold Quantity</th>
                <th>Quantity On Hand</th>
            </tr>
            <!-- loop the product names here -->
            @foreach($new_data as $data)
                <tr>
                    <td>{{ $data['product_name']}}</td>
                    <td>{{ $data['quantity_sold']}}</td>
                    <td>{{ $data['quantity_on_hand']}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>


</body>
</html>

