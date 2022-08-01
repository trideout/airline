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
    <table id="closestPoints" style="border:2px solid black;width:400px;">
        <thead>
            <tr>
                <th style="text-align:left">Name</th>
                <th style="text-align:left">X</th>
                <th style="text-align:left">Y</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br><br>
    <div id="farthestPointDiv"></div>
    <table id="farthestPoints" style="border:2px solid black;width:400px;">
        <thead>
            <tr>
                <th style="text-align:left">Name</th>
                <th style="text-align:left">X</th>
                <th style="text-align:left">Y</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <script>
        $(document).ready(function () {
            findClosestAndFurthestPoint($('input[name="x"]').val(), $('input[name="y"]').val());
            $('input').on('keyup', function () {
                $('#submitForm').prop('disabled', false);
                findClosestAndFurthestPoint($('input[name="x"]').val(), $('input[name="y"]').val());
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
            return Math.round(Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2)) * 10) / 10;
        }
        function findClosestAndFurthestPoint(x, y) {
            if (!$.isNumeric(x) || !$.isNumeric(y)) {
                $('#closestPointDiv').html("");
                $('#farthestPointDiv').html("");
                return;
            }
            farthestPoint = [];
            closestPoint = [];
            closestDistance = null;
            farthestDistance = null;
            $.each(points, function (key, value) {
                distance = calculateDistance(x, y, value.x, value.y);
                if (closestDistance == null || distance <= closestDistance) {
                    if (distance !== closestDistance) {
                        closestPoint = [];
                    }
                    closestDistance = distance;
                    closestPoint.push(value);
                }
            });
            $.each(points, function (key, value) {
                distance = calculateDistance(x, y, value.x, value.y);
                if (farthestDistance == null || distance >= farthestDistance) {
                    if (distance !== farthestDistance) {
                        farthestPoint = [];
                    }
                    farthestDistance = distance;
                    farthestPoint.push(value);
                }
            });
            clearClosestPointsTable();
            $('#closestPointDiv').html("The nearest point"+ (closestPoint.length > 1 ? "s are" : " is") +" at distance " + Math.round(closestDistance * 10 )/10 + "<br>");
            $('#farthestPointDiv').html("The farthest point"+ (farthestPoint.length > 1 ? "s are" : " is") +" at distance " + Math.round(farthestDistance * 10 )/10 + "<br>");
            $.each(closestPoint, function (key, value) {
                $('#closestPoints tbody').append('<tr><td>' + value.name + '</td><td>' + value.x + '</td><td>' + value.y + '</td></tr>');
            });
            $.each(farthestPoint, function (key, value) {
                $('#farthestPoints tbody').append('<tr><td>' + value.name + '</td><td>' + value.x + '</td><td>' + value.y + '</td></tr>');
            });
        }

        function clearClosestPointsTable() {
            $('#closestPoints tbody tr').remove();
            $('#farthestPoints tbody tr').remove();
        }
    </script>
</body>
</html>
