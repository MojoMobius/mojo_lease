<?php
//if (!isset($params['escape']) || $params['escape'] !== false) {
//    $message = h($message);
//}
?>
<!--<div class="message success" onclick="this.classList.add('hidden')"><?//= $message ?></div>-->
<div id="content">
<div class="success_msg alert">
    <a href="#" class="close">x</a>
<?php echo $message; ?>
</div></div>

<style type="text/css">
.alert {

border: 1px solid transparent;
border-radius: 4px;
margin-bottom: 20px;
padding: 15px;}

.success_msg {
background-color: #dff0d8;
border-color: #d6e9c6;
color: #3c763d;
text-align: center;}


.close{   text-decoration :none;
 float: right;}
</style>
