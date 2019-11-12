<!DOCTYPE html>
<html>
<head>
    <title>Stock Transfer Status Report</title>

    <style>

        @page {
            size: A4 landscape;
        }


        body {
            /*font-size: 23px;*/
            /*transform: rotate(-90deg);*/
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
            /*margin-top: -10%;*/
        }

        #table-detail-main {
            width: 102%;
            margin-top: -10%;
            margin-bottom: 1%;
            border-collapse: collapse;
        }

        #table-detail tr > {
            line-height: 13px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #category {
            text-transform: uppercase;
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
<h2 align="center" style="margin-top: -2%">Stock Transfer Status Report</h2>
<h4 align="center" style="margin-top: -2%">
    @if($data[0]->status == 2)
        Completed Transfers
    @else
        Pending Transfers
    @endif
</h4>
<div class="row" style="margin-top: 10%;">
    <div class="col-md-12">
        <table id="table-detail-main">
            <tr>
                <td style="background: #1f273b; color: white"><b>From
                        Date:</b> {{date('d-m-Y',strtotime($data[0]['from']))}}
                </td>
                <td style="background: #1f273b; color: white"><b>To
                        Date:</b> {{date('d-m-Y',strtotime($data[0]['to']))}}
                </td>
            </tr>
        </table>
        <table id="table-detail" align="center">
            <!-- loop the product names here -->
            <thead>
            <tr style="background: #1f273b; color: white; font-size: 0.9em">
                <th>Code</th>
                <th>Product Name</th>
                <th>Transfer No</th>
                <th>Transferred Qty</th>
                <th>Accepted Qty</th>
                <th>From</th>
                <th>To</th>
                <th>Date</th>
            </tr>
            </thead>
            @foreach($data as $item)
                <tr>
                    <td>{{$item->currentStock['product']['id']}}</td>
                    <td>{{$item->currentStock['product']['name']}}</td>
                    <td align="">{{$item->transfer_no}}</td>
                    <td align="right">
                        <div style="margin-right: 50%">{{floatval($item->transfer_qty)}}</div>
                    </td>
                    <td align="right">
                        <div style="margin-right: 50%">{{floatval($item->accepted_qty)}}</div>
                    </td>
                    <td align="">{{$item->fromStore['name']}}</td>
                    <td align="">{{$item->toStore['name']}}</td>
                    <td align="" style="font-size: 0.8em">{{date('Y-m-d',strtotime($item->created_at))}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

<script type="text/php">
    if ( isset($pdf) ) {
        $x = 400;
        $y = 560;
        $text = "{PAGE_NUM} of {PAGE_COUNT} pages";
        $font = null;
        $size = 10;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);


     }




</script>

</body>
</html>

