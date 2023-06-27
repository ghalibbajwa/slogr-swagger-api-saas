@section('title')
    Profiles
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
                                <h2 style="font-weight:1000;">Profiles</h2>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <button type="button" class="btn btn-primary" style="float:right" onclick='addprofile()'><i
                                    class="feather icon-plus-circle mr-2"></i> Add a new
                                Profile</button>

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
                                        <th>Name</th>
                                        <th>Number of Packets</th>
                                        <th>Interval</th>
                                        <th>Wait Time</th>
                                        <th>DSCP</th>
                                        <th>Max Uplink</th>
                                        <th>Max Downlink</th>
                                        <th>Max Jitter</th>
                                        <th>Max Rtt</th>
                                        <th>Packet Loss</th>
                                        <th></th>
                                        <th>Push Profile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profiles as $profile)
                                        <tr>
                                            <td>{{ $profile->name }}</td>
                                            <td>{{ $profile->n_packets }}</td>
                                            <td>{{ $profile->p_interval }}</td>
                                            <td>{{ $profile->w_time }}</td>
                                            <td>{{ $profile->dscp }}</td>
                                            <td>{{ $profile->max_uplink }}</td>
                                            <td>{{ $profile->max_downlink }}</td>
                                            <td>{{ $profile->max_jitter }}</td>
                                            <td>{{ $profile->max_rtt }}</td>
                                            <td>{{ $profile->max_loss }}</td>
                                            <td>
                                                <form method="post" action="{{ url('edit-profile') }}">
                                                    @csrf
                                                    <input hidden value={{ $profile }} name="agent">
                                                    <button type="button" onmousedown='editprofile({{ $profile }})'
                                                        class="btn btn-round btn-warning"><i
                                                            class="feather icon-edit-2"></i></button>
                                                    <button type="submit" name="delete" value={{ $profile->id }}
                                                        class="btn btn-round btn-danger"><i
                                                            class="feather icon-delete"></i></button>
                                                </form>
                                            </td>
                                            <td> <a href="{{ url('/push/' . $profile->id) }}"><button type="submit"
                                                        class="btn btn-round btn-primary"><i
                                                            class="feather icon-upload"></i></button></a></td>
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

                        <form method="post" action="{{ url('add-profile') }}">
                            @csrf
                            <input name="edit" id="edit" hidden>
                            <input name="aid" id="aid" hidden>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0" style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Name</strong>

                                    <input type="text" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="name" id="name" placeholder="Enter Profile Name">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0" style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Packet Size</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="p_size" id="p_size" placeholder="50">
                                </div>
                            </div>


                            <br>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Number of Sessions</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="count" id="count" placeholder="0 to run infinetly">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Number of Packets</strong>

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

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="p_interval" id="p_interval" placeholder="50">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Wait Time</strong>

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

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="dscp" id="dscp" placeholder="0">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Max Packet Loss</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="max_loss" id="max_loss" placeholder="50">
                                </div>




                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Max Avg Downlink</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="max_down" id="max_down" placeholder="30">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Max Avg Uplink</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="max_up" id="max_up" placeholder="30">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:15px; padding-left:0px">
                                    <strong style="color:black; ">Max Avg Jitter</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="max_jitter" id="max_jitter" placeholder="30">
                                </div>

                                <div class="col-lg-6 col-md-6 form-group mb-0"
                                    style="padding-right:0px; padding-left:0px">
                                    <strong style="color:black; ">Max Avg RTT</strong>

                                    <input type="number" class="form-control"
                                        style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px"
                                        name="max_rtt" id="max_rtt" placeholder="50">
                                </div>

                            </div>






                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                        <button type="submit" id="agent-button" class="btn btn-primary">Create Profile</button>
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
        function editprofile($data) {

            console.log($data);

            $('#count').val($data['count']);
            $('#max_down').val($data['max_downlink']);
            $('#max_up').val($data['max_uplink']);
            $('#max_jitter').val($data['max_jitter']);
            $('#max_rtt').val($data['max_rtt']);
            $('#max_loss').val($data['max_loss']);

            $('#n_packets').val($data['n_packets']);

            $('#name').val($data['name']);
            $('#w_time').val($data['w_time']);
            $('#p_interval').val($data['p_interval']);
            $('#dscp').val($data['dscp']);
            $('#p_size').val($data['p_size']);
            $('#aid').val($data['id']);
            $('#edit').val('true');

            document.getElementById("agent-button").innerHTML = "Edit Profile"
            $('#agentmodal').modal('show');

        }

        function addprofile() {


            $('#count').val('');
            $('#max_down').val('');
            $('#max_up').val('');
            $('#max_jitter').val('');
            $('#max_rtt').val('');
            $('#max_loss').val('');

            $('#n_packets').val('');

            $('#w_time').val('');
            $('#p_interval').val('');
            $('#dscp').val('');
            $('#p_size').val('');
            $('#aid').val('');
            $('#edit').val('');
            document.getElementById("agent-button").innerHTML = "Create an Profile"
            $('#agentmodal').modal('show');

        }
    </script>
@endsection
