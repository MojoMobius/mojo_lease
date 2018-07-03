<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head> 

  <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  

 


<body>
  <div class="logo"></div>
  <div class="login"> <!-- Login -->

    <h1><?php echo $this->Html->image('logo.png', array('alt' => 'CakePHP'));?></h1>

    <form class="form" method="post" action="">

      <p class="field">
        <input type="text" name="login" placeholder="Username or email" required/>
        <i class="fa fa-user"></i>
      </p>

      <p class="field">
        <input type="password" name="password" placeholder="Password" required/>
        <i class="fa fa-lock"></i>
      </p>

      <p class="submit"><input type="submit" name="commit" value="Login"><div class="logo_img"><?php echo $this->Html->image('mob_logo.png', array('alt' => 'CakePHP'));?>&nbsp;<?php echo $this->Html->image('copy_right.png', array('alt' => 'CakePHP'));?></div></p>

     
    </form>
  </div> <!--/ Login-->
  
</body>
</html>