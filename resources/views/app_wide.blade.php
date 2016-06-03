<!DOCTYPE html>
<html lang="en">

@include('app_head')

<body class="container-fluid">
        @include('app_nav')
        <main>
            @yield('content')
        </main>
    <p style="clear:both">&nbsp;</p>
    
    <script>
        @yield('scripts');
    </script>

</body>
</html>
