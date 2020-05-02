@extends("layouts.master")

@section('page_css')
<style>
    #daily_sales {
        width: 100%;
        height: 500px;
    }

    #staff_loss {
        width: 100%;
        height: 400px;
    }

    #sales_by_user {
        width: 100%;
        height: 400px;
    }
</style>
@endsection





@section("content")

<div class="col-sm-12">

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#pills-home" role="tab"
                aria-selected="true">Dashboard</a>
        </li>

    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

            {{-- <div class="row">

                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row align-items-center justify-content-center">
                                <div class="col">
                                    <h3 class="text-c-red">Out of stock - {{ outofstock()}} Item(s)</h3>
                                    <a href="{{route('out-of-stock')}}" class="badge badge-info">View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <div class="row align-items-center justify-content-center">
                                <div class="col">
                                    <h3 class="text-c-green">Below minimum level - {{ belowMin()}} Item(s) </h3>
                                    <a href="{{route('below-min-level')}}" class="badge badge-info">View</a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}
            <div class="row">
                <div class="col-md-12 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Out of Stock</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ outofstock()}}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                <div class="col-4 text-right">
                                    <h5 class="text-c-red f-w-400">{{ number_format(outofstock()/stockItems()*100,1) }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Below Minimum</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ belowMin()}}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                <div class="col-4 text-right">
                                    <h5 class="text-c-green f-w-400">{{ number_format(belowMin()/stockitems()*100,1) }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-block border-bottom">
                            <h5 class="m-0">Total Stock</h5>
                        </div>
                        <div class="card-block">
                            <div class="row  align-items-center">
                                <div class="col-8">
                                    <h2 class=" m-0">{{ stockItems() }}</h2>
                                    <span class="text-muted">Items</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- row 3 start --}}
            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div id='staff_loss'></div>
                </div>
                <div class="col-md-6 col-xl-6">
                    <div id='sales_by_user'></div>
                </div>
            </div>


        </div>
        {{-- /Tab 1 --}}



    </div>
</div>


@endsection



@push("page_scripts")

<script src="{{asset("assets/plugins/amcharts4/core.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/charts.js")}}"></script>
<script src="{{asset("assets/plugins/amcharts4/themes/animated.js")}}"></script>

<script>
    var title = document.title;
    document.title = title.concat(" | Home");
</script>

<!-- Losses by user -->
<script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);

        // Create chart instance
        var chart = am4core.create("staff_loss", am4charts.XYChart);

        // Add data
        chart.data = @json($staffLoss);

        //title
        var title = chart.titles.create();
            title.text = "Losses by Staff this Month";
            title.fontSize = 16;
            title.marginBottom = 15;
        // Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "user";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;
        categoryAxis.renderer.labels.template.horizontalCenter = "right";
        categoryAxis.renderer.labels.template.verticalCenter = "middle";
        categoryAxis.renderer.labels.template.rotation = 270;
        categoryAxis.tooltip.disabled = true;
        categoryAxis.renderer.minHeight = 110;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.minWidth = 50;

        // Create series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.sequencedInterpolation = true;
        series.dataFields.valueY = "amount";
        series.dataFields.categoryX = "user";
        series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
        series.columns.template.strokeWidth = 0;

        series.tooltip.pointerOrientation = "vertical";

        series.columns.template.column.cornerRadiusTopLeft = 10;
        series.columns.template.column.cornerRadiusTopRight = 10;
        series.columns.template.column.fillOpacity = 0.8;

        // on hover, make corner radiuses bigger
        var hoverState = series.columns.template.column.states.create("hover");
        hoverState.properties.cornerRadiusTopLeft = 0;
        hoverState.properties.cornerRadiusTopRight = 0;
        hoverState.properties.fillOpacity = 1;

        series.columns.template.adapter.add("fill", function(fill, target) {
        return chart.colors.getIndex(target.dataItem.index);
        });

        // Cursor
        chart.cursor = new am4charts.XYCursor();




        }); 
</script>

<!-- Sales by Users -->

<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
    
    // Create chart
     var chart = am4core.create("sales_by_user", am4charts.PieChart);
     chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

        chart.data = @json($salesByUser);
      //title
      var title = chart.titles.create();
            title.text = "Total Sales By Staff this Month";
            title.fontSize = 16;
            title.marginBottom = 15;

    
    var series = chart.series.push(new am4charts.PieSeries());
    series.dataFields.value = "amount";
    series.dataFields.radiusValue = "amount";
    series.dataFields.category = "user";
    series.slices.template.cornerRadius = 6;
    series.colors.step = 3;
    
    series.hiddenState.properties.endAngle = 90;
    

    
    }); // end am4core.ready()
</script>

@endpush