<!DOCTYPE html>
<html>

<head>
    <title>Stock Count Sheet</title>

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

        <h2 align="center">Stock Count Sheet</h2>


        <div class="row">

            <table width="100%" style="width:100%" border="1" Cellspacing="0">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>QOH</th>
                        <th>Physical Count</th>
                        <th>Difference</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0 ?>
                    @foreach($products as $product)
                         <?php $i++ ?>

                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->category->name}}</td>
                            <td>{{$product->quantity}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
   
    </div>



</body>

</html>