<?php //echo $this->Html->script('jquery'); 
echo $this->Html->script('jquery-1.8.2');
echo $this->Html->script('sleetmute');?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->

    <head>
        <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <meta charset="utf-8">
 
  <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $this->fetch('title');?></title>
        <?php
        
        echo $this->Html->css('login');
        echo $this->Html->script('jquery.min');
            //echo $this->Html->script('login.js');
        //$access = $this->Session->read("uid");
        ?>
        <script type="text/javascript">
 $(document).ready(function(){

  $('#content .close').click(function(){
   $(this).parent().remove();
   return false; });


  $('.alert').animate({opacity: 1.0}, 3000).fadeOut();
 });
 </script>
    </head><link rel="icon" href="../webroot/images/mob-icon.ico">
    <body>
	 <section class="material-half-bg">
      <div class="cover"></div>
    </section>
  <?= $this->Flash->render(); ?>
    <section class="login-content">
  <div class="login"> <!-- Login -->

     
 <div class="login-box">
    <form class="form log-for login-form" method="post" action="">
	 <h1><?php echo $this->Html->image('../webroot/images/logo1.png', array('alt' => 'CakePHP'));?></h1>
  <div class="form-group">
   <label>Username or email</label>
      <p class="field">
        <input type="text" name="login" placeholder="Username or email" required/>
        <i class="fa fa-user"></i>
      </p>
</div>
  <div class="form-group">
    <label>Password</label>
      <p class="field">
        <input type="password" name="password" placeholder="Password" required/>
        <i class="fa fa-lock"></i>
      </p>
</div>
      <p class="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i> <input type="submit" name="commit" value="Login"><div class="logo_img"><?php //echo $this->Html->image('mob_logo.png', array('alt' => 'CakePHP'));?>&nbsp;<?php //echo $this->Html->image('copy_right.png', array('alt' => 'CakePHP'));?></div></p>

     
    </form>
	</div>
  </div> <!--/ Login-->
  </section>
</body>
</html>
