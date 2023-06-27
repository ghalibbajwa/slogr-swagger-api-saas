@section('title')
    Agents
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
                                <h2 style="font-weight:1000;">Agents</h2>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            {{-- <button type="button" class="btn btn-primary" style="float:right" onclick='addagent()'><i
                                    class="feather icon-plus-circle mr-2"></i> Add a new
                                Agent</button> --}}

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
                                        <th>IP Address</th>
                                        <th>Operating System</th>
                                        <th>Location</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agents as $agent)
                                        <tr>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->ipaddress }}</td>
                                            <td>{{ $agent->platform }}</td>
                                            <td>{{ $agent->location }}</td>
                                            <td>
                                                <form method="post" action="{{ url('edit-agent') }}">
                                                    @csrf
                                                    <input hidden value={{ $agent }} name="agent">
                                                    {{-- <button type="button" onmousedown='logs({{ $agent }})'
                                                        class="btn btn-round btn-warning"><i
                                                            class="feather icon-file-text"></i></button> --}}
                                                    <button type="submit" name="delete" value={{ $agent->id }}
                                                        class="btn btn-round btn-danger"><i
                                                            class="feather icon-delete"></i></button>

                                                </form>


                                            </td>

                                            <td>

                                                <form method="get" action="{{ url('down/' . $agent->id) }}">
                                                    <button type="submit" onclick="showtoast()"
                                                        class="btn btn-round btn-success"><i
                                                            class="feather icon-download"></i></button>

                                                </form>


                                            </td>
                                            <td>
                                                {{-- @if ($agent->type == 'client' and $agent->os == 'win') --}}
                                                <a href="{{ url('/agentdata/' . $agent->id . '/1') }}"><button
                                                        type="submit" class="btn btn-round btn-primary"><i
                                                            class="feather icon-bar-chart"></i></button></a>
                                                <a href="{{ url('/agentlogs/' . $agent->id) }}"><button
                                                        type="submit" class="btn btn-round btn-warning"><i
                                                            class="feather icon-file-text"></i></button></a>
                                                {{-- @endif --}}
                                            </td>
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

                        <form method="post" action="{{ url('add-agent') }}">
                            @csrf
                            <input name="edit" id="edit" hidden>
                            <input name="aid" id="aid" hidden>
                            <div class="form-group mb-0">
                                <strong style="color:black; ">Name</strong>

                                <input type="text" class="form-control"
                                    style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px" name="name"
                                    id="name" placeholder="Enter Agent Name">
                            </div>
                            <br>
                            <div class="form-group mb-0">
                                <strong style="color:black; ">IP Address</strong>

                                <input type="text" class="form-control"
                                    style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px" name="ip"
                                    id="ip" placeholder="0.0.0.0">
                            </div>
                            <br>

                            <div class="form-group mb-0">
                                <strong style="color:black; ">Organization</strong>

                                <input type="text" class="form-control"
                                    style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px" name="org"
                                    id="org" placeholder="Enter Organization Name">
                            </div>
                            <br>

                            <div class="form-group mb-0">
                                <strong style="color:black; ">Operating System</strong>

                                <select class="select2-single form-control"
                                    style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px" name="os"
                                    id="os">
                                    <option disabled selected>Select Operating System</option>
                                    <option value="win">Windows</option>
                                    <option value="linux">Linux (Docker)</option>
                                </select>

                            </div>
                            <br>
                            <div class="form-group mb-0">
                                <strong style="color:black; ">Type</strong>

                                <select class="select2-single form-control"
                                    style="border:1px solid #8CB63D; border-radius:0.5rem;margin-top:10px" name="type"
                                    id="type">
                                    <option disabled selected>Select Type</option>
                                    <option value="client">Client</option>
                                    <option value="server">Server (Linux only)</option>
                                </select>

                            </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                        <button type="submit" id="agent-button" class="btn btn-primary">Create an Agent</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Start row -->
        <div aria-live="polite" aria-atomic="true">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true"
                style="position: absolute; bottom: 30px; right: 30px;" id="bottom-right-toasts">
                <div class="toast-header">
                    <i class="feather icon-info text-warning mr-2"></i>
                    <span class="toast-title mr-auto">Installation Instructions</span>

                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Read Instructions <strong><a href="https://github.com/slogr/slogr-twamp"
                            target="_blank">Here</a></strong>
                </div>
            </div>
        </div>
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
        function showtoast() {
            $("#bottom-right-toasts").toast({
                delay: 6000
            });
            $("#bottom-right-toasts").toast("show");
        }

        function editagent($data) {

            $('#org').val($data['organization']);
            $('#name').val($data['name']);
            $('#ip').val($data['ipaddress']);
            $('#os').val($data['os']);
            $('#type').val($data['type']);
            $('#aid').val($data['id']);
            $('#edit').val('true');
            document.getElementById("agent-button").innerHTML = "Edit Agent"
            $('#agentmodal').modal('show');

        }

        function addagent() {

            $('#org').val('');
            $('#name').val('');
            $('#ip').val('');
            $('#os').val('');
            $('#type').val('');
            $('#aid').val('');
            $('#edit').val('');
            document.getElementById("agent-button").innerHTML = "Create an Agent"
            $('#agentmodal').modal('show');

        }
    </script>
@endsection
