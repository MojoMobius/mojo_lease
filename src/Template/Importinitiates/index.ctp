<?php

use Cake\Routing\Router
?>
<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Input Import</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6">
                     <?php 
                     echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId', 'value' => $sessionProjects ,'name' => 'ProjectId', 'class'=>'form-control prodash-txt', 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); 
                        ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Region</label>
                <div class="col-sm-6">
                  <?php 
                  //$Region=array(0=>'--Select--',1=>'US',2=>'UK');
                  $Region=array(0=>'--Select--');
                    echo '<div id="LoadRegion">';
                    echo $this->Form->input('', array('options' => $Region,'id' => 'Region', 'name' => 'Region', 'class'=>'form-control prodash-txt')); 
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Input Type</label>
                <div class="col-sm-6">
                     <?php 
                        $InputTypeArr=array('0'=>'--Select--',1=>'Client Input',2=>'Automation Output');
                        echo $this->Form->input(' ', array('options' => $InputTypeArr,'id' => 'InputType', 'name' => 'InputType', 'class'=>'form-control prodash-txt','onchange'=>'return getStatus(this.value)' )); 
                      ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">File Name</label>
                <div class="col-sm-6">
                     <?php
                        echo '<div id="LoadFiles">';
                        echo $this->Form->input(' ', array('options' => $Region,'id' => 'FileName', 'name' => 'FileName', 'class'=>'form-control prodash-txt')); 
                        echo '</div>';
                     ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                   <?php echo '<div id="LoadStatus">'; ?>
                <label for="inputPassword3" class="col-sm-6 control-label" >Status</label>
                <div class="col-sm-6">
                     <?php 
                     
                     $status=array(0=>'--Select--');
                     echo $this->Form->input('', array('options' => $status,'id' => 'InputToStatus', 'name' => 'InputToStatus', 'class'=>'form-control prodash-txt')); 
                    
                     ?>
                </div>
                  <?php  echo '</div>'; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"></label>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" id= "check_submit" name="check_submit" class="btn btn-primary btn-sm" onclick="return ValidateForm();">Submit</button>
                <span id='xlscnt'>
                    <button type='submit' name='downloadFile' id='downloadFile' class="btn btn-primary btn-sm" value='downloadFile' onclick="return Mandatory();">Sample Download</button>
                </span>
            </div>
        </div>
        </form>
    </div>
</div>
<div  id='detail' class="container-fluid bs-example mt15" >
    <table style=';' class='table table-striped table-center'>
            <?php echo $this->Html->tableHeaders(array('S.No', 'Project Name','Region Name','FIle Name','Input TO Status','Input Type','Record Status','Error Message','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
            $i = 1;
            $recordStatus=array('0'=>'Inactive','1'=>'Active',2=>'Import Initiated',3=>'Import Completed', 4=>'Invalid Header');
              foreach ($query as $key => $data):
                $delete=$this->Html->link('Delete', ['action' => 'delete', $data->Id]);
       // pr($detailArr);
				$staus=($data->InputToStatus==0)?'':$detailArr[$data->ProjectId]['Status'][$data->InputToStatus];
                echo $this->Html->tableCells(array(
                    array(
                        array($i,array('class' => 'Cell')),
                        array($Projects[$data->ProjectId],array('class' => 'Cell')),
                        array($detailArr[$data->ProjectId]['Region'][$data->Region],array('class' => 'Cell')),
                        array($data->FileName,array('class' => 'Cell')),
                        array($staus,array('class' => 'Cell')),
                        array($InputTypeArr[$data->InputType],array('class' => 'Cell')),
                        array($recordStatus[$data->RecordStatus],array('class' => 'Cell')),
                        array($data->ResponseData,array('class' => 'Cell')),
                        array($delete,array('class' => 'Cell')),
                        )
                    ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                $i++;
            endforeach;
            ?>
    </table>
</div>
<script type="text/javascript">

    $( document ).ready(function() {
    var projectId = <?php echo $sessionProjects ?>;
    var projectName = $('#ProjectId option:selected').html();
    $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxregion'));?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadRegion').innerHTML = result;
            }
        });
          $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxfilelist'));?>",
            data: ({projectName: projectName}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadFiles').innerHTML = result;
            }
        });
    });
    function getRegion(projectId) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxregion'));?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadRegion').innerHTML = result;
            }
        });
    }
    function getFiles() {
        var result = new Array();
        var projectName = $('#ProjectId option:selected').html();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxfilelist'));?>",
            data: ({projectName: projectName}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadFiles').innerHTML = result;
            }
        });


    }
    function getStatus(importType) {
        if (importType == 1) {
            document.getElementById('LoadStatus').style.display = 'block';
            projectId = $('#ProjectId').val();
            var result = new Array();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxstatus'));?>",
                data: ({projectId: projectId, importType: importType}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadStatus').innerHTML = result;
                }
            });
        } else {
            document.getElementById('LoadStatus').style.display = 'none';
        }
    }
    function ValidateForm() {
        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#Region').val() == 0) {
            alert('Select Region Name');
            $('#Region').focus();
            return false;
        }
        if ($('#InputType').val() == 0) {
            alert('Select Input Type');
            $('#InputType').focus();
            return false;
        }
        if ($('#FileName').val() == 0) {
            alert('Select File Name');
            $('#FileName').focus();
            return false;
        }

        if ($('#InputType').val() == 1 && $('#InputToStatus').val() == 0) {
            alert('Select Status');
            $('#InputToStatus').focus();
            return false;
        }

        return true;
    }

    function Mandatory() {
        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#Region').val() == 0) {
            alert('Select Region Name');
            $('#RegionId').focus();
            return false;
        }
    }
</script>
<script>
    $(window).bind("load", function () {
        var optionValues = [];
        $('#ProjectId option').each(function () {
            optionValues.push($(this).val());
        });
        if (optionValues.length == 2) {
            //alert(optionValues[1]);
            $("#ProjectId").val(optionValues[1]).change();
        }
    });
</script>