<!DOCTYPE html>
<html>
@section('style')
<!-- DataTables css -->
<link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive Datatable css -->
<link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

<head>
    <title>slogr.io</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            
            margin-bottom: 20px;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        h1 {
            color: #333;
            
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer {
            
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    
        <div class="header">
            <img src="https://slogr.io/wp-content/uploads/2023/06/Group-2420.png" alt="Logo">
        </div>
        <h1>{{ $details['title'] }}</h1>
        @php
        $colorsArray = explode(', ', $details['body']);
        @endphp

        <h6 class="card-subtitle"  >Details for the sessions SLA breached</h6>
        <div class="table-responsive" >
            <table id="default-datatable" class="display table table-striped table-bordered" style="text-align:center;">
                <thead  > 
                    <tr>
                        <th>Client</th>
                        <th>Server</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody  >
                    @for($x=1; $x < count($details['sessions']); $x++) <tr>
                        <td>{{$details['sessions'][$x]->c_name}}</td>
                        <td>{{$details['sessions'][$x]->s_name}}</td>
                        <td>{{ $colorsArray[$x]}}</td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>




        <!-- @if(isset($details['arrayData']))
        <p>The array data as a string:</p>
        <p>{{ implode(", ", $details['arrayData']) }}</p>
        @endif -->

</body>

</html>
@section('script')
<!-- Datatable js -->

@endsection