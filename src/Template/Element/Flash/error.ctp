<?php
//if (!isset($params['escape']) || $params['escape'] !== false) {
//    $message = h($message);
//}
?>
<!--<div class="message error" onclick="this.classList.add('hidden');"><?//= $message ?></div>-->
<div id="content">
<div class="error_msg alert">
<a href="#" class="close">x</a>
<?php echo $message; ?>
</div></div>

<style type="text/css">
.alert {

border: 1px solid transparent;
border-radius: 4px;
margin-bottom: 20px;
padding: 15px;}

.error_msg {
background-color: #eccecf;
border-color: #9e0b0f;
color: #424242;
text-align: center;}


.close{   text-decoration :none;
 float: right;}
</style>


