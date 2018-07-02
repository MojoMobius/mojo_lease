<nav class="navbar navbar-default">
         <div class="container-fluid">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="#"><?php echo $this->Html->image("../webroot/images/logo.png"); ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">

                    <?php
                    $session = $this->request->session();
                    if($menus){
                    //pr($menus);
                    foreach ($menus as $key => $value) {
                    ?>
                    <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $key?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php
                            foreach ($value as $key1 => $value1) {
                            ?>
                                <li><?php echo $this->Html->link(__($key1, true), array('controller' => $value1, 'action' => '')); ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                    }
					
                    if($session->read('UserRole') == 'QCTL'){
                    ?>
					
                        <li class="dropdown site-menu-item has-sub navbar-brand navbar-brand-center font-size-14 font-weight-100">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b>Dashboard</b><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                    <li><?php echo $this->Html->link(__('QC Dashboard', true), array('controller' => 'Dashboard', 'action' => '')); ?></li>
                            </ul>
                        </li>
                    <?php } 
                    }
					//exit;
                    ?>
					
                </ul>
                <ul class="nav navbar-nav navbar-right" >
                  <li class="dropdown logout-admin">
                      <ul class="nav-right-ul"><li><?php echo date("l, d/m/Y g:i a"); ?></li></ul> 
                   <ul class="nav-right-ul"><li style="float: right;"> <a href="#" class="dropdown-toggle logout" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color:#000;text-decoration:none;font-size:12px;"><?php echo $this->Html->image("oneblack.png"); ?> <?php $session = $this->request->session();echo $session->read('user_name');?> <span class="caret"></span></a>
                     <ul class="dropdown-menu">
                        <li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'Users', 'action' => 'logout')); ?></li>
                     </ul></li></ul>
                    
                  </li>
                    
               </ul>
            </div>
            <!--/.nav-collapse -->
         </div>
         <!--/.container-fluid -->
      </nav>

<script>
$('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(100);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
});
</script>





