@section('title')
    Groups
@endsection
@extends('layouts.main')
@section('style')
    <link href="{{ asset('assets/plugins/nestable/jquery.nestable.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .dd-list .dd-item .dd-handle {

            background: #def1bb !important;
            color: black !important;

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
                                <h2 style="font-weight:1000;">Groups</h2>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="row">
                                <div class="col-md-6 col-lg-6" style="float:right">

                                </div>
                                <div class="col-md-6 col-lg-6" style="float:right">
                                    <button type="button" class="btn btn-primary" style="float:right" onclick='addnew()'><i
                                            class="feather icon-plus-circle mr-2"></i> Add a new
                                        Group</button>
                                </div>
                            </div>
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
            <div class="col-lg-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        <h5 class="card-title">Group</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle">Drag & drop items to group</h6>

                        <form method="post" action="{{ url('add-group') }}">
                            @csrf
                            <div class="form-group mb-0">
                                <select class="select2-single form-control" style="border:1px solid #8CB63D; float:right"
                                    name="g_id" id="g_id">
                                    <option val="se" disabled selected>Select Group to Edit</option>
                                    @foreach ($groups as $gr)
                                        <option value={{ $gr->id }}>{{ $gr->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                            <br>
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" style="border:1px solid #8CB63D; margin-top:20px"
                                    name="name" id="name" placeholder="Enter Group Name">
                            </div>
                            <br>
                            <div class="dd" id="group">
                                <ol class="dd-list">

                                </ol>
                                <ol class="dd-list" id="group-dd">

                                </ol>

                            </div>
                            <button type="submit" id="agent-button" class="btn btn-primary">Create Group</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">

                    <div class="col-lg-4">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title">Sessions</h5>
                            </div>
                            <div class="card-body">

                                <div class="dd" id="session">
                                    <ol class="dd-list">

                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <h5 class="card-title">Profiles</h5>

                            </div>

                            <div class="card-body">
                                <h6 class="card-subtitle">Add only 1 profile</h6>
                                <div class="dd" id="profile">
                                    <ol class="dd-list">

                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contentbar -->
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/nestable/jquery.nestable.min.js') }}"></script>
    <script>
        pop([]);

        function pop(group) {


            "use strict";
            if (group.length > 0) {
                $('#group').nestable('destroy');
            }

            var updateOutput = function(e) {


                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                {{-- if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));
                } else {
                    output.val('JSON browser support required for this demo.');
                } --}}
            };


            var agent = {!! json_encode($agent, JSON_NUMERIC_CHECK) !!};
            var profile = {!! json_encode($profile, JSON_NUMERIC_CHECK) !!};
            var session = {!! json_encode($session, JSON_NUMERIC_CHECK) !!};

            var lastId = 12;
            /* -- activate Nestable for list 1 -- */
            $('#group').nestable({
                group: 1,
                json: group,
                contentCallback: function(item) {
                    console.log('2e32');

                    var content = item.content || '' ? item.content : item.id;
                    content += ' <i>(id = ' + item.id + ')</i>';

                    return content;
                }

            }).on('change', updateOutput);
            $('#session').nestable({
                group: 1,
                json: session,
                contentCallback: function(item) {
                    var content = item.content || '' ? item.content : item.id;
                    content += ' <i></i> <input hidden name="session|' + item
                        .content + '""  value=' + item.id + '>';
                    return content;
                }
            }).on('change', updateOutput);
            $('#profile').nestable({
                group: 1,
                json: profile,
                contentCallback: function(item) {
                    var content = item.content || '' ? item.content : item.id;
                    content += ' <i ></i> <input hidden name="profile|' + item
                        .content + '""  value=' + item.id + '>';

                    return content;
                }

            }).on('change', updateOutput);

        };

        function addnew() {
            pop([]);
            $('#name').val('');
            $('#g_id').val('Select Group to Edit');
            document.getElementById("agent-button").innerHTML = "Create Group";

        }
        $(document.body).on("change", "#g_id", function() {

            // Retrieve the CSRF token from the page
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX POST request with the CSRF token as a header
            $.ajax({
                url: 'get-group',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    id: this.value
                },
                success: function(response) {


                    var group = JSON.stringify(response[1]);
                    document.getElementById("agent-button").innerHTML = "Edit Group";
                    $('#name').val(response[0]['name']);

                    pop(group);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        });
    </script>
@endsection
