<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php echo $title ?> | <?php echo $this->config->item('site_name') ?></title>
		<!--  Meta Data  -->		
        <meta name="description" content="">
        <meta name="author" content="">
		<!-- CSS Stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Oswald:400,300,700" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>css/fontawesome.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>css/style.css" media="screen" rel="stylesheet" type="text/css" />
        
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
         <a class="brand" href="<?php echo base_url(); ?>index.php">DailyWhois.Com</a>
        <ul class="nav pull-right">
          <li><a href="#index.php" class="a-1">Home<br></a>
          </li>
          <li><a href="#login.php" class="a-1">Login<br></a>
          </li>
        </ul>
      </div>
      </div>
    </div>
                <?php echo $contents ?>
				
	
	<div class="container-fluid container-fluid-2">
      <div class="container">
        <div class="row-fluid">
          <span class="span12">
            <div class="pull-left">
              <p style="line-height: 20px;margin-top:8px;">
                &nbsp;&nbsp;  blogspot.Domains  2014. <br>
              </p>
            </div>
            <ul class="nav nav-pills pull-right">
              <li><a class="a-2" href="#privacy.php">Privacy</a>
              </li>
              <li><a class="a-2" href="#terms.php">Terms Of Service</a>
              </li>
            </ul>
          </span>
        </div>
      </div>
    </div>
	
            
    </body>
</html>
