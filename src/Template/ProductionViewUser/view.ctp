<?php

use Cake\Routing\Router;
if($NoNewJob=='NoNewJob') {
?>
<br><br>
<div align="center" style="color:green;">
    <b>
            <?php echo 'No New Job Available Now! <br> Check Later to have new job!';?>
    </b>
    <br><br>

</div>
<?php   
}
else if($this->request->query['job']=='completed' || $this->request->query['job']=='Query')
{
?>
<br><br>
<div align="center" style="color:green;">
    <b>
                <?php
                if($this->request->query['job']=='completed')
                 echo 'Job completed.<br>';
                 else
                    echo 'Query Posted Successfully.<br>';
                ?>

                <?php echo 'Click Get Job Button to get new Job';?>
    </b>
    <br><br>
    <div style="margin:0px 0px 5px 0px;">
        <button class="btn btn-default btn-sm" type="button" onclick="getJob()">Get Job</button>
    </div>
</div>
<br><br>   
         <?php
}
else if($getNewJOb=='getNewJOb' ) {
        echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms'));
        ?>
<br><br>
<div align="center" style="color:green;">
    <b>
            <?php echo 'Click Get Job Button to get new Job';?>
    </b>
    <br><br>
    <div style="margin:0px 0px 5px 0px;">
            <?php echo $this->Form->button('GetJob', array( 'id' => 'NewJob', 'name' => 'NewJob', 'value' => 'NewJob','class'=>'btn btn-default btn-sm')); ?>
    </div>
</div>
        <?php
     echo $this->Form->end();   
}
else
{
    echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms','name'=>'getjob'));
?>
<input type="hidden" name='loaded' id='loaded' >
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-top:10px;">
    <div class="container-fluid">

        <div class="panel panel-default formcontent">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h3 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration:none;">
                        <i class="more-less glyphicon glyphicon-plus"></i>
                        Production
                    </a> <!-- <span class="buttongrp">    
                        <?php //$Back = $this->Html->link('Back', ['controller'=>'productionview','action' => 'index']); ?>
<!--                         <span  class="btn btn-primary btn-xs pull-right"  style="margin-top:-4px;"><?php //echo $Back ?></span>
                         <span  class="btn btn-primary btn-xs pull-right"  style="margin-top:-4px;"><a onclick="history.go(-1);">Back</a></span>
                     </span> -->
                    </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-6
                                   control-label"><b><?php echo $moduleName;?> process</b></label>
                            <div class="col-sm-6">
                                &nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group"> <?php foreach ($StaticFields as $key => $value) { ?>

                            <a style="color:#555b86 !important;"
                               href="#"><u><?php echo $value['DisplayAttributeName']; ?>:<?php echo $productionjob[$value['AttributeMasterId']];?></u></a>

              <?php } ?></div>

                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-6
                                   control-label">Time Taken</label>
                            <div class="col-sm-6" style="margin-top:5px">
                                <a href="#">
