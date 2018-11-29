@extends('layouts.app')

@section('content')
<table class="table table-bordered" id="evaluations-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Schicht</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
</table>

    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script>
    $(function() {
        $('#evaluations-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
            ]
        });
    });

    </script>

    @stack('scripts')
@endsection
