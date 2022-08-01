<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
</head>
<body>
<table id="pointsTable">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>X</th>
<th>Y</th>
<th>Actions</th>
</tr>
</thead>
</table>
<script>
    $(document).ready(function () {
        $('#pointsTable').DataTable({
            dom: 'Bfrtip',
            ajax: '{{ route('points.all') }}',
            buttons: [
                {
                    text: 'Create Point',
                    action: function (e, dt, node, config) {
                        window.location.href = '{{ route('points.create') }}';
                    }
                }
            ],
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'x'},
                {data: 'y'},
                {render: function (data, type, row) {
                    return '<a href="{{ route('points.edit', ':id') }}'.replace(':id', row.id) + '">Edit</a>' + '&nbsp;&nbsp;&nbsp;' +
                        '<a href="{{ route('points.delete', ':id') }}'.replace(':id', row.id) + '">Delete</a>';
                }}
            ]
        });
    });
</script>
</body>
</html>
