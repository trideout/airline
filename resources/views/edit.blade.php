<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
</head>
<body>
<div class=container id="errorsDiv"></div>
    <form action="#" method="POST">
        {{ csrf_field() }}
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Name" value="{{ $point->name }}">
        <label for="x">X</label>
        <input type="text" name="x" placeholder="X" value="{{ $point->x }}">
        <label for="y">Y</label>
        <input type="text" name="y" placeholder="Y" value="{{ $point->y }}">
        <input id="submitForm" type="submit" value="Submit" disabled=true>
    </form>
    <div id="closestPointDiv"></div>
    <script>
        $(document).ready(function () {
            findClosestPoint($('input[name="x"]').val(), $('input[name="y"]').val());
            $('input').on('keyup', function () {
                $('#submitForm').prop('disabled', false);
                findClosestPoint($('input[name="x"]').val(), $('input[name="y"]').val());
            });
            $('form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ $point->id ? route('points.update', $point->id) : route('points.store') }}',
                    type: '{{ $point->id ? 'PUT' : 'POST' }}',
                    data: $(this).serialize(),
                    success: function (data) {
                        window.location.href = '{{ route('points.index') }}';
                    },
                    error: function (data) {
                        $('#errorsDiv').html("");
                        errors = data.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('input[name=' + key + ']').addClass('error');
                            $('#errorsDiv').append('<p>' + value + '</p>');
                        });
                    }
                });
            });
        });
        points = {!! \App\Models\Point::all()->filter( fn($p) => $p->id != $point->id)->toJson() !!};
        function calculateDistance(x1, y1, x2, y2) {
            return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        }
        function findClosestPoint(x, y) {
            if (!$.isNumeric(x) || !$.isNumeric(y)) {
                $('#closestPointDiv').html("");
                return;
            }
            closestPoint = null;
            closestDistance = null;
            $.each(points, function (key, value) {
                distance = calculateDistance(x, y, value.x, value.y);
                if (closestDistance == null || distance < closestDistance) {
                    closestDistance = distance;
                    closestPoint = value;
                }
            });
            $('#closestPointDiv').html('Closest point is ' + closestPoint.name + ' at ' + closestPoint.x + ', ' + closestPoint.y);
        }
    </script>
</body>
</html>
