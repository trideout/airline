<html>
<head>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body x-data="application">
<div class=container>
    <template x-for="errorMessage in formErrors.errors">
        <div x-text="errorMessage">
        </div>
    </template>
</div>
    <form x-ref="submitform" @submit.prevent="submitForm" @form-saved.window="returnToList">
        <label for="name">Name</label>
        <input
            type="text"
            name="name"
            placeholder="Name"
            x-model="point.name"
            :class="inputHasErrors('name')"
        >
        <label for="x">X</label>
        <input
            type="text"
            name="x"
            placeholder="X"
            x-model='point.x'
            :class="inputHasErrors('x')"
            >
        <label for="y">Y</label>
        <input
            type="text"
            name="y"
            placeholder="Y"
            x-model='point.y'
            :class="inputHasErrors('y')"
        >
        <input
            type="submit"
            value="Submit"
            x-bind:disabled="isDirty"
        >
    </form>
    <div x-text="closestPointText"></div>
    <table x-show="closestPoints.length > 0" style="border:2px solid black;width:400px;">
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
    <div x-text="farthestPointText"></div>
    <table x-show="furthestPoints.length > 0" style="border:2px solid black;width:400px;">
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
        point = @json($point);
        points = @json($points);
    </script>
</body>
</html>
