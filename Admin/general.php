<?php include './include/config.inc.php'; ?>
<?php include './include/functions/checkTable.inc.php'; ?>
<?php include './include/functions/checkActions.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | General UI</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">

  
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php include './include/functions/navbar.inc.php'; ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
      <div class="card card-primary">
<div class="card-header">
<h3 class="card-title"><?php echo $_GET['action'] ?> <?php echo $_GET['table'] ?></h3>
</div>


<form method="POST" action="./include/functions/SQLfunctions.inc.php" enctype="multipart/form-data">

  <?php
    if($_GET['action'] == 'edit'){
      editTable();
    } else if($_GET['action'] == 'insert'){
      insertTable("fixe");
    } 
  ?>

  <div class="card-footer">
  <button type="submit" class="btn btn-primary" name="<?php echo $_GET['action'] ?>">Submit</button>
  </div>
  </form>
  </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>

</body>
</html>
