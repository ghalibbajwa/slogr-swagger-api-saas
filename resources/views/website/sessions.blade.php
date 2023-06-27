@section('title')
    Sessions
@endsection
@extends('layouts.main')
@section('style')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive Datatable css -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />


    <style>
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
                                <h2 style="font-weight:1000;">Sessions</h2>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <button type="button" class="btn btn-primary" style="float:right" onclick='addsession()'><i
                                    class="feather icon-plus-circle mr-2"></i> Add a new
                                Session</button>

                        </div>
                    </div>
                </div>
            </div>
            @if (session()->has('a_status'))
                @if (session()->get('a_status') == 'success')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @else
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error</strong> {{ session()->get('a_status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="datatable-buttons" class="display table">
                                <thead>
                                    <tr>
                                        <th>Server</th>
                                        <th>Client</th>
                                        <th>Number of Packets</th>
                                        <th>Interval</th>
                                        <th>Wait Time</th>
                                        <th>DSCP</th>
                                        <th>Last executed</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sessions as $session)
                                        <tr>

                                            <td>{{ $session->s_name }}</td>
                                            <td>{{ $session->c_name }}</td>
                                            <td>{{ $session->n_packets }}</td>
                                            <td>{{ $session->p_interval }}</td>
                                            <td>{{ $session->w_time }}</td>
                                            <td>{{ $session->dscp }}</td>
                                            <td>{{ $session->updated_at }}</td>

                                            <td>
                                                <form method="post" action="{{ url('edit-session') }}">
                                                    @csrf
                                                    <input hidden value={{ $session }} name="agent">
                                                    <button type="button" onmousedown='editsession({{ $session }})'
                                                        class="btn btn-round btn-warning"><i
                                                            class="feather icon-edit-2"></i></button>
                                                    <button type="submit" name="delete" value={{ $session->id }}
                                                        class="btn btn-round btn-danger"><i
                                                            class="feather icon-delete"></i></button>
                                                </form>

                                            </td>
                                            <td> <a href="{{ url('/analytics/' . $session->id) }}"><button type="submit"
                                                        class="btn btn-round btn-primary"><i
                                                            class="feather icon-bar-chart"></i></button></a></td>
                                        </tr>
                                    @endforeach

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="agentmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:1rem">

                    <div class="modal-body" style="padding:40px">
                        <form method="post" action="{{ url('add-session') }}">
                            @csrf
                            <input name="edit" id="edit" hidden>
                            <input name="aid" id="aid" hidden>
                            <div class="row">

                                <div class=" col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Server</strong>

                                    <select class="select2-single form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="serve" id="serve">
                                        <option disabled selected>Select a Server</option>
                                        @foreach ($agents as $agent)
                                            <option value={{ $agent->id }}>{{ $agent->name }}</option>
                                        @endforeach
                                    </select>

                                </div>


                                <div class="col-lg-6 col-md-6 form-group mb-0" style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Client</strong>

                                    <select class="select2-single form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="client" id="client">
                                        <option disabled selected>Select an Client</option>
                                        @foreach ($agents as $agent)
                                            <option value={{ $agent->id }}>{{ $agent->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <br>

                            <div class="row">

                                <div class="col-lg-6 col-md-6 form-group mb-0" style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Number of Sessions </strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="How many times will the test run? Enter 0 to run infinetly"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="count" id="count" placeholder="0 to run infinetly">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Schedule (in seconds)</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="After how many Seconds will each test run. For example 120 means; run after every 2 minutes"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="schedule" id="schedule" placeholder="Enter Seconds">
                                </div>
                            </div>
                            <br>
                            <hr>

                            <div class="row">

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Monitoring Profile</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="Monitoring Profile sets SLA for which the test will match against"></i>
                                    <select class="select2-single form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="profile" id="profile" onchange="profiler()">
                                        <option disabled selected>Select an Profile</option>
                                        @foreach ($profiles as $profile)
                                            <option value={{ $profile->id }}>{{ $profile->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Number of Packets</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="Number of Packets that will be communicated in each test"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="n_packets" id="n_packets" placeholder="20">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Packet Interval</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="The time in Micro Seconds, for the time between transmission of each packet"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="p_interval" id="p_interval" placeholder="50">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Wait Time</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="The time in Micro Seconds, for which the acknowledgement packet will be waited for"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="w_time" id="w_time" placeholder="2000">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">DSCP</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="(Differentiated Services Code Point) for mimicking each type of traffic"></i>
                                    <select class="select2-single form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="dscp" id="dscp">
                                        <option disabled selected>Select an DSCP</option>

                                        <option value="26">Gaming/Streaming - 26</option>
                                        <option value="46">PCOIP/VOIP - 46</option>

                                    </select>


                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Packet Size</strong>
                                    <i class="feather icon-info" data-toggle="tooltip" data-placement="top"
                                        title="The size in bytes, for each packet"></i>
                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="p_size" id="p_size" placeholder="50">
                                </div>

                            </div>





                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                        <button type="submit" id="agent-button" class="btn btn-primary">Create Session</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Start row -->

        <!-- End row -->
    </div>
    <!-- End Contentbar -->
@endsection
@section('script')
    <script src="{{ asset('assets/js/custom/custom-model.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/custom-table-datatable.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        var pros = {!! json_encode($pros, JSON_NUMERIC_CHECK) !!};

        function profiler() {


            $('#w_time').val(2000);
            $('#p_interval').val(50);
            $('#dscp').val(pros[$('#profile').val()]['dscp']);
            $('#p_size').val(50);
            $('#n_packets').val(20);
        }

        function editsession($data) {

            console.log($data);
            $('#serve').val($data['server']);

            $('#client').val($data['client']);
            $('#profile').val($data['profile']);
            $('#count').val($data['count']);
            $('#n_packets').val($data['n_packets']);
            $('#schedule').val($data['schedule']);
            $('#w_time').val($data['w_time']);
            $('#p_interval').val($data['p_interval']);
            $('#dscp').val($data['dscp']);
            $('#p_size').val($data['p_size']);
            $('#aid').val($data['id']);
            $('#edit').val('true');

            document.getElementById("agent-button").innerHTML = "Edit Session"
            $('#agentmodal').modal('show');

        }

        function addsession() {

            $('#serve').val('');
            $('#client').val('');
            $('#profile').val('');
            $('#count').val('');
            $('#n_packets').val('');
            $('#schedule').val('');
            $('#w_time').val('');
            $('#p_interval').val('');
            $('#dscp').val('');
            $('#p_size').val('');
            $('#aid').val('');
            $('#edit').val('');
            document.getElementById("agent-button").innerHTML = "Create an Session"
            $('#agentmodal').modal('show');

        }
    </script>
@endsection
