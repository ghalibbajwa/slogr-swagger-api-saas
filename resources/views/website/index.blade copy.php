@section('title')
    Home
@endsection
@extends('layouts.main')
@section('style')
    <style>
        #chartdiv {

            height: 92.5vh;
        }

        .card-body {


            overflow-y: auto !important;
            max-height: 350px !important;
        }
    </style>
@endsection
@section('rightbar-content')
    <!-- Start Contentbar -->
    <div class="contentbar">
        <div class="col-lg-2 col-md-4 col-sm-4 col-xl-2" style="position:absolute; z-index:99; margin-top:30px;">
            <div class="row">
                <div class="card success m-b-30" style="box-shadow: 1px 12px 15px 2px;">

                    <div class="card-body" style="color:black">
                        <div class="col-lg-12" style="margin-top:5px">
                            <h4>Legend</h4>
                        </div>
                        <hr>
                        <br>
                        @foreach ($groups as $gr)
                            <div class="row" style="margin-right:10px">
                                <div class="col-lg-8 col-md-8">
                                    <strong>{{$gr->name}}</strong>
                                </div>
                                <div class="col-lg-4  col-md-4">
                                    <input type="checkbox" class="js-switch-primary" id={{$gr->id.$gr->name}} checked />
                                </div>
                            </div>
                            <br>
                        @endforeach


                    </div>

                </div>
                <div class="card success m-b-30" style="box-shadow: 1px 12px 15px 2px;">

                    <div class="card-body" style="color:black">
                        <div class="col-lg-12" style="margin-top:5px">
                            <h4>Layers</h4>
                        </div>
                        <hr>
                        <br>
                        @foreach ($profiles as $pro)
                            <div class="row" style="margin-right:10px">
                                <div class="col-lg-8 col-md-8" style="margin-top:-10px">
                                    <p style="opacity:0.8; margin-bottom:-2px"><small>Monitoring</small></p>
                                    <strong>{{$pro->name}}</strong>
                                </div>
                                <div class="col-lg-4  col-md-4">
                                    <input type="checkbox" class="js-switch-primary4" id="{{$pro->id.$pro->name}}" checked />
                                </div>
                            </div>
                            <br>
                        @endforeach



                        <div class="row" style="margin-right:10px">
                            <div class="col-lg-1 col-md-1">
                                <i class=" fa fa-gear" style="color:#ADEB39"></i>
                            </div>
                            <div class="col-lg-9 col-md-9">
                                <strong style="font-size:13px">Profile Setting</strong>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
        <div id="chartdiv" style="border-radius:0.5rem"></div>
        <!-- Start row -->

        <!-- End row -->
    </div>
    <!-- End Contentbar -->
@endsection
@section('script')
    <script src="{{ asset('assets/js/custom/custom-switchery.js') }}"></script>
    <script src="{{ asset('assets/plugins/map/index.js') }}"></script>
    <script src="{{ asset('assets/plugins/map/map.js') }}"></script>
    <script src="{{ asset('assets/plugins/map/worldLow.js') }}"></script>
    <script src="{{ asset('assets/plugins/map/themes/Animated.js') }}"></script>

    <script>
    @foreach ($groups as $gr)
    var primary_small = document.getElementById("{{$gr->id.$gr->name}}")
    var switchery = new Switchery(primary_small, { color: '#ADEB39', size: 'small' });
        
    @endforeach

     @foreach ($profiles as $pro)
    var primary_small = document.getElementById("{{$pro->id.$pro->name}}")
    var switchery = new Switchery(primary_small, { color: '#ADEB39', size: 'small' });
        
    @endforeach
    </script>

    <script>
        // Create root
        var root = am5.Root.new("chartdiv");

        // Set themes
        root.setThemes([
            am5themes_Animated.new(root)
        ]);


        var chart = root.container.children.push(
            am5map.MapChart.new(root, {

                projection: am5map.geoNaturalEarth1()
            })
        );
        chart.chartContainer.set("background", am5.Rectangle.new(root, {
            fill: am5.color(0xe9f5f7),
            fillOpacity: 1
        }));
        // Create polygon series
        var polygonSeries = chart.series.push(
            am5map.MapPolygonSeries.new(root, {
                fill: am5.color(0xffffff),
                stroke: am5.color(0x0f5587),
                geoJSON: am5geodata_worldLow,
                exclude: ["AQ"]
            })
        );




        // Load routes in GeoJSON format
        var links = {!! json_encode($links, JSON_NUMERIC_CHECK) !!};
        console.log(links);
        var routes = {
            "type": "FeatureCollection",
            "features": [{
                "type": "Feature",
                "properties": {
                    "name": "New York City line"
                },
                "geometry": {
                    "type": "MultiLineString",
                    "coordinates": links
                }
            }]
        };


        var lineSeries = chart.series.push(
            am5map.MapLineSeries.new(root, {
                geoJSON: routes
            })
        );



        var pointSeries = chart.series.push(am5map.MapPointSeries.new(root, {}));
        var colorset = am5.ColorSet.new(root, {});

        pointSeries.bullets.push(function() {
            var container = am5.Container.new(root, {});

            var circle = container.children.push(
                am5.Circle.new(root, {
                    radius: 6,
                    tooltipY: 2,
                    fill: "#8CB63D",
                    strokeOpacity: 6,
                    tooltipText: "{title}"
                })
            );

            var circle2 = container.children.push(
                am5.Circle.new(root, {
                    radius: 4,
                    tooltipY: 0,
                    fill: "#000000",
                    strokeOpacity: 0,
                    tooltipText: "{title}"
                })
            );

            circle.animate({
                key: "scale",
                from: 1,
                to: 5,
                duration: 4000,
                easing: am5.ease.out(am5.ease.cubic),

            });
            circle.animate({
                key: "opacity",
                from: 0,
                to: 0.5,
                duration: 1000,
                easing: am5.ease.out(am5.ease.cubic),

            });

            return am5.Bullet.new(root, {
                sprite: container
            });
        });


        var cities = {!! json_encode($cities, JSON_NUMERIC_CHECK) !!};


        for (var i = 0; i < cities.length; i++) {
            var city = cities[i];
            addCity(city.longitude, city.latitude, city.title);
        }

        function addCity(longitude, latitude, title) {
            pointSeries.data.push({
                geometry: {
                    type: "Point",
                    coordinates: [longitude, latitude]
                },
                title: title
            });
        }
    </script>
@endsection
