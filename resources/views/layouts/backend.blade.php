<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Panel de control | @yield('titulo')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{url('/css/skins/_all-skins.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{url('/plugins/iCheck/flat/blue.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{url('/plugins/morris/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{url('/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{url('/plugins/datepicker/datepicker3.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{url('/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{url('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <!-- Apprise css -->
  <link rel="stylesheet" href="{{url('/css/apprise.min.css')}}" type="text/css" />
  <style>
  .appriseOuter {
	  top: 45% !important;
  }
<?php $url = Request::root(); ?>
@if(strpos( $url, 'test' ))
.skin-blue .main-header .logo {
    background-color: #408268 !important;
}
.skin-blue .main-header .navbar {
    background-color: #5db592 !important;
}
.skin-blue .main-header .navbar .sidebar-toggle:hover {
    background-color: #de991a !important;
}
.skin-blue .main-header li.user-header {
    background-color: #ac7308 !important;
}
.navbar-nav>.user-menu>.dropdown-menu {
    padding: 0px 0 0 0 !important;
}
<?php
	$bdeco = "<b>Deco</b>test";
	$sdeco = "<b>D</b>T";
?>
@else

<?php
	$bdeco = "<b>Deco</b>wood";
	$sdeco = "<b>D</b>W";
?>
.navbar-nav>.user-menu>.dropdown-menu {
	padding: 0px 0 0 0 !important;
}
@endif
  </style>
  @yield('estilos')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">{!! $sdeco !!}</span>
      <!-- logo for regular state and mobile devices -->

      <span class="logo-lg ">{!! $bdeco !!}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu" style="display:none;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="{{url(Auth::user()->imagen_perfil)}}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="{{url(Auth::user()->imagen_perfil)}}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        AdminLTE Design Team
                        <small><i class="fa fa-clock-o"></i> 2 hours</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Developers
                        <small><i class="fa fa-clock-o"></i> Today</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="img/user3-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Sales Department
                        <small><i class="fa fa-clock-o"></i> Yesterday</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="img/user4-128x128.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Reviewers
                        <small><i class="fa fa-clock-o"></i> 2 days</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu" style="display:none;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu" style="display:none;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Create a nice theme
                        <small class="pull-right">40%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">40% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Some task I need to do
                        <small class="pull-right">60%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">60% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Make beautiful transitions
                        <small class="pull-right">80%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          {{--<li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{{ url(Auth::user()->imagen_perfil) }}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->apodo }}</span>
            </a>
          </li>--}}
          <!-- Control Sidebar Toggle Button
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{{ url(Auth::user()->imagen_perfil) }}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->apodo }} </p>
          <small> {{ Auth::user()->rol }}</small>
        </div>
      </div>
      <!-- search form -->
      {{--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>--}}
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">Menú</li>
		 {{-- <li class="{{(Request::is('administracion')) ? 'active':'' }}"><a href="{{url('/administracion')}}"><i class="fa fa-book"></i> <span>Inicio</span></a></li>--}}
     @if (Auth::user()->rol != "pedidos")
        <li class="treeview {{(Request::is('estadisticas*')) ? 'active':'' }}">
              <a href="#">
                <i class="fa fa fa-area-chart"></i> <span>Estadísticas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{(Request::is('estadisticas/inicio')) ? 'active':'' }}"><a href="{{url('estadisticas/inicio')}}"><i class="fa fa-circle-o "></i> Inicio</a></li>
                <li class="{{(Request::is('estadisticas/pedidos')) ? 'active':'' }}"><a href="{{url('/estadisticas/pedidos')}}"><i class="fa fa-circle-o "></i> Pedidos</a></li>
                <li class="{{(Request::is('estadisticas/incidencias')) ? 'active':'' }}"><a href="{{url('/estadisticas/incidencias')}}"><i class="fa fa-circle-o "></i> Incidencias</a></li>
                <li class="{{(Request::is('estadisticas/productos')) ? 'active':'' }}"><a href="{{url('/estadisticas/productos')}}"><i class="fa fa-circle-o "></i> Productos</a></li>
              </ul>
            </li>
    @endif

    <li class="treeview {{(Request::is('pedidos*')) ? 'active':'' }}">
          <a href="#">
            <i class="fa fa-shopping-bag"></i> <span>Pedidos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::is('pedidos')) ? 'active':'' }}"><a href="{{url('/pedidos')}}"><i class="fa fa-circle-o "></i> Inicio</a></li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-truck"></i> <span>Transportistas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{(Request::is('pedidos/transportista/mrw')) ? 'active':'' }}"><a href="{{url('/pedidos/transportista/mrw')}}"><i class="fa fa-envelope-square" aria-hidden="true"></i> MRW</a></li>
                <li class="{{(Request::is('pedidos/transportista/tipsa')) ? 'active':'' }}"><a href="{{url('/pedidos/transportista/tipsa')}}"><i class="fa fa-envelope-square" aria-hidden="true"></i> TIPSA</a></li>
                <li class="{{(Request::is('pedidos/transportista/ups')) ? 'active':'' }}"><a href="{{url('/pedidos/transportista/ups')}}"><i class="fa fa-envelope-square" aria-hidden="true"></i> UPS</a></li>
                <li class="{{(Request::is('pedidos/transportista/SZENDEX')) ? 'active':'' }}"><a href="{{url('/pedidos/transportista/SZENDEX')}}"><i class="fa fa-envelope-square" aria-hidden="true"></i> SZENDEX</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-envelope-square"></i> <span>Proveedores</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{(Request::is('pedidos/proveedor/DUPS')) ? 'active':'' }}"><a href="{{url('/pedidos/proveedor/DUPS')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> DUPS</a></li>
                <li class="{{(Request::is('pedidos/proveedor/ICOMMERS')) ? 'active':'' }}"><a href="{{url('/pedidos/proveedor/ICOMMERS')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> ICOMMERS</a></li>
                <li class="{{(Request::is('pedidos/proveedor/TIC%20TAC')) ? 'active':'' }}"><a href="{{url('/pedidos/proveedor/TIC%20TAC')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> TIC TAC</a></li>
                <li class="{{(Request::is('pedidos/proveedor/imcogirona')) ? 'active':'' }}"><a href="{{url('/pedidos/proveedor/imcogirona')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> IMCOGIRONA</a></li>
              </ul>
            </li>
            <li class="{{(Request::is('pedidos/no_enviados')) ? 'active':'' }}"><a href="{{url('/pedidos/no_enviados')}}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> NO ENVIADOS</a></li>
            <li class="{{(Request::is('pedidos/nuevo')) ? 'active':'' }}"><a href="{{url('/pedidos/nuevo')}}"><i class="fa fa-plus "></i> Nuevo</a></li>
          </ul>
    </li>




    <li class="{{(Request::is('incidencias*')) ? 'active':'' }}"><a href="{{url('/incidencias')}}"><i class="fa fa-comments-o"></i> <span>Incidencias</span></a></li>

    <li class="treeview {{(Request::is('catalogo*')) ? 'active':'' }}">
          <a href="#">
            <i class="fa fa-archive"></i> <span>Catálogo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::is('catalogo')) ? 'active':'' }}"><a href="{{url('/catalogo')}}"><i class="fa fa-circle-o "></i> Inicio</a></li>
            <li class="{{(Request::is('productos/ver_stock_web')) ? 'active':'' }}"><a href="{{url('/catalogo/ver_stock_web')}}"><i class="fa fa-circle-o"></i> Stock</a></li>
            <li class="{{(Request::is('catalogo/nuevo')) ? 'active':'' }}"><a href="{{url('/catalogo/nuevo')}}"><i class="fa fa-plus "></i> Nuevo producto</a></li>
          </ul>
    </li>


      <li class="treeview {{(Request::is('campanas*')) ? 'active':'' }}">
            <a href="#">
              <i class="fa fa-globe"></i> <span>Campañas</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{(Request::is('campanas')) ? 'active':'' }}"><a href="{{url('/campanas')}}"><i class="fa fa-circle-o "></i> Inicio</a></li>
            </ul>
        </li>

        <li class="{{(Request::is('webs*')) ? 'active':'' }}"><a href="{{url('/webs')}}"><i class="fa fa-laptop"></i> <span>Webs</span></a></li>
        @if(strpos( $url, 'test' ))
        <li class="treeview {{(Request::is('development*')) ? 'active':'' }}">
              <a href="#">
                <i class="fa fa-empire"></i> <span>Development</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{(Request::is('development')) ? 'active':'' }}"><a href="{{url('/development')}}"><i class="fa fa-circle-o "></i> Inicio</a></li>
                <li class="{{(Request::is('development/calculadora')) ? 'active':'' }}"><a href="{{url('/development/calculadora')}}"><i class="fa fa-circle-o "></i> Calculadora</a></li>
              </ul>
            </li>
        @endif
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('titulo_h1','Inicio')
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
	  <li><a href="{{Url('')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
	  <?php $path =  explode("/",Request::path()); ?>
	  @foreach($path as $key => $section)
        <li class="{{($key == (count($path) - 1)) ? '': '' }} active">{{$section}}</li>
		@endforeach
      </ol>
    </section>

    <!-- Main content -->
	@yield('contenido')

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.6
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?> Decowood.</strong>
    @if(strpos( $url, 'test' ))
      <div style="display: flex;opacity: 0.6;position:  fixed;color: #000000;bottom: 20px;left: 20px;border-radius: 50%;background:#e8e8e8;z-index:  10000;font-size: 46px;"><i class="fa fa-empire"></i></div>
    @endif
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{url('/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{url('/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{url('/plugins/morris/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{url('/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{url('/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{url('/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url('/plugins/knob/jquery.knob.js')}}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{url('/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{url('/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{url('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{url('/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('/plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('/js/app.min.js')}}"></script>
<!-- Apprise APP -->
<script type="text/javascript" src="{{url('/js/apprise.min.js')}}"></script>
<!-- JS Mejoras 18/7/2017 by Carlos -->
<script type="text/javascript" src="{{url('/js/functionsDW.js')}}"></script>
<!-- DROPZONEJS -->
<script type="text/javascript" src="{{url('/js/dropzone.js')}}"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>


@yield('scripts')
<style>
div#back-to-top {
  border-radius: 3px;
  opacity: 0.5;
  position: fixed;
  font-size: 20px;
  bottom: 28px;
  right: 0px;
  /* left: 10px; */
  z-index: 1000;
  color: white;
  background: #3c8dbc;
  padding: 0px 10px;
}
  div#back-to-top:hover {
    opacity: 0.8;
}
</style>
<div class="back-to-top" id="back-to-top" style="display: block;">
			<i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<script>
/* back to top */
function scroting(container,speed){
  $('html,body').animate({
       scrollTop: $(container).offset().top},
       speed);
}
  $(window).scroll(function(){
    if($(window).scrollTop() >= 266 ){
      $('#back-to-top').css('display','block');

    }else{
      $('#back-to-top').css('display','none');
    }

  });

  $('#back-to-top').click(function(){
    scroting('body','slow');
});
</script>
<style>
.loader-dw {
  background: rgba(0, 0, 0, 0.68);
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  z-index: 10000;
  }
  .load-modal {
    width: 100%;
    height: 100%;
    position: fixed;
}
.load-modal > h1 {
    color: white;
    text-align: center;
    margin-top: -35%;
}
.load-modal > img{
    top: 0;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    margin: auto;
}

</style>
<div class="loader-dw" style="display:none;">
  <div class="load-modal">
    <img src="/img/loader/loading.svg" alt="Cargando...">
    <h1>Por favor, espere...</h1>
  </div>
</div>
</body>
</html>
