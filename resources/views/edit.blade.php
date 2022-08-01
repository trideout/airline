<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="application">
<div class=container id="errorsDiv"></div>
    <form action="#">
        {{ csrf_field() }}
        <label for="name">Name</label>
        <input
            type="text"
            name="name"
            placeholder="Name"
            x-model="point.name"
        >
        <label for="x">X</label>
        <input
            type="text"
            name="x"
            placeholder="X"
            x-model='point.x'
            x-on:keyup="$store.dirty = true;"
            >
        <label for="y">Y</label>
        <input
            type="text"
            name="y"
            placeholder="Y"
            x-model='point.y'
            x-on:keyup="$store.dirty = true;"
        >
        <input id="submitForm" type="submit" value="Submit" x-bind:disabled=" !$store.dirty ">
    </form>
    <div id="closestPointDiv" x-show="closestPoints.length > 0" x-text="closestPointText"></div>
    <table id="closestPoints" x-show="closestPoints.length > 0" style="border:2px solid black;width:400px;">
        <thead>
            <tr>
                <th style="text-align:left">Name</th>
                <th style="text-align:left">X</th>
                <th style="text-align:left">Y</th>
            </tr>
        </thead>
        <tbody>
        <template x-for="point in closestPoints">
            <tr>
                <td style="text-align:left" x-text="point.name"></td>
                <td style="text-align:left" x-text="point.x"></td>
                <td style="text-align:left" x-text="point.y"></td>
            </tr>
        </template>
        </tbody>
    </table>
    <br><br>
    <div id="farthestPointDiv" x-show="furthestPoints.length > 0" x-text="farthestPointText"></div>
    <table id="farthestPoints"  x-show="furthestPoints.length > 0" style="border:2px solid black;width:400px;">
        <thead>
            <tr>
                <th style="text-align:left">Name</th>
                <th style="text-align:left">X</th>
                <th style="text-align:left">Y</th>
            </tr>
        </thead>
        <tbody>
        <template x-for="point in furthestPoints">
            <tr>
                <td style="text-align:left" x-text="point.name"></td>
                <td style="text-align:left" x-text="point.x"></td>
                <td style="text-align:left" x-text="point.y"></td>
            </tr>
        </template>
        </tbody>
    </table>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('dirty', false);
            Alpine.data('application', () => ({
                point: @json($point),
                points: @json($points),
                closestDistance: '',
                farthestDistance: '',
                get furthestPoints() {
                    this.points.forEach(point => {
                        point.distance = calculateDistance(point.x, point.y, this.point.x, this.point.y);
                    });
                    this.farthestDistance = this.points.reduce((prev, curr) => {
                        return prev.distance > curr.distance ? prev : curr;
                    }).distance;
                    return this.points.filter(point => point.distance === this.farthestDistance);
                 },
                get closestPoints() {
                    this.points.forEach(point => {
                        point.distance = calculateDistance(point.x, point.y, this.point.x, this.point.y);
                    });
                    this.closestDistance = this.points.reduce((prev, curr) => {
                        return prev.distance < curr.distance ? prev : curr;
                    }).distance;
                    return this.points.filter(point => point.distance === this.closestDistance);
                },
                get farthestPointText() {
                    if (this.furthestPoints.length > 1) {
                        return `The farthest points are ${Math.round(this.farthestDistance * 10)/10} units away.`;
                    }
                    return `The farthest point is ${Math.round(this.farthestDistance * 10)/10} units away.`;
                },
                get closestPointText() {
                    if (this.closestPoints.length > 1) {
                        return `The closest points are ${Math.round(this.closestDistance * 10)/10} units away.`;
                    }
                    return `The closest point is ${Math.round(this.closestDistance * 10)/10} units away.`;
                }
            }));
        });

        function calculateDistance(x1, y1, x2, y2) {
            return Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        }
    </script>
    <script>
        $(document).ready(function () {
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
    </script>
</body>
</html>
