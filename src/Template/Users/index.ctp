<body>
  <div class="logo"></div>
  <div class="login"> <!-- Login -->

    <h1><?php echo $this->Html->image('mob_logo.png', array('alt' => 'CakePHP'));?></h1>

    <form class="form log-for" method="post" action="">

      <p class="field">
        <input type="text" name="login" placeholder="Username or email" required/>
        <i class="fa fa-user"></i>
      </p>

      <p class="field">
        <input type="password" name="password" placeholder="Password" required/>
        <i class="fa fa-lock"></i>
      </p>

      <p class="submit"><input type="submit" name="commit" value="Login"><div class="logo_img"><?php //echo $this->Html->image('mob_logo.png', array('alt' => 'CakePHP'));?>&nbsp;<?php //echo $this->Html->image('copy_right.png', array('alt' => 'CakePHP'));?></div></p>

     
    </form>
  </div> <!--/ Login-->
  
</body>
