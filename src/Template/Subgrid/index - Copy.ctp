<?php

use Cake\Routing\Router
//echo $this->Form->create(array('name' => 'projectEdit', 'id' => 'projectEdit', 'style'=>'width:100%','class' => 'form-group','action'=>'/' ,'inputDefaults' => array( 'div' => false),'type'=> 'post')); ?>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
    <div class="form-group form-group-sm form-inline">
    <?php echo $this->Form->create(); 
    echo $this->Form->input(' Project Name : ', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'class'=>'form-control', 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); 
    echo '<br>';
    $Region=array(0=>'--Select--',1=>'US',2=>'UK');
    
     echo '<div id="LoadRegion">';
    echo $this->Form->input(' Region Name : ', array('options' => $Region,'id' => 'Region', 'name' => 'Region', 'class'=>'form-control')); 
    echo '</div>';
    $InputTypeArr=array('0'=>'--Select--',1=>'Client Input',2=>'Automation Output');
    
    echo $this->Form->input(' Input Type : ', array('options' => $InputTypeArr,'id' => 'InputType', 'name' => 'InputType', 'class'=>'form-control','onchange'=>'return getStatus(this.value)' )); 
    echo '<div id="LoadFiles">';
    echo $this->Form->input(' Files Name : ', array('options' => $Region,'id' => 'FileName', 'name' => 'FileName', 'class'=>'form-control')); 
    echo '</div>';
    echo '<div id="LoadStatus">';
    $status=array(0=>'--Select--');
    echo $this->Form->input(' Status       :', array('options' => $status,'id' => 'InputToStatus', 'name' => 'InputToStatus', 'class'=>'form-control')); 
    echo '</div>';
    echo $this->Form->submit('Submit', array( 'id' => 'submit','style'=>'margin-left:200px;float:left;padding-bottom:5 px;', 'name' => 'submit', 'value' => 'Submit','class'=>'btn btn-warning','onclick'=>'return ValidateForm()')); 
    echo $this->Form->button('Cancel', array( 'id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:2px;','class'=>'btn btn-warning','onclick'=>'return CancelForm()','type'=>'button'));   
    echo $this->Form->end();
    ?>
    </div>
</div>

<div id='detail'>
    <table style='width:98%;' class='table-responsive'>
            <?php echo $this->Html->tableHeaders(array('S.No', 'Project Name','Region Name','FIle Name','Input TO Status','Input Type','Record Status','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
            $i = 1;
            $recordStatus=array('0'=>'Inactive','1'=>'Active',2=>'Import Initiated',3=>'Import Completed');
            foreach ($query as $data):
                  //$this->Html->link('edit', ['action' => 'edit', $query->id]) ;
                $delete=$this->Html->link('Delete', ['action' => 'delete', $data->Id]);
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
                        array($delete,array('class' => 'Cell')),
                        )
                    ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                $i++;
            endforeach;
            ?>
    </table>
</div>
<?php if($this->Paginator->numbers()!='') { ?>
<div class="paginator">
    <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
    </ul>
    
</div>
<?php } ?>
<script type="text/javascript">
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
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Importinitiates','action'=>'ajaxfilelist'));?>",
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
        }
        else {
            document.getElementById('LoadStatus').style.display = 'none';
        }
    }
     function ValidateForm(){
        if($('#ProjectId').val()==0){
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if($('#Region').val()==0){
            alert('Select Region Name');
            $('#RegionId').focus();
            return false;
        }
        if($('#InputType').val()==0){
            alert('Select Input Type');
            $('#FileName').focus();
            return false;
        }
        if($('#FileName').val()==0){
            alert('Select File Name');
            $('#FileName').focus();
            return false;
        }
        
        if($('#InputType').val()==1 && $('#InputToStatus').val()==0){
            alert('Select Status');
            $('#InputToStatus').focus();
            return false;
        }
       
        return true;
    }
</script>
