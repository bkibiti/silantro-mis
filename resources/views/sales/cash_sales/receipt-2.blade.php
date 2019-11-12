<!DOCTYPE html>
<html>
<head>
<title>Receipt</title>
<style>

* {
font-family: Verdana, Arial, sans-serif;
}
table, th, td {
border: 1px solid black;
border-collapse: collapse;
padding: 10px;
font-size: x-small;
}
#table-detail {
border-spacing: 6%;
width: 96%;
margin-top: -13%;
border: none;
}
.header img {
float: left;
width: 100px;
height: 100px;
}
.logo-centre {
display: block;
margin-left: auto;
margin-right: auto;
width: 50%;
}

</style>
</head>
<body>
<div class="header logo-centre">
<img  src="{{public_path('fileStore/logo/'.$pharmacy[0]->logo)}}" alt="logo"/>
<h3 align="center" >{{$pharmacy[0]->business_name}}</h3>
<h6 align="center" style="margin-top: -2%">{{$pharmacy[0]->address}}</h6>
<h3 align="center">RECEIPT</h3>
</div>



<div class="row" style="margin-top: 10%;">
<div class="col-md-12">
<table class="table table-sm" id="table-detail" align="center">
@foreach($data as $datas => $dat)
<tr>
<td style="padding-bottom: 4%; border: none;" colspan="8" id="category">
</td>
</tr>
<tr>
<td   id="category"><b>Sale Date</b></td>
<td   id="category">{{date('j M, Y', strtotime($dat[0]['created_at']))}}</td>
<td style="border: none; " colspan="4" id="category"></td>
<td   id="category"><b>Sold By</b></td>
<td   id="category">{{$dat[0]['sold_by']}}</td>
</tr>
<tr>
<td   id="category"><b>Receipt</b></td>
<td   id="category">{{$datas}}</td>
<td style="border: none;" colspan="4" id="category"></td>
<td   id="category"><b>TIN</b></td>
<td   id="category">{{$pharmacy[0]->tin_number}}</td>
</tr>

<tr>
<td style="padding-bottom: 4%; border: none;" colspan="8" id="category">
</td>
</tr>
<tr>
<th align="left">SN</th>
<th align="left">Product Name</th>
<th align="right">Quantity</th>
<th align="right">Price</th>
<th align="right">Sub Total</th>
<th align="right">Discount</th>
<th align="right">VAT</th>
<th align="right">Amount</th>
</tr>
@foreach($dat as $item)
<tr> <td align="left">{{11}}</td>
<td align="left">{{$item['name']}}</td>
<td align="right">{{$item['quantity']}}</td>
<td align="right">{{number_format($item['price']/$item['quantity'],2)}}</td>
<td align="right">{{number_format($item['sub_total'],2)}}</td>
<td align="right">{{number_format($item['discount'],2)}}</td>
<td align="right">{{number_format($item['vat'],2)}}</td>
<td align="right">{{number_format($item['amount'],2)}}</td>
</tr>
@endforeach
<tr>
<td style="padding-bottom: 4%; border: none;" colspan="8" id="category">
</td>
</tr>
<tr>
<td style="border: none;" colspan="6" id="category"></td>
<td align="left"><b>Sub Total</b></td>
<td align="right"><b>{{number_format(($dat[0]['grand_total']-$dat[0]['total_vat']),2)}}</b></td>
</tr>
<tr>
<td style="border: none;" colspan="6" id="category"></td>
<td><b>VAT</b></td>
<td align="right"><b>{{number_format($dat[0]['total_vat'],2)}}</b></td>
</tr>
<tr>
<td style="border: none;" colspan="6" id="category"></td>
<td ><b>Total</b></td>
<td align="right"><b>{{number_format($dat[0]['grand_total'],2)}}</b></td>
</tr>


@endforeach

</table>
</div>
</div>

</body>
</html>