<!--                                     <span class="badge" id='countdown'>-->
                                         <?php $hrms=explode('.',$TimeTaken);
                                         echo $hrms[0];?>
                                    <!--                                    </span>-->
                                     <?php echo $this->Form->input('', array( 'type'=>'hidden','id' => 'TimeTaken', 'name' => 'TimeTaken','value' => $hrms[0])); ?>
                                </a><br>
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3
                                   control-label"><b>Status</b></label>
                            <div class="col-sm-8">
                                <label for="inputEmail3" class="col-sm-9
                                       control-label">Production Completed</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2
                                   control-label">&nbsp;</label>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                        </div>
                    </div>

                </div>
                <?php if(!empty($DynamicFields)){?>
                <div id="top-pane" readonly="readonly">
                    <div class="pane-content" style="width:99.7%;" >
                        <div class="form-horizontal">
                            <div class="form-group form-group-sm form-inline" id='appendNew' style="overflow-x: scroll;overflow-y:hidden !important; white-space: nowrap;padding-bottom: 15px;">
                                <?php foreach ($DynamicFields as $key => $value) { ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-6
                                               control-label"><b><?php echo $value['DisplayAttributeName'];?></b></label>
                                        <input type="hidden" name="CommonAM[<?php echo $value['ProjectAttributeMasterId'];?>]" id="CommonAM[<?php echo $value['ProjectAttributeMasterId'];?>]" value="<?php echo $dynamicData[$value['AttributeMasterId']];?>">

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">

                                        <div class="col-sm-6">
            <!--                                <input id="1390" class="form-control" size="40" name="1390" value="" onblur="('1390', this.value, '', '', '', '')" maxlength="" minlength="" type="text">-->
                                                        <?php if ($value['ControlName'] == 'TextBox' || $value['ControlName'] == 'Label') { ?>
                                            <input readonly="readonly" class="form-control" type="text"  size="40" title="<?php echo $value['AttributeMasterId']; ?>" name="<?php echo $value['AttributeMasterId']; ?>" id="CommonPAM[<?php echo $value['ProjectAttributeMasterId']; ?>]" onblur="<?php echo $value['FunctionName']; ?>('CommonPAM[<?php echo $value['ProjectAttributeMasterId']; ?>]', this.value, '<?php echo $value['AllowedCharacter']; ?>', '<?php echo $value['NotAllowedCharacter']; ?>')" value="<?php echo $dynamicData[$value['AttributeMasterId']]; ?>">
                                                        <?php } elseif ($value['ControlName'] == 'DropDownList') { ?>
                                            <select disabled="disabled" class="form-control" name="<?php echo $value['AttributeMasterId']; ?>" id="CommonPAM[<?php echo $value['ProjectAttributeMasterId']; ?>]">
                                                <option value="yes" <?php
                                                                if ($value['AttributeValue'] == 'yes') {
                                                                    echo 'Selected';
                                                                }
                                                                ?>>Yes</option>
                                                <option value="no" <?php
                                                                if ($value['AttributeValue'] == 'no') {
                                                                    echo 'Selected';
                                                                }
                                                                ?>>No</option>
                                            </select>
                                                        <?php } elseif ($value['ControlName'] == 'MultiTextBox') { ?>
                                            <textarea disabled="disabled" title="<?php echo $value['AttributeValue']; ?>"  name="<?php echo $value['AttributeMasterId']; ?>" id="CommonPAM[<?php echo $value['ProjectAttributeMasterId']; ?>]" onblur="<?php echo $value['FunctionName']; ?>('CommonPAM[<?php echo $value['ProjectAttributeMasterId']; ?>]', this.value, '<?php echo $value['AllowedCharacter']; ?>', '<?php echo $value['NotAllowedCharacter']; ?>')"><?php echo $dynamicData[$value['AttributeValue']]; ?></textarea>
                                                        <?php } elseif ($value['ControlName'] == 'RadioButton') { ?>
                                                            <?php echo $value['AttributeValue']; ?>
                                                    <?php } 
                                                    
                                         ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>


                            </div>

                        </div>
                    </div>
                </div>
                            <?php } ?>
            </div>
        </div>    </div></div>


      <?php pr($productionjob);?>
<input type="hidden" name='ProductionId' id="ProductionId" value="<?php echo $productionjob['Id'];?>">
<input type="hidden" name='ProductionEntity' id="ProductionEntity" value="<?php echo $productionjob['InputEntityId'];?>">
<input type="hidden" name='StatusId' value="<?php echo $productionjob['StatusId'];?>">
<input type="hidden" name='RegionId' id='RegionId' value="<?php echo $productionjob['RegionId'];?>">
<input type="hidden" name="ADDNEW" id="ADDNEW" value="">
                <?php
                echo $this->Form->input('', array( 'type'=>'hidden','id' => 'addnewActivityChange', 'name' => 'addnewActivityChange','value'=>$addnewActivityChange));
                echo $this->Form->input('', array( 'type'=>'hidden','id' => 'page', 'name' => 'page','value'=>$page));
                echo $this->Form->input('', array( 'type'=>'hidden','id' => 'prevPage', 'name' => 'prevPage','value'=>$this->request->params[paging][GetJob][prevPage]));
                echo $this->Form->input('', array( 'type'=>'hidden','id' => 'nextPage', 'name' => 'nextPage','value'=>$this->request->params[paging][GetJob][nextPage]));
                ?>
<div id="example" class="container-fluid" style="margin-bottom:-15px;">
    <div id="vertical">
        <div id="top-pane" class="tap-pane">
            <div id="horizontal" style="height: 100%; width: 100%;">
                <div id="left-pane">
                    <div class="pane-content">
                        <!-- Load pdf file starts -->
                        <div style="margin-top:10px;"><iframe onload="onMyFrameLoad(this)" id="frame" sandbox="" src="<?php echo $FirstLink;?>"></iframe>
                        </div> 
                        <!-- Load pdf file ends-->
                    </div>
                </div>

                <div id="right-pane">
                    <?php
                         
                                                    $previous='true';
                                                    if($page>1)
                                                        $previous='false';
                                                     $next='disabled="disabled"';
                                                    if($page<$SequenceNumber)
                                                        $next='';
                          ?>                          
                    <div class="col-md-12">
                        <div class="col-md-6 pull-left" style="width:56%;">
                        <?php  echo $this->Form->input('',array('options' => $Html, 'id'=>'status', 'name' => 'status', 'class'=>'form-control pull-left','style'=>'margin-left:-10px;margin-top: 5px;','onchange' =>'LoadPDF(this.value);')); ?>
                        </div>

                        <div class="pull-right" style="cursor:pointer;padding-top:5px;">
                            <button class="btn btn-primary btn-xs " name='gopdf' id='gopdf' onclick="OpenPdf();" type="button">Go</button>
                            <button class="btn btn-primary btn-xs " name='pdfPopUP' id='pdfPopUp' onclick="PdfPopup();" type="button">Undock</button>
                            <button class="btn btn-primary btn-xs" type="button" id="clicktoviewPre" name="clicktoviewPre" value='clicktoviewPre' disabled='<?php echo $previous;?>' onclick="loadNext('previous');">&lt;&lt;</button>
                            <button class="btn btn-primary btn-xs" type="button" id="clicktoviewNxt" name="clicktoviewNxt" value="Next" <?php echo $next; ?> onclick="loadNext('next');" >&gt;&gt;</button>
                            <!--                                <button class="btn btn-primary btn-xs" type="button" onclick="DeletePage();">X</button>-->
                            <button class="btn btn-primary btn-xs" type="button"> No of pages <span class="badge" id="SequenceNumber"><?php echo $SequenceNumber;?></span> </button> 
                        </div>
                    </div>
                    <input type="hidden" name="seq" id="seq" value='<?php echo $SequenceNumber;?>' > 
                    <div class="form-inline">
                            <?php 
                           // pr($ProductionFields);
                            foreach ($ProductionFields as $key => $value) {
                                $value['SequenceNumber']=1;
                                ?>

                        <div class="form-group mar" style="margin-left:20px;">
                            <p class="form-control-static col-xs-6 for-wid"><?php echo $value['DisplayAttributeName']; ?></p>
                                                    <?php if($value['ControlName']=='TextBox' || $value['ControlName']=='Label') { ?>
                            <input readonly="readonly" type="text" class="form-control" size="40"  name="<?php echo $value['AttributeMasterId']; ?>" id="<?php echo $value['AttributeMasterId']; ?>" value="<?php echo $productionjob[$value['AttributeMasterId']];?>" onblur="<?php echo $value['FunctionName'];?>('<?php echo $value['AttributeMasterId'];?>', this.value, '<?php echo $value['AllowedCharacter'];?>', '<?php echo $value['NotAllowedCharacter'];?>', '<?php echo $value['Dateformat'];?>', '<?php echo $value['AllowedDecimalPoint'];?>')" maxlength="<?php echo $value['MaxLength'];?>" minlength="<?php echo $value['MinLength'];?>">
                                                    <?php } elseif($value['ControlName']=='DropDownList') { ?>
                            <select disabled="disabled" class="form-control"  name="<?php echo $value['AttributeMasterId']; ?>" id="<?php echo $value['AttributeMasterId']; ?>" value="<?php echo $productionjob[$value['AttributeMasterId']];?>" onblur="<?php echo $value['FunctionName'];?>('<?php echo $value['AttributeMasterId'];?>', this.value, '<?php echo $value['AllowedCharacter'];?>', '<?php echo $value['NotAllowedCharacter'];?>', '<?php echo $value['Dateformat'];?>', '<?php echo $value['AllowedDecimalPoint'];?>')" onchange="<?php echo $value['Reload'];?>" >
                                <option value="0">--select--</option>
                                                    <?php foreach($value['Options'] as $key_opt=>$opt) { ?>
                                <option value="<?php echo $key_opt;?>" <?php if($productionjob[$value['AttributeMasterId']]==$key_opt) { echo 'Selected';} ?>><?php echo $opt;?></option>
                                                    <?php } ?>
                            </select>
                                                    <?php } elseif($value['ControlName']=='MultiTextBox') { ?>
                            <textarea title="<?php echo $value['AttributeValue'];?>"  name="<?php echo $value['AttributeMasterId']; ?>" id="<?php echo $value['AttributeMasterId']; ?>" onblur="<?php echo $value['FunctionName'];?>('<?php echo $value['AttributeMasterId'];?>', this.value, '<?php echo $value['AllowedCharacter'];?>', '<?php echo $value['NotAllowedCharacter'];?>', '<?php echo $value['Dateformat'];?>', '<?php echo $value['AllowedDecimalPoint'];?>')"><?php echo $productionjob[$value['AttributeMasterId']];?></textarea>
                                                    <?php } elseif($value['ControlName']=='RadioButton') {?>
                                                    <?php } elseif($value['ControlName']=='Auto') { ?>
                            <input readonly="readonly" type="text" class="form-control" size="40"  name="<?php echo $value['AttributeMasterId']; ?>" id="<?php echo $value['AttributeMasterId']; ?>" value="<?php echo $productionjob[$value['AttributeMasterId']];?>" onblur="<?php echo $value['FunctionName'];?>('<?php echo $value['AttributeMasterId'];?>', this.value, '<?php echo $value['AllowedCharacter'];?>', '<?php echo $value['NotAllowedCharacter'];?>', '<?php echo $value['Dateformat'];?>', '<?php echo $value['AllowedDecimalPoint'];?>')" maxlength="<?php echo $value['MaxLength'];?>" minlength="<?php echo $value['MinLength'];?>">
                                                    <?php } ?>
                        </div>




				<?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
if($QueryDetails['StatusID']==1) {
?>
    <div id="popup1" class="overlay">
        <div class="popup">
            <div id='successMessage' align='center' style='display:none;color:green'><b>Query Successfully Posted!</b></div>
            <h2>Query</h2>
            <a class="close" href="#">&times;</a>
            <div class="content">
                <table style="width:100%">
                    <tr><td style="width:50%">Query</td><td><textarea name="query" id="query" rows="5" cols="35"><?php echo $QueryDetails['Query'];?></textarea></td></tr>
                    <tr>
                        <td></td>
                        <td><input type="hidden" name="ProductionEntity" id="ProductionEntity" value="<?php echo $productionjob['InputEntityId'];?>"> 
                                <?php echo $this->Form->button('Submit', array( 'id' => 'Query', 'name' => 'Query', 'value' => 'Query','class'=>'btn btn-primary btn-sm','onclick'=>"return valicateQuery();",'type'=>'button')).' '; 
                           //echo $this->Form->button('Cancel', array( 'type'=>'button','id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','class'=>'btn btn-warning','onclick'=>"queryPopupClose();")); ?>  
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php
    } else if($QueryDetails['StatusID']==3) {
?>
    <div id="popup1" class="overlay">
        <div class="popup" style="width:50%;">
            <div id='successMessage' align='center' style='display:none;color:green'><b>Query Successfully Posted!</b></div>
            <h2>TL Comments</h2>
            <a class="close" href="#">&times;</a>
            <div class="content">
                <table style="width:100%">
                    <tr>
                        <td >User Query</td>
                        <td>TL Comments</td></tr>
                    <tr>
                    <tr>
                        <td><textarea name="query" id="query" rows="5" cols="35"><?php echo $QueryDetails['Query']; ?></textarea></td>
                        <td><textarea name="query" id="query" rows="5" cols="35"><?php echo $QueryDetails['TLComments']; ?></textarea></td></tr>
                </table>
            </div>
        </div>
    </div>
<?php
    } else {
?>
    <div id="popup1" class="overlay">
        <div class="popup">
            <div id='successMessage' align='center' style='display:none;color:green'><b>Query Successfully Posted!</b></div>
            <h2>Query</h2>
            <a class="close" href="#">&times;</a>
            <div class="content">
                <table style="width:100%">
                    <tr><td style="width:50%">Query</td><td><textarea name="query" id="query" rows="5" cols="35"></textarea></td></tr>
                    <tr>
                        <td></td>
                        <td> <input type="hidden" name="ProductionEntity" id="ProductionEntity" value="<?php echo $productionjob['InputEntityId'];?>"> 
                                <?php echo $this->Form->button('Submit', array( 'id' => 'Query', 'name' => 'Query', 'value' => 'Query','class'=>'btn btn-primary btn-sm','onclick'=>"return valicateQuery();",'type'=>'button')).' '; 
                           //echo $this->Form->button('Cancel', array( 'type'=>'button','id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','class'=>'btn btn-warning','onclick'=>"queryPopupClose();")); ?>  
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php
    }
?>



    <!--    <div id="popup1" class="overlay" >
            <div class="popup">
                <div id='successMessage' align='center' style='display:none;color:green'><b>Query Successfully Posted!</b></div>
                <h2>Query</h2>
                <a class="close" href="#">&times;</a>
                <div class="content">
                    <table style="width:100%">
                        <tr><td style="width:50%">Query</td><td><textarea name="query" id="query" rows="5" cols="35"></textarea></td></tr>
                        <tr>
                            <td></td>
                            <td> <input type="hidden" name="ProductionEntity" id="ProductionEntity" value="<?php echo $productionjob['ProductionEntityID'];?>"> 
                                <?php //echo $this->Form->button('Submit', array( 'id' => 'Query', 'name' => 'Query', 'value' => 'Query','class'=>'btn btn-warning','onclick'=>"return valicateQuery();",'type'=>'button')).' '; 
                           //echo $this->Form->button('Cancel', array( 'type'=>'button','id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','class'=>'btn btn-warning','onclick'=>"queryPopupClose();")); ?>  
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>-->
    <div id="fade" class="black_overlay"></div>
<?php

 echo $this->Form->end();   
}
?>
    <script>
        var myWindow = null;
        function onMyFrameLoad() {
            $('#loaded').val('loaded');
        }
        $(document).ready(function () {
            $("#vertical").kendoSplitter({
                orientation: "vertical",
                panes: [
                    {collapsible: false},
                    {collapsible: false, size: "100px"},
                    {collapsible: false, resizable: false, size: "100px"}
                ]
            });

            $("#horizontal").kendoSplitter({
                orientation: "horizontal",
                panes: [
                    {collapsible: true},
                    {collapsible: true},
                    {collapsible: true}
                ],
                expand: onExpandSplitter,
                resize: onResizeSplitter
            });

            function onResizeSplitter(e) {

                var leftpaneSize = $('#left-pane').data('pane').size;
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('controller' => 'ProductionView', 'action' => 'upddateLeftPaneSizeSession')); ?>",
                    data: ({leftpaneSize: leftpaneSize}),
                    dataType: 'text',
                    async: true,
                    success: function (result) {

                    }
                });
            }

            function onExpandSplitter() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('controller' => 'ProductionView', 'action' => 'upddateUndockSession')); ?>",
                    data: ({undocked: 'no'}),
                    dataType: 'text',
                    async: true,
                    success: function (result) {

                    }
                });
                if (myWindow)
                    myWindow.close();
            }

            //setTimeout(window.stop,8000);
            function displayTimeout() {

                //alert('test');
                iframe1 = document.getElementById('frame');
                //alert(frames["frame"].document.body.innerHTML)
                //iframeDoc= document.getElementById("frame").contentWindow;
                //alert($( "#frame" ).contents());
                //    if (iframe1.contentDocument){ // FF Chrome
                //        alert('dsa');
                //   iframeDoc = iframe1.contentDocument;
                //}
                // Check if loading is complete
                // alert(iframeDoc.readyState);

                if ($('#loaded').val() === 'loaded') {

                } else {

                    var p = iframe1.parentNode;
                    p.removeChild(iframe1);

                    var div = document.createElement("iframe");

                    div.setAttribute("id", "frame");
                    div.setAttribute("style", 'width:100%; height:800px; overflow:hidden !important;');
                    // var html = '<body>Foo</body>';
                    // iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(html);
                    // document.body.appendChild(div);
                    //div.setAttribute("",'width:100%; height:800px; overflow:hidden !important;');
                    p.appendChild(div);
                    //var div = document.createElement("iframe");
                    // var text = document.createTextNode('Loading Issue');
                    // div.appendChild(text);

                    var html = '<body>Loading takes longer than usual.<br> Please use Go button!</body>';
                    div.src = 'data:text/html;charset=utf-8,' + encodeURI(html);
                    p.appendChild(div);
                    console.log('div.contentWindow =', div.contentWindow);
                }



            }
            setTimeout(displayTimeout, 8000);

        });

    </script>
    <style>
        #vertical {
            height: 750px;
            margin: 0 auto;
        }
        #left-pane,#right-pane  { background-color: rgba(60, 70, 80, 0.05); }
        .pane-content {
            padding: 0 10px;
        }
    </style>
