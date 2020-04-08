<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
    	<div>
    		这里是公共的东西 所有页面都展示
    	</div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>