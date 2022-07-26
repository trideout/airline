<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script
   src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
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
            ajax: '{{ route('points.all') }}',
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'x'},
                {data: 'y'},
                {render: function (data, type, row) {
                    return '<a href="{{ route('points.edit', ':id') }}'.replace(':id', row.id) + '">Edit</a>';
                }}
            ]
        });
    });
</script>
</body>
</html>
