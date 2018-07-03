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
        <?php  // echo $this->Html->meta('mob-icon');
        
		//echo $this->Html->css('login');
        //  echo $this->Html->css('default');
                echo $this->Html->css('bootstrap.min');
                 //  echo $this->Html->css('component');
               
                echo $this->Html->css('date-pic');
                echo $this->Html->css('font-awesome.min');
                echo $this->Html->css('jquery.timepicker');
                  echo $this->Html->css('navbar');
                echo $this->Html->css('jquery-ui');
                //echo $this->Html->css('jqx.apireference');
               // echo $this->Html->css('jqx.base');
                echo $this->Html->css('kendo.common.min');
                //echo $this->Html->css('site');
                
                  // echo $this->Html->css('jquery.smartmenus.bootstrap.css');
                  //echo $this->Html->css('jquery.datetimepicker');
                   // echo $this->Html->css('alertify.min');
                echo $this->Html->css(array('jquery-ui-1.8.4.custom'));
               echo $this->Html->css('style');
                echo $this->Html->css('styles');
                echo $this->Html->css('zebra_datepicker.css');
                echo $this->Html->css('jquery.timepicker.css');
                echo $this->Html->css('jquery.datetimepicker.css');
                 echo $this->Html->css('jquery.dataTables.min.css');
                 echo $this->Html->css('dataTables.bootstrap.min.css');
                 echo $this->Html->css('bootstrap-extend.min.css');
                  //echo $this->Html->css('assets/fonts/font-awesome/font-awesome.min.css');
                 //echo $this->Html->css('bootstrap.css');
               // echo $this->Html->css('workaround');
                //echo $this->Html->css(array('bootstrap-datetimepicker.min'));
                 
				echo $this->Html->css('ui.jqgrid');
				echo $this->Html->css('themes/redmond/jquery-ui.custom.css');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
               
                echo $this->Html->script('jquery-1.12.4');
                echo $this->Html->script('bootstrap.min');
                //echo $this->Html->script('jquery-2.1.3');
                echo $this->Html->script('tableHeadFixer');
                
                echo $this->Html->script('jquery.min');
                echo $this->Html->script('validation');
                //echo $this->Html->script('jquery.timepicker');
                
                echo $this->Html->script('jquery-ui');
                // echo $this->Html->script('jquery.datetimepicker');
                echo $this->Html->script('zebra_datepicker');
                 echo $this->Html->script('jquery.timepicker');
                 echo $this->Html->script('jquery.timepicker.min.js');
                 echo $this->Html->script('jquery.datetimepicker');
                echo $this->Html->script('kendo.web.min');
              //  echo $this->Html->script(array('jquery.min'));
		echo $this->Html->script(array('jqgrid/js/i18n/grid.locale-en.js'));
                echo $this->Html->script(array('jqgrid/js/jquery.jqGrid.min.js'));
                
                echo $this->Html->script(array('jquery.dataTables.min.js'));
               echo $this->Html->script(array('dataTables.bootstrap.min.js'));
                        echo $this->Html->script(array('shortcut.js'));
              
        ?>

        <script>

            $(function () {
                $('#FromTime').timepicker({
                    timeFormat: 'H:i:s',
                    step: 05,
                    scrollDefault: 'now'
                });

                $('#ToTime').timepicker({
                    timeFormat: 'H:i:s',
                    step: 05,
                    scrollDefault: 'now'
                });
            });

            // Function for Fade out the success & failure message.
            $(document).ready(function () {

                $('#batch_from').Zebra_DatePicker({
                    format: 'd-m-Y',
                    pair: $('#batch_to'),
                    onChange: function (view, elements) {
                        $('#batch_to').val('');

                    }
                });

                $('#batch_to').Zebra_DatePicker({
                    direction: true,
                    format: 'd-m-Y'
                });

                $('#QueryDateFrom').Zebra_DatePicker({
                    format: 'd-m-Y',
                    pair: $('#QueryDateTo'),
                    onChange: function (view, elements) {
                        $('#QueryDateTo').val('');

                    }
                });


                $('#QueryDateTo').Zebra_DatePicker({
                    direction: true,
                    format: 'd-m-Y'
                });


                if ($("#batch_date").length) {
                    $('#batch_date').Zebra_DatePicker({
                        format: 'd-m-Y'
                    });
                }

                if ($("#batch_date").length || $("#batch_to").length || $("#batch_from").length) {
                    var datepicker1 = $('#batch_to , #batch_from , #batch_date').data('Zebra_DatePicker');

                    setTimeout(function () {
                        $(".flash_good , .flash_bad").fadeOut("slow", function () {
                            $(".flash_good , .flash_bad").remove(datepicker1.update());
                        });
                    }, 2000);
                }

                setTimeout(function () {
                    $(".flash_good , .flash_bad").fadeOut("slow", function () {
                    });
                }, 2000);

            });

        </script>

        <script type="text/javascript">
            $(document).ready(function () {

                $('#content .close').click(function () {
                    $(this).parent().fadeOut();
                    return false;
                });


                $('.alert').animate({opacity: 1.0}, 3000).fadeOut();
            });
        </script>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <?php
            echo $this->element('header');
            echo $this->element('menu');
        ?>
        <?= $this->Flash->render(); ?>
        <div class="container clearfix">
        <?= $this->fetch('content') ?>
        </div>
        <footer class="footer">
            <?php $Date = date("Y-m-d H:i:s");
             $CurrentYear = date("Y", strtotime($Date));  ?>
            <p class="pull-left">© <?php echo "$CurrentYear"; ?> Mojo 3.0 All Rights Reserved.</p><p class="pull-right">Powered By <a target="_blank" href="https://www.mobiusservices.com" class="footerlink">Mobius Knowledge Services</a></p>
        </footer>
        <?php //echo $this->element('sql_dump'); ?>
    </body>
</html>
