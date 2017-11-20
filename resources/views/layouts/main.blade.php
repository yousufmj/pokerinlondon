<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="_hSxjtrrGyADRp-WLpbToDgDC60XPbQSTePMavzOS7M" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <title>
    	@section('title')
        | Poker In London
        @show
    </title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/poker.css') }}">
    <!--end of global css-->
    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
</head>

<body>
    <!-- Header Start -->
    <header>
        
        <!-- Nav bar Start -->
        <nav class="navbar navbar-default container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                    <span><a href="#">_<i class="livicon" data-name="responsive-menu" data-size="25" data-loop="true" data-c="#757b87" data-hc="#ccc"></i>
                    </a></span>
                </button>
                <a class="navbar-brand" href="{{ route('today') }}"><img src="{{ asset('assets/images/poker-logo-small.png') }}" alt="logo" class="logo_position">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li {!! (Request::is('today') ? 'class="active"' : '') !!}><a href="{{ route('today') }}"> Poker Today</a>
                    </li>
                    <li {!! (Request::is('tournaments') || Request::is('tournaments/*') ? 'class="active"' : '') !!}><a href="{{ URL::to('tournaments') }}"> Tournaments</a>
                    </li>
                    <li class="dropdown"><a href="{{ route('casino-main') }}" class="dropdown-toggle">Casino's</a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ URL::to('casino/aspers') }}">Aspers</a>
                            </li>
                            <li><a href="{{ URL::to('casino/empire') }}">Empire</a>
                            </li>
                            <li><a href="{{ URL::to('casino/hippodrome') }}">Hippodrome</a>
                            </li>
                            <li><a href="{{ URL::to('casino/vic') }}">The Vic</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Nav bar End -->
    </header>
    <!-- //Header End -->
    
    <!-- slider / breadcrumbs section -->
    @yield('top')

    <!-- Content -->
    @yield('content')

    <!-- Footer Section Start -->
    <footer>
        <div class="container footer-text footer-padding">
            <!-- About Us Section Start -->
            <div class="col-sm-4">
                <ul class="list-inline">
                    <li>
                        <a href="https://www.facebook.com/Poker-In-London-1377485192343022"> 
                            <i class="livicon" data-name="facebook" data-size="18" data-loop="true" data-c="#ccc" data-hc="#ccc"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/poker_in_london"> 
                            <i class="livicon" data-name="twitter" data-size="18" data-loop="true" data-c="#ccc" data-hc="#ccc"></i>
                        </a>
                    </li>
                     <li>
                        <a href="mailto:pokerinldn@gmail.com">
                            <i class="livicon" data-name="mail" data-size="18" data-loop="true" data-c="#ccc" data-hc="#ccc"></i>
                        </a>
                </ul>
            </div>

        </div>
    
    <!-- //Footer Section End -->
    <div class="copyright">
        <div class="container">
        <p>All Rights Reserved | PokerInLondon <?=date('Y')?></p>
        </div>
    </div>

    </footer>
    <!--global js starts-->
    <script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!--analytics-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-75000392-1', 'auto');
        ga('send', 'pageview');

    </script>
    <!--livicons-->
    <script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/frontend/josh_frontend.js') }}"></script>
    <!--global js end-->
    <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->
</body>

</html>
