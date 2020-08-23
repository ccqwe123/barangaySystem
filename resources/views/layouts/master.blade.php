
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>BRIS</title>
  <link rel="stylesheet" type="text/css" href="/css/app.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <div class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup.enter="searchData" v-model="searhall" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchData">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user"></i>
          {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <p class="text-sm">Profile</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <p class="text-sm">{{ __('Logout') }}</p>
              </div>
            </div>
            <!-- Message End -->
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
    <a href="index3.html" class="brand-link">
      <img src="/images/barangay/barangay_logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;">BRIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/images/users/default.png" class="img-circle elevation-2" alt="User Image">
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
	            <router-link to="/dashboard" class="nav-link">
	              <i class="nav-icon fas fa-tachometer-alt"></i>
	              <p>
	                Dashboard
	              </p>
	            </router-link>
          	</li>
          	<li class="nav-item">
	            <router-link to="/residents" class="nav-link">
	              <i class="nav-icon fas fa-users"></i>
	              <p>
	                Residents
	              </p>
	            </router-link>
          	</li>
          	<li class="nav-item">
	            <router-link to="/barangay" class="nav-link">
	              <i class="nav-icon fas fa-home"></i>
	              <p>
	                Barangay
	              </p>
	            </router-link>
          	</li>
            <li class="nav-item">
              <router-link to="/barangay-officials" class="nav-link">
                <i class="nav-icon fas fa-sitemap"></i>
                <p>
                  Barangay Officials
                </p>
              </router-link>
            </li>
            <li class="nav-item has-treeview">
              <a href="" class="nav-link">
                <i class="nav-icon fas fa-print"></i>
                <p>
                  Certificates
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/business-clearance" class="nav-link">
                      <p>Business Clearance</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/good-moral" class="nav-link">
                      <p>Good Moral Certificate</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/student-certificate" class="nav-link">
                      <p>Student Certificate</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/barangay-indigency" class="nav-link">
                      <p>Indigency Certificate</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/resident-certificate" class="nav-link">
                      <p>Resident Certificate</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/transfer-certificate" class="nav-link disabled">
                      <p>Transfer Certificate</p>
                      <span class="badge badge-warning">Request <br>if needed</span>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/excavation-certificate" class="nav-link disabled">
                      <p>Excavation Certificate</p>
                      <span class="badge badge-warning">Request <br>if needed</span>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <router-link to="/blotter" class="nav-link">
                <i class="nav-icon fas fa-file-signature"></i>
                <p>
                  Blotter
                </p>
              </router-link>
            </li>
          	<li class="nav-item has-treeview">
	            <a href="" class="nav-link">
	              <i class="nav-icon fas fa-chart-line"></i>
	              <p>
	                Reports
                  <span class="badge badge-warning">Soon</span>
	                <i class="fas fa-angle-left right"></i>
	              </p>
	            </a>
	            <ul class="nav nav-treeview">
	              <li class="nav-item">
	                <router-link to="/users" class="nav-link">
	                  <p>Barangay Report</p>
	                </router-link>
	              </li>
	              <li class="nav-item">
	                <router-link to="/users" class="nav-link">
	                  <p>Residents Report</p>
	                </router-link>
	              </li>
	            </ul>
	        </li>
	        <li class="nav-item has-treeview">
	            <a href="" class="nav-link">
	              <i class="nav-icon fas fa-cogs"></i>
	              <p>
	                Settings
	                <i class="right fas fa-angle-left"></i>
	              </p>
	            </a>
	            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/system" class="nav-link">
                      <p>System</p>
                  </router-link>
                </li>
	              <li class="nav-item">
	                <router-link to="/unknown" class="nav-link disabled">
	                  	<p>Activity Logs
                        <span class="badge badge-warning">Soon</span>
                      </p>
	                </router-link>
	              </li>
                <li class="nav-item">
                  <router-link to="/crime-type" class="nav-link">
                      <p>Crime Type</p>
                  </router-link>
                </li>
	              <li class="nav-item">
	                <router-link to="/users" class="nav-link">
	                  	<p>Users</p>
	                </router-link>
	              </li>
	            </ul>
	        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
	  <div class="content-wrapper pt-2">
	    <!-- Main content -->
	    <div class="content">
	      <div class="container-fluid">
	        <router-view></router-view>
	      </div><!-- /.container-fluid -->
	    </div>
	    <!-- /.content -->
	  </div>
</div>
<script src="/js/app.js"></script>
</body>
</html>
