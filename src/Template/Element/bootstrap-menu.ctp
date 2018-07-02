    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega navbar-inverse" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided" data-toggle="menubar">
                <span class="sr-only">Toggle navigation</span>
                <span class="hamburger-bar"></span>
            </button>
            <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
                <i class="fa fa-ellipsis-h fa-1" aria-hidden="true"></i>
            </button>
            <a class="navbar-brand navbar-brand-center" href="#">
                <span class="navbar-brand-text"> Mojo</span>
            </a>
            <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
                <span class="sr-only">Toggle Search</span>
                <i class="icon fa fa-search" aria-hidden="true"></i>
            </button>
        </div>
        <div class="navbar-container container-fluid">
            <!-- Navbar Collapse -->
            <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
                <!-- Navbar Toolbar -->
                <ul class="nav navbar-toolbar">
                    <li class="nav-item hidden-float" id="toggleMenubar">
                        <a class="nav-link" data-toggle="menubar" href="#" role="button">
                            <i class="icon hamburger hamburger-arrow-left">
                                <span class="sr-only">Toggle menubar</span>
                                <span class="hamburger-bar"></span>
                            </i>
                        </a>
                    </li>
                    <?php foreach ($menus as $key => $value) { ?>
                        <li class="dropdown site-menu-item has-sub navbar-brand navbar-brand-center font-size-14 font-weight-100">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><b><?php echo $key?></b><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($value as $key1 => $value1) { ?>
                                    <li><?php echo $this->Html->link(__($key1, true), array('controller' => $value1, 'action' => '')); ?></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <!-- End Navbar Toolbar -->
                <!-- Navbar Toolbar Right -->
                <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up" aria-expanded="false" role="button">
                            <span class="flag-icon flag-icon-us"></span>
                        </a>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><span class="flag-icon flag-icon-gb"></span> English</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><span class="flag-icon flag-icon-fr"></span> French</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><span class="flag-icon flag-icon-cn"></span> Chinese</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><span class="flag-icon flag-icon-de"></span> German</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><span class="flag-icon flag-icon-nl"></span> Dutch</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
<!--                            <span class="avatar avatar-online">
                                <img src="images/assets/images/5.jpg" alt="...">
                                <i></i>
                            </span>-->
                            
                            
                                
<!--                            <ul class="nav-right-ul"><li><?php echo date("l, d/m/Y g:i a"); ?></li></ul> 
                            <ul class="nav-right-ul">
                                <li style="float: right;"> -->
                                    <a href="#" class="dropdown-toggle logout" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color:#fff;text-decoration:none;font-size:13px;">
                                        <?php echo $this->Html->image("oneblack.png"); ?> <?php $session = $this->request->session();echo $session->read('user_name');?>
                                        <span class="caret"></span>
                                    </a>
<!--                                    <ul class="dropdown-menu">
                                    <li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'Users', 'action' => 'logout')); ?></li>
                                    </ul>-->
<!--                                </li>
                            </ul>-->
                                
                            
                            
                        </a>
<!--                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon fa fa-user" aria-hidden="true"></i> Profile</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon fa fa-credit-card-alt" aria-hidden="true"></i> Billing</a>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon fa fa-gear" aria-hidden="true"></i> Settings</a>
                            <div class="dropdown-divider" role="presentation"></div>
                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon fa fa-power-off" aria-hidden="true"></i> Logout</a>
                        </div>-->
                        <div class="dropdown-menu" role="menu">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <i class="icon fa fa-power-off" aria-hidden="true" style="text-decoration: none">
                                <?php echo $this->Html->link(__('Logout', true), array('controller' => 'Users', 'action' => 'logout')); ?>
                            </i>
                        </div>
                    </li>
                </ul>
            <!-- End Navbar Toolbar Right -->
            </div>
            <!-- End Navbar Collapse -->
            <!-- Site Navbar Seach -->
            <div class="collapse navbar-search-overlap" id="site-navbar-search">
                <form role="search">
                    <div class="form-group">
                        <div class="input-search">
                            <i class="input-search-icon fa fa-search" aria-hidden="true"></i>
                            <input type="text" class="form-control" name="site-search" placeholder="Search...">
                            <button type="button" class="input-search-close fa fa-times fa-1" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
                        </div>
                    </div>
                </form>
            </div>
          <!-- End Site Navbar Seach -->
        </div>
    </nav>
    