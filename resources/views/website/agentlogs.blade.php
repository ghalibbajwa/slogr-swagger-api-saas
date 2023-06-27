@section('title')
    Logs
@endsection
@extends('layouts.main')
@section('style')
@endsection
@section('rightbar-content')
    <!-- Start Contentbar -->
    <div class="contentbar">
        <table id="datatable-buttons" class="display table">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $k)
                                <tr>
                                    <td>{{ $k }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        <!-- End row -->
    </div>
    <!-- End Contentbar -->
@endsection
@section('script')
<script>
window.scrollTo(0, document.body.scrollHeight);

</script>
@endsection
