@section('title')
    Analytics
@endsection
@extends('layouts.main')
@section('style')
    <link href="{{ asset('/assets/plugins/footable/css/footable.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .chartdiv {
            width: 100%;
            height: 400px;
        }

        hr {
            border: 0;
            clear: both;
            display: block;
            width: 100%;
            background-color: #E9E9EA;
            opacity: 0.5;
            height: 1px;
            margin-top: 0.4rem !important;
            margin-bottom: 0.4rem !important;
        }

        .container-fluid {
            width: 100% !important;
        }

        .btn-secondary {
            background: #8CB63D !important;
            border: #8CB63D !important
        }
    </style>
@endsection
@section('rightbar-content')
    <!-- Start Contentbar -->
    <div class="contentbar">

        <div class="col-md-12 col-lg-12 col-xl-12" style="margin-top:50px">
            <div class="card  m-b-30">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <span>
                                <h4 style="font-weight:1000;">{{ $server->name }}<i
                                        class="feather icon-arrow-right"></i>{{ $client->name }}</h4>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <a href="#">
                                <h4 style="font-weight:1000; float:right"><i class="feather icon-refresh-ccw mr-3"></i></h4>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-2 col-md-2">
                <div class="card m-b-30">

                    <div class="card-body">
                        <div class="row" style="margin-right:10px">
                            <div class="col-lg-12 col-md-12">
                                <h5><strong style="color:black">{{ $server->name }}</strong></h5>

                                {{-- <strong style="font-size:15px;font-weight:300">{{ $server->type }}</strong> --}}

                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">System</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $server->platform }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">Machine Name</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $server->machine_name }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">IP address</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $server->ipaddress }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">Location</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $server->location }}</strong>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="card m-b-30">

                    <div class="card-body">
                        <div class="row" style="margin-right:10px">
                            <div class="col-lg-12 col-md-12">
                                <h5><strong style="color:black">{{ $client->name }}</strong></h5>

                                {{-- <strong style="font-size:15px;font-weight:300">{{ $client->type }}</strong> --}}

                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">System</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $client->platform }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">Machine Name</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $client->machine_name }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">IP address</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $client->ipaddress }}</strong>
                            </div>
                            <hr>
                            <div class="col-lg-12 col-md-12">
                                <strong style="color:black;font-size:18px;font-weight:600">Location</strong>
                                <p>
                                    <strong style="font-size:15px;font-weight:300">{{ $client->location }}</strong>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="card m-b-30">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table foo-pagination-table" data-paging="true" data-paging-size="2">
                                <thead>
                                    <tr>
                                        <th>
                                            <h6>Round Trip Time (ms)</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
                                        </th>
                                        <th>
                                            <h6>Uplink Time (ms)</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
                                        </th>
                                        <th>
                                            <h6>Downlink Time (ms)</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
                                        </th>
                                        <th>
                                            <h6>Jitter</h6>
                                            <p style="font-weight:100">Min/Max</p>
                                        </th>
                                        <th>
                                            <h6>Packet</h6>
                                            <p style="font-weight:100">Loss</p>
                                        </th>
                                        <th>
                                            <h6>Reordering</h6>
                                            <p style="font-weight:100">TX/RX</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($analytic as $an)
                                        @if($profile->max_rtt < $an->avg_rtt)
                                        <tr style="background-color:#ff000040">
                                        @else
                                        <tr style="background-color:#91cb9173">
                                        @endif
                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">
                                                    {{ round($an->avg_rtt, 1) }}</p>
                                                <p style="font-weight:100; margin-top:-8px !important;font-size:13px">
                                                    {{ round($an->min_rtt, 1) }} /
                                                    {{ round($an->max_rtt, 1) }}
                                                    / {{ round($an->st_rtt, 1) }}</p>

                                            </td>
                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">
                                                    {{ round($an->avg_up, 1) }}</p>
                                                <p style="font-weight:100; margin-top:-8px !important;font-size:13px">
                                                    {{ round($an->min_up, 1) }} /
                                                    {{ round($an->max_up, 1) }}
                                                    / {{ round($an->st_up, 1) }}</p>

                                            </td>
                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">
                                                    {{ round($an->avg_down, 1) }}</p>
                                                <p style="font-weight:100; margin-top:-8px !important;font-size:13px">
                                                    {{ round($an->min_down, 1) }} /
                                                    {{ round($an->max_down, 1) }}
                                                    / {{ round($an->st_down, 1) }}</p>

                                            </td>
                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">
                                                    {{ round($an->avg_jitter, 1) }}</p>
                                                <p style="font-weight:100; margin-top:-8px !important;font-size:13px">
                                                    {{ round($an->min_jitter, 1) }} /
                                                    {{ round($an->max_jitter, 1) }}

                                            </td>

                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">
                                                    {{ $an->t_packets - $an->r_packets }}</p>

                                            </td>
                                            <td>
                                                <p style="font-weight:100; color:black; font-size:15px">0</p>
                                                <p style="font-weight:100; margin-top:-8px !important;font-size:13px">0 / 0
                                                </p>

                                            </td>

                                        </tr>
                                    @endforeach

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="card m-b-30">

                    <div class="card-body">
                        <h4>Round Trip</h4>
                        <div class="chartdiv" id="c1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card m-b-30">

                    <div class="card-body">
                        <h4>Uplink</h4>
                        <div class="chartdiv" id="c2"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card m-b-30">

                    <div class="card-body">
                        <h4>Downlink</h4>
                        <div class="chartdiv" id="c3"></div>
                    </div>
                </div>
            </div>


        </div>

    </div>


    <!-- Start row -->

    <!-- End row -->
    </div>
    <!-- End Contentbar -->
