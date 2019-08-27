<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


<link rel="stylesheet" href="{{ asset('/css/app.css') }}">
<link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-orange navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>

    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      {{-- 右サイドバー --}}

      {{-- logout --}}
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          <i class="fa fa-sign-out" aria-hidden="true"></i>
          <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>


    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/medcheck/public/home" class="brand-link">
      <img src="{{ asset('img/checkup.png') }}" alt="Medcheck Logo" class="brand-image img-rectangle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Home 画面へ</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-header">マニュアル</li>

            <li class="nav-item">
                <a href="/medcheck/public/manual/basic" class="nav-link" name="basic">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      基本事項
                  </p>
                </a>                    
            </li>
            <li class="nav-item">
                <a href="/medcheck/public/manual/user" class="nav-link" name="user">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      ユーザー管理
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/config" class="nav-link" name="config">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      システム設定
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/manage_area" class="nav-link" name="manage_area">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      検査エリア管理
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/import" class="nav-link" name="import">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      健診簿インポート
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/reserve" class="nav-link" name="reserve">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      予約リスト
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/area" class="nav-link" name="area">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      検査エリア
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/progress" class="nav-link" name="progress">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      検査進捗管理
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/result" class="nav-link" name="result">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      健診結果出力 
                  </p>
                </a>                    
            </li>         
            <li class="nav-item">
                <a href="/medcheck/public/manual/top" class="nav-link" name="top">
                  <i class="nav-icon fa fa-circle-o"></i>
                  <p>
                      トップ
                  </p>
                </a>                    
            </li>         



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
          @yield('content')  
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        Version 1.0-beta.1
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2019 <a href="https://github.com/bluewhale1020/medcheck">Yasuno Hironori</a>.</strong> All rights reserved.
      <div>Icons made by <a href="https://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="https://www.flaticon.com/" 			    title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" 			    title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
    </footer>
</div>
<!-- ./wrapper -->
<script src="{{ asset('/js/app.js') }}"></script>
<script>
        $(function(){
          menu_item = '@yield('menu')';
          $('.nav-link[name='+menu_item+']').addClass('active');
        });  

</script>
</body>
</html>