</div>


<script>
    var hms = '<?php echo $hrms[0];?>';   // your input string
    if (hms != '') {
        var a = hms.split(':'); // split it at the colons
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    } else
    {
        var seconds = 0;
    }
    function LoadPDF(file)
    {
        document.getElementById('frame').src = file;
        $("body", myWindow.document).find('#pdfframe').attr('src', file);
    }
//    function secondPassed() {
//        var hour = Math.round((Math.round((seconds - 30) / 60) - 30) / 60);
//        var temp = hour * 60 * 60;
//        var minutes = Math.round(((seconds - temp) - 30) / 60);
//        var remainingSeconds = seconds % 60;
//        if (remainingSeconds < 10) {
//            remainingSeconds = "0" + remainingSeconds;
//        }
//        if (minutes < 10) {
//            minutes = "0" + minutes;
//        }
//
//        if (hour < 10) {
//            hour = "0" + hour;
//        }
//        document.getElementById('countdown').innerHTML = hour + ":" + minutes + ":" + remainingSeconds;
//        document.getElementById('TimeTaken').value = hour + ":" + minutes + ":" + remainingSeconds;
//        seconds++;
//    }
//    var countdownTimer = setInterval('secondPassed()', 1000);

    function formSubmit() {
<?php
    if(isset($Mandatory)) {
$js_array = json_encode($Mandatory);
echo "var mandatoryArr = ". $js_array . ";\n";
    }
?>
        var mandatary = 0;
        if (typeof mandatoryArr != 'undefined') {
            $.each(mandatoryArr, function (key, elementArr) {
                element = elementArr['AttributeMasterId']
                if ($('#' + element).val() == '') {
                    alert('Enter Value in ' + elementArr['DisplayAttributeName']);
                    $('#' + element).focus();
                    mandatary = '1';
                    return false;
                }
            });
        }
        if (mandatary == 0) {
            AjaxSave('');
            return true;

        } else
            return false;
    }
    function getJob()
    {
        window.location.href = "ProductionView?job=newjob";
    }
    var windowObjectReference;
    var strWindowFeatures = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
    function OpenPdf() {
        str = $("#status option:selected").text();
        if (str.search("http://") > -1)
            file = $("#status option:selected").text();
        else if (str.search("https://") > -1)
            file = $("#status option:selected").text();
        else
            file = 'http://' + $("#status option:selected").text() + '/';
        windowObjectReference = window.open(file, "CNN_WindowName", strWindowFeatures);
    }

    function PdfPopup()
    {
//        var splitterElement = $("#horizontal").kendoSplitter({
//            panes: [
//                {collapsible: true},
//                {collapsible: false},
//                {collapsible: true}
//            ]
//        });
//
//        var splitter = splitterElement.data("kendoSplitter");
//        var leftPane = $("#left-pane");
//        splitter["collapse"](leftPane);
//        var file = $("#status option:selected").text();
//
//        myWindow = window.open("", "myWindow", "width=500, height=500");
//        myWindow.document.write('<iframe id="pdfframe"  src="' + file + '" style="width:100%; height:100%; overflow:hidden !important;"></iframe>');

        var splitterElement = $("#horizontal"), getPane = function (index) {
            index = Number(index);
            var panes = splitterElement.children(".k-pane");
            if (!isNaN(index) && index < panes.length) {
                return panes[index];
            }
        };

        var splitter = splitterElement.data("kendoSplitter");
        var pane = getPane('0');
        splitter.toggle(pane, $(pane).width() <= 0);


        var file = $("#status option:selected").text();
        myWindow = window.open("", "myWindow", "width=500, height=500");
        myWindow.document.write('<iframe id="pdfframe"  src="' + file + '" style="width:100%; height:100%; overflow:hidden !important;"></iframe>');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionView', 'action' => 'upddateUndockSession')); ?>",
            data: ({undocked: 'yes'}),
            dataType: 'text',
            async: true,
            success: function (result) {

            }
        });

    }
    function valicateQuery()
    {
        if ($("#query").val() == '')
        {
            alert('Enter Query');
            $("#query").focus();
            return false;
        }
        query = $("#query").val();
        InputEntyId = $("#ProductionEntity").val();

        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxqueryposing'));?>",
            data: ({query: query, InputEntyId: InputEntyId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('successMessage').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('successMessage').style.display = 'none';
                    $("#query").val(result);
                }, 2000);
            }
        });
    }
    function LoadValue(id, value, toid) {

        var Region = $('#RegionId').val();
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxloadresult'));?>",
            data: ({id: id, value: value, toid: toid, Region: Region}),
            dataType: 'text',
            async: false,
            success: function (result) {
                var obj = JSON.parse(result);
                // alert(JSON.stringify(obj));
                var k = 1;
                //toid = 225;
                var x = document.getElementById(toid);
                document.getElementById(toid).options.length = 0;
                var option = document.createElement("option");
                option.text = '--Select--';
                option.value = 0;
                x.add(option, x[0]);
                $.each(obj, function (key, element) {
                    //   obj.forEach(function (element) {
                    //  alert(element['Value'])
                    var option = document.createElement("option");
                    option.text = element['Value'];
                    option.value = element['id'];
                    x.add(option, x[k]);
                    k++;
                });


            }
        });
    }
    $(function () {

<?php
if(isset($AutoSuggesstion)){
$AutoSuggesstion_json = json_encode($AutoSuggesstion);
echo "var autoArr = ". $AutoSuggesstion_json . ";\n";
}
?>
        if (typeof mandatoryArr != 'undefined') {
            $.each(autoArr, function (key, element) {
                //autoArr.forEach(function (element) {
                var result = new Array();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxautofill'));?>",
                    data: ({element: element}),
                    dataType: 'text',
                    async: false,
                    success: function (result) {
                        availableTags = JSON.parse(result);
                    }
                });


                $("#" + element).autocomplete({
                    source: availableTags
                });
            });
        }
    });
    function AjaxSave(addnewpagesave) {

        //alert($('#235').val());
        document.getElementById('fade').style.display = 'block';
        <?php
        if(isset($Mandatory)) {
    $js_array = json_encode($Mandatory);
    echo "var mandatoryArr = ". $js_array . ";\n";
        }
    ?>
        var mandatary = 0;
        if (typeof mandatoryArr != 'undefined') {
            $.each(mandatoryArr, function (key, elementArr) {
                element = elementArr['AttributeMasterId']
                if ($('#' + element).val() == '') {
                    alert('Enter Value in ' + elementArr['DisplayAttributeName']);
                    $('#' + element).focus();
                    mandatary = '1';
                    return false;
                }
            });
        }
        if (mandatary == 0) {
            // AjaxSave('');
            // return true;

        } else
            return false;
        //alert('test');

        var addnew = $('#ADDNEW').val();

        var productionData = new Array();
        var productionData_ely = new Array();
        var productionData_projatt = new Array();
        var dynamicData = new Array();
        var dynamicData_ely = new Array();
        var staticDatavar = new Array();
        var staticData_elyvar = new Array();

        var prodArr =<?php echo json_encode($ProductionFields );?>;
        var dynamicArr =<?php if(isset($DynamicFields)) { echo json_encode($DynamicFields );} else echo "''";?>;
        var staticArr =<?php if(isset($StaticFields)) { echo json_encode($StaticFields );} else echo "''";?>;
        var i = 0;
        var j = 0;
        var k = 0;

        //alert($('#6233').val());

        $.each(prodArr, function (key, element) {
            if (element['AttributeMasterId'] != '') {
                var elt = element['AttributeMasterId'];
                var elts = element['ProjectAttributeMasterId'];
                // alert(elt);
                //alert($('#'+elt).val());
                productionData[i] = $('#' + elt).val();
                productionData_ely[j] = elt;
                productionData_projatt[k] = elts;
            }
            i++;
            j++;
            k++;
        });

        var i = 0;
        var j = 0;
        //alert(productionData); 
        if (typeof dynamicArr != 'undefined') {
            $.each(dynamicArr, function (key, element) {
                //dynamicArr.forEach(function (element) {
                //alert(JSON.stringify(element));
                //alert(element['AttributeMasterId']);
                if (element['AttributeMasterId'] != '') {
                    var elt = element['AttributeMasterId'];
                    //alert(element.length);
                    dynamicData[i] = $('#' + elt).val();
                    dynamicData_ely[j] = elt;
                    //  alert(productionData[elt]);
                }
                i++;
                j++;
            });
        }
        s = 0;
        v = 0;


        if (typeof staticArr != 'undefined') {
            $.each(staticArr, function (key, element) {
                //dynamicArr.forEach(function (element) {
                //alert(JSON.stringify(element));
                //alert(element['AttributeMasterId']);
                if (element['AttributeMasterId'] != '') {
                    //alert('coming');
                    var elt = element['AttributeMasterId'];
                    //alert($('#' + elt).val());
                    staticDatavar[s] = $('#' + elt).val();
                    staticData_elyvar[v] = elt;
                    //  alert(productionData[elt]);
                }
                s++;
                v++;
            });
        }
//alert(staticDatavar);
        var ProductionEntity = $('#ProductionEntity').val();
        var SequenceNumber = $('#page').val();
        var TimeTaken = $('#TimeTaken').val();
        var RegionId = $('#RegionId').val();
        $('#ADDNEW').val('');
        if (addnew == '') {
            var result = new Array();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxsave'));?>",
                data: ({productionData_ely: productionData_ely, productionData_projatt: productionData_projatt, productionData: productionData, dynamicData: dynamicData, dynamicData_ely: dynamicData_ely, ProductionEntity: ProductionEntity, SequenceNumber: SequenceNumber, TimeTaken: TimeTaken, RegionId: RegionId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    //alert(result);
                    if (result === 'saved') {
                        // alert(addnewpagesave);
                        if (addnewpagesave == '') {
                            alert('Entered Data Successfully saved!');
                            document.getElementById('fade').style.display = '';
                        }
                    } else {
                        window.location = "users";
                    }
                }

            });
        }
        if (addnew == 'ADDNEW') {
            var result = new Array();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxnewsave'));?>",
                data: ({staticDatavar: staticDatavar, staticData_elyvar: staticData_elyvar, productionData_ely: productionData_ely, productionData_projatt: productionData_projatt, productionData: productionData, dynamicData: dynamicData, dynamicData_ely: dynamicData_ely, ProductionEntity: ProductionEntity, SequenceNumber: SequenceNumber, TimeTaken: TimeTaken, RegionId: RegionId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    if (result === 'saved') {
                        alert('Additional Page Added Successfully');
                        document.getElementById('SequenceNumber').innerHTML = $('#seq').val();
                        loadNextAddnew('next');

                    } else {
                        window.location = "users";
                    }
                }
            });
        }

    }

    function loadNextAddnew(id) {
        document.getElementById('fade').style.display = 'block';

        var page = $('#page').val();
        var seq = $('#seq').val();
        if (id === 'next') {
            // page = parseInt(page) + 1;
            if (page == seq) {
                $("#clicktoviewNxt").prop("disabled", "disabled");
            }
            if (page == 1) {
                $("#clicktoviewPre").prop("disabled", "disabled");
            }
            if (page > 1) {
                $("#clicktoviewPre").prop("disabled", "");
            }


        }
        // $('#page').val(page);
        var next_status_id = '<?php echo $next_status_id;?>';
        var ProductionEntity = $('#ProductionEntity').val();
        var productionData = new Array();
        var productionData_ely = new Array();
        var dynamicData = new Array();
        var dynamicData_ely = new Array();
        var prodArr =<?php echo json_encode($ProductionFields );?>;
        var dynamicArr =<?php if(isset($DynamicFields)) { echo json_encode($DynamicFields );} else echo "''";?>;
        var i = 0;
        j = 0;

        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxgetnextpagedata'));?>",
            data: ({page: page, next_status_id: next_status_id, ProductionEntity: ProductionEntity}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result == 'expired') {
                    window.location = "users";
                }
                var resultData = JSON.parse(result);
                $.each(prodArr, function (key, element) {
                    // prodArr.forEach(function (element) {
                    var elt = element['AttributeMasterId'];
                    $('#' + elt).val(resultData[elt]);

                    i++;
                    j++;
                });
                document.getElementById('fade').style.display = '';
            }
        });

    }

    function loadNext(id) {
        $('#ADDNEW').val('');
        document.getElementById('fade').style.display = 'block';
        var page = $('#page').val();
        var seq = $('#seq').val();
        if (id === 'next') {
            page = parseInt(page) + 1;
            if (page == seq) {
                $("#clicktoviewNxt").prop("disabled", "disabled");
            }
            if (page == 1) {
                $("#clicktoviewPre").prop("disabled", "disabled");
            }
            if (page > 1) {
                $("#clicktoviewPre").prop("disabled", "");
            }


        }
        if (id === 'previous') {

            page = parseInt(page) - 1;
//alert(page);
//alert(seq);
            if (page == seq) {
                // alert('enter');
                $("#clicktoviewNxt").prop("disabled", "disabled");
            }
            if (page == 1) {
                $("#clicktoviewPre").prop("disabled", "disabled");
            }
            if (page > 1) {
                $("#clicktoviewPre").prop("disabled", "");

            }
            if (page != seq && seq > 1) {
                $("#clicktoviewNxt").prop("disabled", "");

            }
        }
        $('#page').val(page);
        var next_status_id = '<?php echo $next_status_id;?>';
        var ProductionEntity = $('#ProductionEntity').val();



        var productionData = new Array();
        var productionData_ely = new Array();
        var dynamicData = new Array();
        var dynamicData_ely = new Array();
        var prodArr =<?php echo json_encode($ProductionFields );?>;
        var dynamicArr =<?php if(isset($DynamicFields)) { echo json_encode($DynamicFields );} else echo "''";?>;
        var readonlyArr =<?php echo json_encode($ReadOnlyFields );?>;
        var i = 0;
        j = 0;



        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxgetnextpagedata'));?>",
            data: ({page: page, next_status_id: next_status_id, ProductionEntity: ProductionEntity}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result === 'expired') {
                    window.location = "users";
                }
                var resultData = JSON.parse(result);
                $.each(prodArr, function (key, element) {
                    //prodArr.forEach(function (element) {
                    var elt = element['AttributeMasterId'];
                    $('#' + elt).val(resultData[elt]);

                    i++;
                    j++;
                });
                document.getElementById('fade').style.display = '';
            }
        });

    }
    function addnewpage() {

    <?php
    if(isset($Mandatory)) {
    $js_array = json_encode($Mandatory);
    echo "var mandatoryArr = ". $js_array . ";\n";
    }
    ?>
        var mandatary = 0;
        if (typeof mandatoryArr != 'undefined') {
            $.each(mandatoryArr, function (key, elementArr) {
                element = elementArr['AttributeMasterId']
                if ($('#' + element).val() == '') {
                    alert('Enter Value in ' + elementArr['DisplayAttributeName']);
                    $('#' + element).focus();
                    mandatary = '1';
                    return false;
                }
            });
        }
        if (mandatary == 0) {
            // AjaxSave('');
            // return true;

        } else
            return false;

        AjaxSave('addnew');
        document.getElementById('fade').style.display = 'block';
        var page = $('#seq').val();
        var newseq = parseInt(page) + 1;
        $('#page').val(newseq);
        $('#seq').val(newseq);

        var productionData = new Array();
        var productionData_ely = new Array();
        var dynamicData = new Array();
        var dynamicData_ely = new Array();
        var prodArr =<?php echo json_encode($ProductionFields );?>;
        var dynamicArr =<?php if(isset($DynamicFields)) { echo json_encode($DynamicFields );} else echo "''";?>;
        var i = 0;
        j = 0;
        $.each(prodArr, function (key, element) {
            // prodArr.forEach(function (element) {
            if (element['AttributeMasterId'] != '') {
                var elt = element['AttributeMasterId'];
                $('#' + elt).val('');

            }
            i++;
            j++;
        });
        $('#ADDNEW').val('ADDNEW');
        document.getElementById('fade').style.display = '';
    }


    function AjaxNewSave() {
        document.getElementById('fade').style.display = 'block';
        var productionData = new Array();
        var productionData_ely = new Array();
        var dynamicData = new Array();
        var dynamicData_ely = new Array();
        var prodArr =<?php echo json_encode($ProductionFields );?>;
        var dynamicArr =<?php if(isset($DynamicFields)) { echo json_encode($DynamicFields );} else echo "''";?>;
        var i = 0;
        j = 0;
        $.each(prodArr, function (key, element) {
            // prodArr.forEach(function (element) {
            if (element['AttributeMasterId'] != '') {
                var elt = element['AttributeMasterId'];
                productionData[i] = $('#' + elt).val();
                productionData_ely[j] = elt;
            }
            i++;
            j++;
        });



        var i = 0;
        j = 0;
        //alert(productionData); 
        if (typeof dynamicArr != 'undefined') {
            $.each(dynamicArr, function (key, element) {
                //  dynamicArr.forEach(function (element) {
                //alert(JSON.stringify(element));
                //alert(element['AttributeMasterId']);
                if (element['AttributeMasterId'] != '') {
                    var elt = element['AttributeMasterId'];
                    //alert(element.length);
                    dynamicData[i] = $('#' + elt).val();
                    dynamicData_ely[j] = elt;
                    //  alert(productionData[elt]);
                }
                i++;
                j++;
            });
        }

        var ProductionEntity = $('#ProductionEntity').val();
        var SequenceNumber = $('#page').val();
        var TimeTaken = $('#TimeTaken').val();

        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxnewsave'));?>",
            data: ({productionData_ely: productionData_ely, productionData: productionData, dynamicData: dynamicData, dynamicData_ely: dynamicData_ely, ProductionEntity: ProductionEntity, SequenceNumber: SequenceNumber, TimeTaken: TimeTaken}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result === 'expired') {
                    window.location = "users";
                }
                // availableTags = JSON.parse(result);
                document.getElementById('fade').style.display = '';
            }
        });


    }
    function DeletePage() {
        document.getElementById('fade').style.display = 'block';
        var r = confirm("Are you sure want to delete this page?");
        if (r == true) {

        } else {

            return false;
        }


        var page = $('#page').val();
        var ProductionEntity = $('#ProductionEntity').val();
        var ProductionId = $('#ProductionId').val();
        //alert(ProductionId);
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxdelete'));?>",
            data: ({page: page, ProductionId: ProductionId, ProductionEntity: ProductionEntity}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result === 'expired') {
                    window.location = "users";
                }
                if (result === 'one') {
                    alert('Minimum one Row Required');
                    return false;
                }
                if (result === 'deleted') {
                    var newseq = parseInt($('#seq').val()) - 1;
                    alert('Deleted Successfully');
                    $('#seq').val(newseq);
                    document.getElementById('SequenceNumber').innerHTML = newseq;
                    if (page == 1) {
                        loadNext('next');
                    } else {
                        loadNext('previous');
                    }


                    return false
                }
                document.getElementById('fade').style.display = '';
            }
        });
    }
    function datacheck(id, value) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ProductionView','action'=>'ajaxdatacheck'));?>",
            data: ({id: id, value: value}),
            dataType: 'text',
            async: false,
            success: function (result) {

            }
        });
    }