@endsection
@section('script')
    {{-- <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script> --}}
    <script src="{{ asset('/assets/plugins/footable/js/footable.min.js') }}"></script>
    <script src="{{ asset('/assets/js/custom/custom-table-footable.js') }}"></script>



    <script src="{{ asset('/assets/plugins/map/index.js') }}"></script>
    <script src="{{ asset('/assets/plugins/map/xy.js') }}"></script>
    <script src="{{ asset('/assets/plugins/map/themes/Animated.js') }}"></script>


    <script>
        var root = am5.Root.new("c1");
        var root1 = am5.Root.new("c2");
        var root2 = am5.Root.new("c3");


        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        root1.setThemes([
            am5themes_Animated.new(root1)
        ]);

        root2.setThemes([
            am5themes_Animated.new(root1)
        ]);



        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX"
        }));
        var chart1 = root1.container.children.push(am5xy.XYChart.new(root1, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX"
        }));

        var chart2 = root2.container.children.push(am5xy.XYChart.new(root2, {
            panX: false,
            panY: false,
            wheelX: "panX",
            wheelY: "zoomX"
        }));



        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "zoomX"
        }));
        var cursor1 = chart1.set("cursor", am5xy.XYCursor.new(root1, {
            behavior: "zoomX"
        }));
        var cursor2 = chart2.set("cursor", am5xy.XYCursor.new(root2, {
            behavior: "zoomX"
        }));



        cursor.lineY.set("visible", false);
        cursor1.lineY.set("visible", false);
        cursor2.lineY.set("visible", false);

        var date = new Date();
        date.setHours(0, 0, 0, 0);
        var value = 100;

        function generateData() {
            value = Math.round((Math.random() * 10 - 5) + value);
            am5.time.add(date, "day", 1);
            return {
                date: date.getTime(),
                value: value
            };
        }

        function generateDatas(count) {
            var data = [];
            for (var i = 0; i < count; ++i) {
                data.push(generateData());
            }
            return data;
        }


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0,
            baseInterval: {
                timeUnit: "minute",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {
                minGridDistance: 60
            }),
            tooltip: am5.Tooltip.new(root, {})
        }));


        var xAxis1 = chart1.xAxes.push(am5xy.DateAxis.new(root1, {
            maxDeviation: 0,
            baseInterval: {
                timeUnit: "minute",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root1, {
                minGridDistance: 60
            }),
            tooltip: am5.Tooltip.new(root1, {})
        }));
        var xAxis2 = chart2.xAxes.push(am5xy.DateAxis.new(root2, {
            maxDeviation: 0,
            baseInterval: {
                timeUnit: "minute",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root2, {
                minGridDistance: 60
            }),
            tooltip: am5.Tooltip.new(root2, {})
        }));



        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            renderer: am5xy.AxisRendererY.new(root, {

            })
        }));
        var yAxis1 = chart1.yAxes.push(am5xy.ValueAxis.new(root1, {
            renderer: am5xy.AxisRendererY.new(root1, {

            })
        }));

        var yAxis2 = chart2.yAxes.push(am5xy.ValueAxis.new(root2, {
            renderer: am5xy.AxisRendererY.new(root2, {

            })
        }));



        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series",
            fill: '#FF7E22',
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        var series1 = chart1.series.push(am5xy.ColumnSeries.new(root1, {
            name: "Series",
            fill: '#FFC104',
            xAxis: xAxis1,
            yAxis: yAxis1,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root1, {
                labelText: "{valueY}"
            })
        }));


        var series2 = chart2.series.push(am5xy.ColumnSeries.new(root2, {
            name: "Series",
            fill: '#00CEC9',
            xAxis: xAxis2,
            yAxis: yAxis2,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root2, {
                labelText: "{valueY}"
            })
        }));





        series.columns.template.setAll({
            strokeOpacity: 0
        })

        series1.columns.template.setAll({
            strokeOpacity: 0
        })

        series2.columns.template.setAll({
            strokeOpacity: 0
        })



        // Add scrollbar
        // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
        chart.set("scrollbarX", am5.Scrollbar.new(root, {
            orientation: "horizontal"
        }));
        chart1.set("scrollbarX", am5.Scrollbar.new(root1, {
            orientation: "horizontal"
        }));
        chart2.set("scrollbarX", am5.Scrollbar.new(root2, {
            orientation: "horizontal"
        }));


        var rtt = {!! json_encode($rtt, JSON_NUMERIC_CHECK) !!};
        var up = {!! json_encode($up, JSON_NUMERIC_CHECK) !!};
        var down = {!! json_encode($down, JSON_NUMERIC_CHECK) !!};

        
        series.data.setAll(rtt);
        series1.data.setAll(up);
        series2.data.setAll(down);


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(2000);
        chart.appear(1000, 100);

        series1.appear(1000);
        chart1.appear(1000, 100);

        series2.appear(1000);
        chart2.appear(1000, 100);
    </script>
@endsection
