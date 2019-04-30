<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>  {{ config('app.name') }} </title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('styleResource/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('styleResource/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'sans';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">

</nav>
    <style>
        .input-group-lg>.form-control, .input-group-lg>.input-group-addon, .input-group-lg>.input-group-btn>.btn{
            font-size: 15px;
        }
    </style>
    <div class="container">
        <div class="row">

            <div class="well col-md-5 center col-md-offset-3">

                <div class="panel panel-info">
                    <div class="panel-heading">Login</div>


                    <div class="panel-body">
                        <h3 style="color:red">
                            @if (session('exception'))
                                <div class="alert alert-danger">
                                    {{ session('exception') }}
                                </div>
                            @endif
                            <?php
                            echo Session::put('exception','');
                            ?>
                        </h3>
                        <h3 style="color:green">
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <?php
                            echo Session::put('message','');
                            ?>

                        </h3>
                        <h3 style="color:red">
                            @if (session('mess'))
                                <div class="alert alert-success">
                                    {{ session('mess') }}
                                </div>
                            @endif
                            <?php
                            echo Session::put('mess','');
                            ?>
                        </h3>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin-login') }}">
                            {{ csrf_field() }}

                        <span class="col-sm-offset-4">
                            <img src="{{URL::to('logo_client.jpeg')}}" width="150px" height="100px">
                            <h4 class="text-center">Welcome</h4>
                        </span>

                            <br />
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                                <input id="username" type="text" class="form-control" name="username" placeholder="Enter username">
                            </div>
                            <div class="clearfix"></div><br>

                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                                <input id="password" type="password" class="form-control" name="employeePassword">
                            </div>
                            <div class="clearfix"></div><br>




                            <div class="clearfix"></div><br>

                            <button type="submit" class="col-md-offset-4 btn btn-success" style="text-align:center">
                                <i class="fa fa-btn fa-sign-in"></i> Login
                            </button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('styleResource/bower_components/jquery/dist/jquery.min.js')}}">
    <link rel="stylesheet" href="{{ asset('styleResource/bower_components/bootstrap/dist/js/bootstrap.min.js')}}">

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