</script>
<style>
    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 500ms;
        visibility: hidden;
        opacity: 0;
    }
    .overlay:target {
        visibility: visible;
        opacity: 1;
    }


    .popup {
        margin: 150px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        width: 40%;
        position: relative;
        transition: all 5s ease-in-out;

    }

    .popup h2 {
        margin-top: 0;
        color: #333;
        font-family: Tahoma, Arial, sans-serif;
    }
    .popup .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }
    .popup .close:hover {
        color: #fdc382;
    }
    .popup .content {
        max-height: 30%;
        overflow: auto;
    }

    #vertical {
        height: 750px;
        margin: 0 auto;
    }
    #left-pane,#right-pane  { background-color: rgba(60, 70, 80, 0.05); }
    .pane-content {
        padding: 0 10px;
    }
</style>
 <?php
        if($session->read("undocked") == 'yes') {
    ?>
<script>
    $(window).bind("load", function () {
        //alert('sds');
        PdfPopup();
    });
</script>
    <?php
        }
        else if($session->read("leftpaneSize") > 0) {
    ?>
<script>
    $(window).bind("load", function () {
        var leftpaneSize = '<?php echo $session->read("leftpaneSize"); ?>';
        var splitter = $("#horizontal").data("kendoSplitter");
        splitter.size(".k-pane:first", leftpaneSize);
    });
</script>
    <?php
        }
    ?>

<script>
    $(window).unload(function () {
        myWindow.close();
    });
</script>