<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//echo 'coming';
$cakeDescription = 'MOJO 2.0';
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('mob-icon') ?>
        <?php   
                
//----------------------------------
        //-- Stylesheets --
        echo $this->Html->css('bootstrap/global/css/bootstrap.min.css');
        echo $this->Html->css('bootstrap/global/css/bootstrap-extend.min.css');
        echo $this->Html->css('bootstrap/assets/css/site.css');
        
        //-- Plugins --
        //echo $this->Html->css('bootstrap/global/vendor/animsition/animsition.css');
        echo $this->Html->css('bootstrap/global/vendor/asscrollable/asScrollable.css');
        echo $this->Html->css('bootstrap/global/vendor/slidepanel/slidePanel.css');
        echo $this->Html->css('bootstrap/assets/examples/css/uikit/modals.css');
        echo $this->Html->css('bootstrap/global/vendor/jquery-selective/jquery-selective.css');
        
        echo $this->Html->css('bootstrap/assets/examples/css/uikit/buttons.css');
        echo $this->Html->css('bootstrap/global/vendor/jquery-wizard/jquery-wizard.min.css');
        echo $this->Html->css('bootstrap/global/vendor/formvalidation/formValidation.min.css');
        echo $this->Html->css('bootstrap/assets/fonts/web-icons/web-icons.min.css');
        echo $this->Html->css('bootstrap/assets/fonts/font-awesome/font-awesome.css');
        echo $this->Html->css('bootstrap/assets/fonts/font-awesome/font-awesome.min.css');
        echo $this->Html->css('bootstrap/assets/fonts/Roboto/Roboto.min.css');
        echo $this->Html->css('bootstrap/global/fonts/web-icons/web-icons.min.css');
        echo $this->Html->css('bootstrap/global/fonts/brand-icons/brand-icons.min.css');
        echo $this->Html->css('http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic');
        //echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/webicons/2.0.0/webicons.min.css');
        echo $this->Html->css('bootstrap/assets/css/jquery.enhsplitter.css');

//----------------------------------
                
//        echo $this->Html->script(array('https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js'));
        echo $this->Html->script(array('bootstrap/global/global/js/jquery.min.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/breakpoints/breakpoints.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/babel-external-helpers/babel-external-helpers.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/jquery/jquery.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/tether/tether.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/bootstrap/bootstrap.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/animsition/animsition.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/mousewheel/jquery.mousewheel.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/asscrollbar/jquery-asScrollbar.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/asscrollable/jquery-asScrollable.js'));
        //-- Plugins -->
        echo $this->Html->script(array('bootstrap/global/vendor/switchery/switchery.min.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/intro-js/intro.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/screenfull/screenfull.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/slidepanel/jquery-slidePanel.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/formvalidation/formValidation.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/formvalidation/framework/bootstrap.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/matchheight/jquery.matchHeight-min.js'));
        echo $this->Html->script(array('bootstrap/global/vendor/jquery-wizard/jquery-wizard.js'));
        //-- Scripts -->
        echo $this->Html->script(array('bootstrap/global/js/State.js'));
        echo $this->Html->script(array('bootstrap/global/js/Component.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin.js'));
        echo $this->Html->script(array('bootstrap/global/js/Base.js'));
        echo $this->Html->script(array('bootstrap/global/js/Config.js'));
        echo $this->Html->script(array('bootstrap/assets/js/Section/Menubar.js'));
        echo $this->Html->script(array('bootstrap/assets/js/Section/Sidebar.js'));
        echo $this->Html->script(array('bootstrap/assets/js/Section/PageAside.js'));
        echo $this->Html->script(array('bootstrap/assets/js/Plugin/menu.js'));
        //-- Config -->
        echo $this->Html->script(array('bootstrap/global/js/config/colors.js'));
        echo $this->Html->script(array('bootstrap/assets/js/config/tour.js'));
        //-- Page -->
        echo $this->Html->script(array('bootstrap/assets/js/Site.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin/asscrollable.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin/slidepanel.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin/switchery.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin/jquery-wizard.js'));
        echo $this->Html->script(array('bootstrap/global/js/Plugin/matchheight.js'));
        echo $this->Html->script(array('bootstrap/assets/examples/js/forms/wizard.js'));
        echo $this->Html->script(array('https://www.jqueryscript.net/demo/Minimal-Overlaying-Off-canvas-Plugin-With-jQuery-Iptools-Offcanvas/dist/iptools-jquery-offcanvas.min.js'));
        //-- Flip slip canvas - -->
        echo $this->Html->script(array('bootstrap/assets/js/Splitter.js'));
        echo $this->Html->script(array('bootstrap/assets/js/jquery.enhsplitter.js'));
//        echo $this->Html->script(array('https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js'));
//        echo $this->Html->script(array('https://www.jqueryscript.net/demo/Minimal-Overlaying-Off-canvas-Plugin-With-jQuery-Iptools-Offcanvas/dist/iptools-jquery-offcanvas.min.js'));
        
//----------------------------------
            
        ?>
        <script>
            Config.set('assets', '../../assets');
        </script>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        
        <?php
            echo $this->element('header');
            echo $this->element('bootstrap-menu');
        ?>
        <?= $this->Flash->render(); ?>
        <div class="container clearfix">
        <?= $this->fetch('content') ?>
        </div>
<!--        <footer class="footer">
            <?php $Date = date("Y-m-d H:i:s");
             $CurrentYear = date("Y", strtotime($Date));  ?>
            <p class="pull-left">© <?php echo "$CurrentYear"; ?> Mojo 3.0 All Rights Reserved.</p><p class="pull-right">Powered By <a href="www.mobiusservices.com" class="footerlink">Mobius Knowledge Services</a></p>
        </footer>-->
        <!-- Footer -->
            <footer class="site-footer">
                <div class="site-footer-legal">© 2018 <a href="#">Mojo</a></div>  
            </footer>
        <!-- Footer -->
        <?php //echo $this->element('sql_dump'); ?>
    </body>
</html>