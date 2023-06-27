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
        .anychart-credits{
            display:none !important;
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
                                    <strong>{{ $gr->name }}</strong>
                                </div>
                                <div class="col-lg-4  col-md-4">
                                    <input type="checkbox" onchange="gg('{{ $gr->id . '|' . $gr->name }}')"
                                        class="js-switch-primary" id={{ $gr->id . '|' . $gr->name }} />
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
                                    <strong>{{ $pro->name }}</strong>
                                </div>
                                <div class="col-lg-4  col-md-4">
                                    <input type="checkbox" class="js-switch-primary4"
                                        onchange="cc('{{ $pro->id . '|' . $pro->name }}')"
                                        id="{{ $pro->id . '|' . $pro->name }}" />
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


    <script>
        @foreach ($groups as $gr)
            var primary_small = document.getElementById("{{ $gr->id . '|' . $gr->name }}")
            var switchery = new Switchery(primary_small, {
                color: '#ADEB39',
                size: 'small'
            });
        @endforeach

        @foreach ($profiles as $pro)
            var primary_small = document.getElementById("{{ $pro->id . '|' . $pro->name }}")
            var switchery = new Switchery(primary_small, {
                color: '#ADEB39',
                size: 'small'
            });
        @endforeach
    </script>






    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-core.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-map.min.js"></script>

    <script src="https://cdn.anychart.com/geodata/latest/custom/world/world.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.15/proj4.js"></script>

    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-data-adapter.min.js"></script>

    <script>
        var pros = {!! json_encode($pros, JSON_NUMERIC_CHECK) !!};
        var links = {!! json_encode($links, JSON_NUMERIC_CHECK) !!};
        var sdata = {!! json_encode($sdata, JSON_NUMERIC_CHECK) !!};
        var glinks = {!! json_encode($glinks, JSON_NUMERIC_CHECK) !!};
        var gpros = {!! json_encode($gpros, JSON_NUMERIC_CHECK) !!};
        var poins = {!! json_encode($poins, JSON_NUMERIC_CHECK) !!};
        var pointers = {!! json_encode($pointers, JSON_NUMERIC_CHECK) !!};
        const sessions = []
        for (const [key, value] of Object.entries(links)) {

            sessions.push(value);
        }




        function gg(id) {
            const group = id.split("|")[0];
            s = [];
            p=[];
            if (document.getElementById(id).checked == true) {
                sid = glinks[group];
                for (sid of sid) {

                    s.push(links[sid]);

                }

                pid = gpros[group];

                if (pid.length > 0) {
                    for (pid of pid) {
                        s = [];
                        sid = glinks[group];
                        tp = pros[pid];

                        for (sid of sid) {
                            for (const [key, value] of Object.entries(links)) {
                                if (key == sid) {
                                    ts = sdata[sid];
                                    ls= links[sid];
                                
                                    p.push(poins[ls['client']]);
                                    p.push(poins[ls['server']]);



                                    if(ts){
                                    if (ts['avg_rtt'] >= tp['max_rtt'] || ts['avg_jitter'] >= tp['max_jitter']) {
                                        value['stroke'] = 'red';

                                    } else {
                                        value['stroke'] = 'green';
                                    }

                                    s.push(value);
                                    }
                                }

                            }


                        }
           
                    }
                } else {
                    s = [];
                    sid = glinks[group];
                    for (sid of sid) {
                        for (const [key, value] of Object.entries(links)) {
                            if (key == sid) {

                                value['stroke'] = 'blue';


                                s.push(value);
                            }

                        }


                    }

                }


                updateConnectors(s,p);

            } else {
                for (const [key, value] of Object.entries(links)) {
                    value['stroke'] = 'blue';
                    s.push(value);
                }
                p=pointers;
                updateConnectors(s,p);

            }



        }

        function cc(id) {

            if (document.getElementById(id).checked == true) {
                const profile = id.split("|")[0];

                s = [];
                for (const [key, value] of Object.entries(links)) {
                    ts = sdata[key];
                    tp = pros[profile];
                    if(ts){
                    if (ts['avg_rtt'] >= tp['max_rtt'] || ts['avg_jitter'] >= tp['max_jitter']) {
                        value['stroke'] = 'red';

                    } else {
                        value['stroke'] = 'green';
                    }

                    s.push(value);
                    }
                }



                updateConnectors(s,pointers);



            } else {
                for (const [key, value] of Object.entries(links)) {
                    value['stroke'] = 'blue';
                    s.push(value);
                }
                updateConnectors(s,pointers);

            }

        }
    </script>




    <script>
        function pointer(op) {
            var image_link = "{{ asset('assets/images/pointer.png') }}";
            return {
                src: image_link,
                mode: 'fit',
                opacity: op
            }
        }
        var map;

        // create data set
        var links = {!! json_encode($links, JSON_NUMERIC_CHECK) !!};

        var data = sessions;


        

        var dataSet_lat_long = pointers;
        // create map chart
        var map = anychart.map();

        //create connector series
        var series = map.connector(data);


        var series_lat_long = map.marker(dataSet_lat_long);
        // set geodata
        map.geoData(anychart.maps['world']);

        //set markers
        map.tooltip().titleFormat("{%name}");
        map.tooltip().format("{%d}");
        // series_lat_long.tooltip({title: false, separator: false});
        map.labels(false);
        // Create a function to update the connectors
        function updateConnectors(data, dataSet_lat_long) {
            // Clear the existing connectors
            map.removeAllSeries();

            // Create the connector series
            var series = map.connector(data);

            // Set markers for lat-long data
            var series_lat_long = map.marker(dataSet_lat_long);
            series_lat_long.normal().stroke(0);
            series_lat_long.normal().size(50);
            series_lat_long.normal().fill(pointer(1));
            series_lat_long.hovered().size(65);
            series_lat_long.selected().size(65);
            series_lat_long.selected().stroke(0);
            series_lat_long.selected().fill(pointer(1));

            series.markers({
                position: '100%',
                size: 8,
                type: 'circle'
            });
            series.hovered().markers({
                position: '100%',
                size: 3,
                fill: '#1976d2',
                stroke: '2 #E1E1E1',
                type: 'circle'
            });
            series.selected().markers({
                position: '100%',
                size: 3,
                fill: '#1976d2',
                stroke: '2 #E1E1E1',
                type: 'circle'
            });
            map.container('chartdiv');

            // Draw the updated map\
            map.draw();
        }

        // Call the function to initially draw the map
        updateConnectors(data, dataSet_lat_long);
    </script>
@endsection
