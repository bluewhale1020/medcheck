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

    <!-- SEARCH FORM -->
    {{-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form> --}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      {{-- 右サイドバー --}}
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fa fa-users"></i>
            {{-- <span class="badge badge-warning navbar-badge">15</span> --}}
          </a>
      </li>

      {{-- 設定 --}}
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          <i class="fa fa-cog" aria-hidden="true"></i>
          <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          @if (Auth::user()->role->name == 'admin')
            <router-link class="dropdown-item" to="/medcheck/public/users">
                ユーザー管理
            </router-link>              
            <router-link class="dropdown-item" to="/medcheck/public/manage_auth">
                Passport管理
            </router-link>              
          @endif
          <router-link class="dropdown-item" to="/medcheck/public/edit_config">
              システム設定
          </router-link>
          <router-link class="dropdown-item" to="/medcheck/public/manage_exam_area">
              検査エリア管理
          </router-link>

        </div>
    </li>

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
    <a href="" class="brand-link">
      <img src="{{ asset('img/checkup.png') }}" alt="Medcheck Logo" class="brand-image img-rectangle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Medcheck</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          {{-- <img src="{{ asset('img/user.png') }}" class="img-circle elevation-2" alt="User Image"> --}}
          <img src="{{ asset($image_path) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->




            <li class="nav-item">
            <router-link to="/medcheck/public/dash_board" class="nav-link">
              <i class="nav-icon fa fa-home text-teal"></i>
              <p>
                トップ
              </p>
            </router-link>
            </li>

            @foreach ($menus as $menu)
                
              @if ($menu->name == 'トップ')
                  
              @elseif ($menu->name == '健診簿インポート')
              <li class="nav-item">
                  <router-link to="/medcheck/public/import" class="nav-link">
                    <i class="fa fa-upload text-orange"></i>
                    <p>
                        健診簿インポート
                    </p>
                  </router-link>   
                </li>              
              @elseif ($menu->name == '予約リスト')
              <li class="nav-item">
                  <router-link to="/medcheck/public/reserve" class="nav-link">
                    <i class="fa fa-list text-orange"></i>
                    <p>
                        予約リスト
                    </p>
                  </router-link>
                  </li>              
              @elseif ($menu->name == '検査エリア')
              <li class="nav-item has-treeview"> <!-- menu-open -->
                <a href="#" class="nav-link bg-orange">
                  <i class="nav-icon fa fa-stethoscope"></i>
                  <p>
                    検査エリア
                    <i class="right fa fa-angle-left"></i>
                  </p>
                </a>
                {{-- set side-menu-area --}}
                <side-menu-area :area_list="{{ json_encode($area_list)}}" role_id="{{ Auth::user()->role_id }}"></side-menu-area>
                  </li>

              @elseif ($menu->name == '検査進捗管理')

              <li class="nav-item">
                  <router-link to="/medcheck/public/progress" class="nav-link">
                    <i class="nav-icon fa fa-tasks"></i>
                    <p>
                        検査進捗管理
                    </p>
                  </router-link>            
                </li>

              @elseif ($menu->name == '健診結果出力')
              <li class="nav-item">
                  <router-link to="/medcheck/public/exam_result" class="nav-link">
                    <i class="nav-icon fa fa-table"></i>
                    <p>
                        健診結果出力
                    </p>
                  </router-link>
                </li>                 
              @endif


            @endforeach

            <li class="nav-header">Docs</li>

            <li class="nav-item"> <!-- menu-open -->
              <a href="/medcheck/public/manual/basic" class="nav-link">
                <i class="nav-icon fa fa-book"></i>
                <p>
                  マニュアル
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
        
        <!-- ルートアウトレット -->
        <!-- ルートとマッチしたコンポーネントがここへ描画されます -->
        <router-view></router-view>
        <!-- set progressbar -->
        <vue-progress-bar></vue-progress-bar>
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  <right-side-bar :online_users="{{ json_encode($online_users)}}"></right-side-bar>
  </aside>
  <!-- /.control-sidebar -->

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
<script src="{{ asset('/js/session_timeout.js') }}"></script>
</body>
</html>
