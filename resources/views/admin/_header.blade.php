<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sitarium</title>

    <!-- Fonts -->
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous"> -->
<!--     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700"> -->

    <!-- Styles -->
    {!! Asset::container('bootstrap')->styles() !!}
    {!! Asset::container('admin')->styles() !!}
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					Menu
					<span class="glyphicon glyphicon-menu-hamburger"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Sitarium
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
<!--                 <ul class="nav navbar-nav"> -->
<!--                     <li><a href="{{ url('/') }}">Home</a></li> -->
<!--                 </ul> -->

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li>
                        	<a href="{{ url('/login') }}">
                            	<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                        		Login
                        	</a>
                        </li>
<!--                         <li> -->
<!--                         	<a href="{{ url('/register') }}"> -->
<!--                             	<span class="glyphicon glyphicon-check" aria-hidden="true"></span> -->
<!--                         		Register -->
<!--                         	</a> -->
<!--                         </li> -->
                    @else
                        <li>
                        	<a href="{{ url('/admin') }}">
                            	<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
                        		Dashboard
                        	</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            	<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                {{ Auth::user()->name }}
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                	<a href="{{ url('/password/reset') }}">
                                    	<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    	Reset password
                                	</a>
                                </li>
                                <li>
                                	<a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    	<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                    	Logout
                                	</a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

