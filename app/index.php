<?php 
	$args=array(
		'url'			=> $sphinx->base_url('assets/'),
		'base_url'			=> $sphinx->base_url(),
		'system_name'	=> $sphinx->system_name,
		'get_entity'	=> $sphinx->get_entity_type()
	);
 ?>
 <?php 
  $setting=$sphinx->row($sphinx->fetch("settings",array('setting_key'=>'login')));
  if ($setting['setting_value']=='enable') {
    $sphinx->is_login('user_id','app/login','user_session');
  }
  ?>
<?php $sphinx->get_header(null,$args); ?>
<?php $sphinx->get_header('menu',$args); ?>
<?php $sphinx->get_header('side_menubar',$args); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->


        <div class="row">
          
        </div>
        <!-- /.row -->
   
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php $sphinx->get_footer(); ?>

