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
    <script>
        $(document).ready(function () {
            $('input').on('keyup', function () {
                $('#submitForm').prop('disabled', false);
            });
            $('form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('points.update', $point->id) }}',
                    type: 'PUT',
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
