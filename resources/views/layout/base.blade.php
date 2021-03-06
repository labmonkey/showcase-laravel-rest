<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('css/app.css') }}"/>
    <script>
        // I had to add this fix because framework has a bug (?) and JS breaks without this.
        window.Laravel = <?php echo json_encode( [
			'csrfToken' => csrf_token(),
		] ); ?>
    </script>
    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    @yield('head')
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>

