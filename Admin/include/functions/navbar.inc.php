<?php
echo'

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./home.php" class="brand-link">
      <span class="brand-text font-weight-light">SnapX</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">' . $_SESSION['username'] . '</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Index
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="home.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Tables
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="data.php?table=contests" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Contests</p>
                  </a>
                  <a href="data.php?table=requirements" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Requirements</p>
                  </a>
                  <a href="data.php?table=genres" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Genres</p>
                  </a>
                  <a href="data.php?table=photos" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Photos</p>
                  </a>
                  <a href="data.php?table=users" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Users</p>
                  </a>
                  <a href="data.php?table=testimonials" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Testimonials</p>
                  </a>
                  <a href="data.php?table=logs" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logs</p>
                  
              </a>
              </li> 
          </ul>
          </li>';
          echo '
          <li class="nav-header">Admin</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-plus-square"></i>
              <p>
                Admin Actions  
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">';
                  if ($_SESSION['super_user'] != 0) {
                    echo'
                    <a href="register.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Register New Admin</p>
                    </a>
                    ';
                  
                  echo'
                  <a href="data.php?table=admins" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Admins</p>
                  </a>';
                  }
                  echo'
                    <a href="./include/functions/logout.inc.php" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Logout</p>
                    </a>
                    </li>
                </ul> 

          </li> 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

';