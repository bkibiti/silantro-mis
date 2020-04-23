@extends("layouts.master")

@section('page_css')
    <style>
        #daily_sales {
            width: 100%;
            height: 500px;
        }

        #sale_by_day {
            width: 100%;
            height: 400px;
        }
        #sales_by_month {
            width: 100%;
            height: 400px;
        }

    </style>
@endsection





@section("content")

<div class="col-sm-12">

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"  data-toggle="pill" href="#pills-home" role="tab" aria-selected="true">Advanced Dashboard</a>
            </li>
    
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                 <!-- start row 1-->    
                <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="card Online-Order">
                                <div class="card-block">
                                    <h5>Purchases</h5>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">This Month<span class="float-right f-18 text-c-yellow">{{ number_format($purchaseThisMonth[0]->Amount, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Last Month<span class="float-right f-18 text-c-yellow">{{ number_format($purchaseLastMonth[0]->Amount, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">Average Daily Purchaces<span class="float-right f-18 text-c-yellow">{{ number_format($avgDailyPurchases, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Average Monthly Purchases<span class="float-right f-18 text-c-yellow">{{ number_format($avgDailyPurchases * 30,2) }}</span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="card Online-Order">
                                <div class="card-block">
                                    <h5>Sales</h5>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">This Month<span class="float-right f-18 text-c-purple">{{ number_format($salesThisMonth[0]->Amount, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Last Month<span class="float-right f-18 text-c-purple">{{ number_format($salesLastMonth[0]->Amount,2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">Average Daily Sales<span class="float-right f-18 text-c-purple">{{ number_format($avgDailySales, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Average Monthly Sales<span class="float-right f-18 text-c-purple">{{ number_format($avgDailySales * 30,2) }}</span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-4">
                            <div class="card Online-Order">
                                <div class="card-block">
                                    <h5>Expenses</h5>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">This Month<span class="float-right f-18 text-c-blue">{{ number_format($expensesThisMonth[0]->Amount, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Last Month<span class="float-right f-18 text-c-blue">{{ number_format($expensesLastMonth[0]->Amount, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">Average Daily Expenses<span class="float-right f-18 text-c-blue">{{ number_format($avgDailyExpenses, 2) }}</span></h6>
                                    <h6 class="text-muted d-flex align-items-center justify-content-between">Average Monthly Expenses<span class="float-right f-18 text-c-blue">{{ number_format($avgDailyExpenses * 30,2) }}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div> 
                     <!-- end row 1 -->

               <!-- start row 2-->    
               <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="card Online-Order">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4 col-xl-4">
                                    <h6 class="text-muted d-flex align-items-center justify-content-between ">Gross Profit This Month<span class="float-right f-18 text-c-green">{{ number_format($proftThisMonth[0]->Amount, 2) }}</span></h6>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <h6 class="text-muted d-flex align-items-center justify-content-between ">Gross Profit Last Month<span class="float-right f-18 text-c-green">{{ number_format($profitLastMonth[0]->Amount, 2) }}</span></h6>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <h6 class="text-muted d-flex align-items-center justify-content-between ">Average Daily Gross Profit<span class="float-right f-18 text-c-green">{{ number_format($avgDailyProfit, 2) }}</span></h6>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
             
            </div> 
             <!-- end row 12 -->

                     {{-- row 3 start --}}
                     <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <div id='sale_by_day'></div>
                        </div>
                      
                        <div class="col-md-6 col-xl-6">
                            <div id='sales_by_month'></div>
                        </div>
                    </div>

                    {{-- row 3 start --}}
                    <div class="row">
                        <div id='daily_sales'></div>
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
<script src="{{asset("assets/plugins/amcharts4/themes/dataviz.js")}}"></script>

<script>
    var title = document.title;
    document.title = title.concat(" | Advance Dashboard");
</script>

{{-- daily sales chart  --}}
<script>

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("daily_sales", am4charts.XYChart);

    // Add data
    chart.data = @json($totalDailySales);

    // Set input format for the dates
    chart.dateFormatter.inputDateFormat = "dd-MM-yyyy";

    // Create axes
    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    //title
    var title = chart.titles.create();
    title.text = "Daily Sales Last 30 Days";
    title.fontSize = 16;
    title.marginBottom = 15;

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "value";
    series.dataFields.dateX = "date";
    series.tooltipText = "{value}"
    series.strokeWidth = 2;
    series.minBulletDistance = 5;
    series.fillOpacity = 0.8;

    // Drop-shaped tooltips
    series.tooltip.background.cornerRadius = 20;
    series.tooltip.background.strokeOpacity = 0;
    series.tooltip.pointerOrientation = "vertical";
    series.tooltip.label.minWidth = 40;
    series.tooltip.label.minHeight = 40;
    series.tooltip.label.textAlign = "middle";
    series.tooltip.label.textValign = "middle";

    // Make bullets grow on hover
    var bullet = series.bullets.push(new am4charts.CircleBullet());
    bullet.circle.strokeWidth = 2;
    bullet.circle.radius = 4;
    bullet.circle.fill = am4core.color("#fff");

    var bullethover = bullet.states.create("hover");
    bullethover.properties.scale = 1.3;

    // Make a panning cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "panXY";
    chart.cursor.xAxis = dateAxis;
    chart.cursor.snapToSeries = series;

    // Create vertical scrollbar and place it before the value axis
    chart.scrollbarY = new am4core.Scrollbar();
    chart.scrollbarY.parent = chart.leftAxesContainer;
    chart.scrollbarY.toBack();

    chart.events.on("ready", function () {
        dateAxis.zoom({start:0.1, end:1});
    });

</script>


<!-- Sales by Day of a week -->
<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    
    var chart = am4core.create("sale_by_day", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; 
    
     //title
    var title = chart.titles.create();
        title.text = "Sales by Day of a Week";
        title.fontSize = 16;
        title.marginBottom = 15;
    
    chart.data = @json($saleByDay)
  
    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "Amount";
    series.dataFields.category = "DayName";
    
    }); // end
</script>

<!-- Sales by Month -->
<script>
    am4core.ready(function() {
    
    // Themes begin
    am4core.useTheme(am4themes_dataviz);
    
    var chart = am4core.create("sales_by_month", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; 
    
     //title
    var title = chart.titles.create();
        title.text = "Sales by Month";
        title.fontSize = 16;
        title.marginBottom = 15;
    
    chart.data = @json($saleByMonth)
  
    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "Amount";
    series.dataFields.category = "Month";
    
    }); // end
</script>


@endpush
