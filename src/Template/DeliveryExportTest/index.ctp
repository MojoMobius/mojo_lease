<?php

use Cake\Routing\Router
?>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>Export Output</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6 prodash-txt">
                     <?php 
                     echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'class'=>'form-control','value'=>$ProjectId, 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); 
                        ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Region</label>
                <div class="col-sm-6">
                    <div id="LoadRegion">
                        <?php 
                            if ($RegionId == '') {
                                $Region=array(0=>'--Select--');
                                echo $this->Form->input('', array('options' => $Region,'id' => 'RegionId', 'name' => 'RegionId', 'class'=>'form-control','value'=>$RegionId,'onchange' => 'getusergroupdetails(this.value)')); 
                            } else {
                                echo $RegionId;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="UserGroupId" class="col-sm-6 control-label">User Group</label>
                <div class="col-sm-6 prodash-txt">
                    <div id="LoadUserGroup">
                        <?php
                        if ($UserGroupId == '') {
                            $UserGroup = array(0 => '--Select--');
                            echo $this->Form->input('', array('options' => $UserGroup, 'id' => 'UserGroupId', 'name' => 'UserGroupId', 'class' => 'form-control', 'value' => $UserGroupId, 'selected' => $UserGroupId, 'onchange' => 'getresourcedetails'));
                        } else {
                            echo $UserGroupId;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if($getProductionDate=='2'){
            $inputbatch_checked='checked="checked"';
        }else{
            $production_checked='checked="checked"';
        }
        ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
                <div class="col-sm-6 exp_radio">
                    <input <?php echo $production_checked; ?> type="radio" id="production_date" name="production_date" value="1" onclick="showNhide(this.value);">&nbsp;Production Date
                    <input <?php echo $inputbatch_checked; ?> type="radio" id="inputbatch_date" name="production_date" value="2" onclick="showNhide(this.value);">&nbsp;Input Batch
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">From</label>
                <div class="col-sm-6 prodash-txt">
                   <?php echo $this->Form->input('', array( 'id' => 'batch_from', 'name' => 'batch_from', 'class'=>'form-control','value'=>$postbatch_from)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">To</label>
                <div class="col-sm-6 prodash-txt" >
                   <?php  echo $this->Form->input('', array( 'id' => 'batch_to', 'name' => 'batch_to', 'class'=>'form-control','value'=>$postbatch_to)); ?>
                </div>
            </div>
        </div>

        <div class="form-group but_align" style="text-align:center;">
            <div class="col-sm-12">
                <span class="clea_buttun"> <?php
            echo $this->Form->Submit('Export', array( 'id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Search','style'=>'','class'=>'btn btn-primary btn-sm clea_buttun','onclick'=>'return Mandatory()'));
            echo $this->Form->button('Load', array( 'id' => 'load_data', 'name' => 'load_data', 'value' => 'Load','class'=>'btn btn-primary btn-sm','style'=>'margin-left: 230px;','type'=>'submit'));
            echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','class'=>'btn btn-primary btn-sm','style'=>'margin-left:5px;','onclick'=>'return ClearFields()','type'=>'button'));
            ?>  </span>    
            </div>    

        </div>



            <?php
                echo $this->Form->end();
            ?>
    </div>

    <?php if (count($ExportProductions[0]) > 0) { ?>

    <div id='detail' class="bs-example">
        <table style='width:100%;' class='table table-striped table-center'>
                <?php echo $this->Html->tableHeaders(array('Input Date','Batch Name','File Name','Total Jobs','Ready for Export','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
           
                    $i = 0;
                    $recordStatus=array('0'=>'Inactive','1'=>'Active',2=>'Import Initiated',3=>'Import Completed');
                    foreach ($ExportProductions as $data):
                        $BatchId=($data['Id']);
                        $ProjectId=($data['ProjectId']);
                        $RegionId=($data['RegionId']);
                        $CreatedDate=($data['CreatedDate']);
                        $Exports='';
                    if($data['CompletedJobs'] > 0)
                        $Exports=$this->Html->link('Export', ['action' => 'export', $BatchId, $ProjectId, $RegionId, $CreatedDate, $postbatch_UserGroupId]);
                        //$staus=($data->InputToStatus==0)?'':$detailArr[$data->ProjectId]['Status'][$data->InputToStatus];
                        echo $this->Html->tableCells(array(
                            array(
                                array(date('d-M-Y', strtotime($data['InputDate'])) ,array('class' => 'Cell')),
                                array($data['BatchName'],array('class' => 'Cell')),
                                array($data['FileName'],array('class' => 'Cell')),
                                array($data['TotalJobs'],array('class' => 'Cell')),
                                array($data['CompletedJobs'],array('class' => 'Cell')),
                                array($Exports,array('class' => 'Cell')),
                                )
                            ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                        $i++;
                    endforeach;
                ?>
        </table>
    </div>
    <?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function (projectId) {
        var id = $('#RegionId').val();
        value = '<?php echo $getProductionDate ?>';
        showNhide(value);
        if ($('#ProjectId').val() != '') {
            getRegion();
            var e = document.getElementById("RegionId");
            var strUser = e.options[e.selectedIndex].text;
        }
    });

    function getRegion(projectId) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'DeliveryExport','action'=>'ajaxregion'));?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadRegion').innerHTML = result;
                // document.getElementById('RegionId').value = result;
            }
        });
    }

    function getusergroupdetails(RegionId) {

        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'DeliveryExport', 'action' => 'getusergroupdetails')); ?>",
            data: ({projectId: ProjectId, regionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadUserGroup').innerHTML = result;
                var optionValues = [];
                $('#UserGroupId option').each(function () {
                    optionValues.push($(this).val());
                });
                optionValues.join(',')
                $('#UserGroupId').prepend('<option selected value="' + optionValues + '">All</option>');
                getresourcedetails();

            }
        });
    }

    function ClearFields()
    {
        $('#ProjectId').val(0);
        $('#RegionId').val(0);
        $('#batch_from').val('');
        $('#batch_to').val('');
        $('#user_id').val('0');
        $('#UserGroupId').val(0);
        $('#detail').hide();
    }

    function Mandatory()
    {
        //alert ($('input[type=radio]:checked').size());

        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#RegionId').val() == 0) {
            alert('Select Region Name');
            $('#RegionId').focus();
            return false;
        }

        if ($('input[type=radio]:checked').size() < 1) {
            alert('Please select any one check box!');
            return false;
        }

        if (($('#batch_from').val() == '') && ($('#batch_to').val() == '') && ($('#user').val() == null)) {
            alert('Select any one date!');
            return false;
        }
        if ((projectforms.check_submit[0].checked == false) && (projectforms.check_search[1].checked == false)) {
            alert("Please choose your Gender: Male or Female");
            return false;
        }
    }

    function showNhide(value) {
        if (value == 1) {
            $("#check_submit").removeAttr("name", "check_search");
            $("#check_submit").attr("name", "check_submit");
            $("#check_submit").attr("value", "Export");
            $("#production_date").attr("checked", "checked");
            $("#inputbatch_date").removeAttr("checked", "checked");
            $('#detail').hide();
        }
        if (value == 2) {
            $("#check_submit").removeAttr("name", "check_submit");
            $("#check_submit").attr("name", "check_search");
            $("#check_submit").attr("value", "Search");
            $("#inputbatch_date").attr("checked", "checked");
            $("#production_date").removeAttr("checked", "checked");
        }
    }
</script>

    <?php
if (isset($this->request->data['check_submit']) || isset($this->request->data['check_search'])) {
    ?>
<script>
    $(window).bind("load", function () {
        var optionValues = [];
        $('#UserGroupId option').each(function () {
            optionValues.push($(this).val());
        });
        optionValues.join(',')
        $('#UserGroupId').prepend('<option selected value="' + optionValues + '">All</option>');
        $("#UserGroupId option[value='<?php echo $postbatch_UserGroupId; ?>']").prop('selected', true);
        //getresourcedetails();
        //$("#UserGroupId option[value='<?php echo $this->request->data['user_id']; ?>']").prop('selected', true);
    });
</script>
    <?php
}
if ($CallUserGroupFunctions == 'yes') {
    ?>
    <script>
        $(window).bind("load", function () {
            var regId = $('#RegionId').val();
            getusergroupdetails(regId);
        });    
    </script>
    <?php
}
?>