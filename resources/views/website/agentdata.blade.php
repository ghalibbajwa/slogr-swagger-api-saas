@section('title')
    Analytics
@endsection
@extends('layouts.main')
@section('style')


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
                                <h4 style="font-weight:1000;">{{ $agent->name }}</h4>
                            </span>
                        </div>
                        <div class="col-md-6 col-lg-6">

                            <select class="select2-single form-control" style="border:1px solid #8CB63D;" name={{$agent->id}}
                                id="profile">
                                <option disabled selected>Select Profile</option>
                                @foreach ($pros as $pro)
                                    <option value={{ $pro->id }}>{{ $pro->name }}</option>
                                @endforeach


                            </select>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12 col-md-12">
                <div class="card m-b-30">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table foo-pagination-table" data-paging="true" data-paging-size="2">
                                <thead>
                                    <tr>
                                        <th>
                                            <h6>RT PD</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
                                        </th>
                                        <th>
                                            <h6>TX PD</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
                                        </th>
                                        <th>
                                            <h6>RX PD</h6>
                                            <p style="font-weight:100">Min/Max/Std</p>
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

                                    @foreach ($res as $an)
                                        @if ($an->sla == 'breached')
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
                        <a>

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


    <script>
        $('#profile').val('{{$profile->id}}')
        $(document.body).on("change", "#profile", function() {
                    const a = document.createElement('a');
                    a.href = "{{url('/agentdata')}}"+"/{{$agent->id}}"+"/"+this.value ;
                    document.body.appendChild(a); // Firefox apparently requires this
                    a.click();
                    document.body.removeChild(a);

                    });
    </script>
@endsection
