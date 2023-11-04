<!doctype html>
@yield('htmlTag')
<head>
    @include('htmlHead')
</head>
<body>
    <div id="header">
        <h1>{{{$countyname}}}</h1>
        <h2>Online Personal Property Filing</h2>
        <div id="poweredby">Site powered by <a href="http://www.clearbasin.com">ClearBasin</a></div>
    </div>
    <div id="pageArea">
        <div id="loadingDialog" title="Sending">
            <img src="images/ajax-loader.gif" />
        </div>
        @yield('content')
    </div>
</body>
</html>
