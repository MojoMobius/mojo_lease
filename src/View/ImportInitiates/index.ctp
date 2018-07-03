<?php
echo $this->Form->create(array('name' => 'projectEdit', 'id' => 'projectEdit', 'style'=>'width:100%','class' => 'form-group','action'=>'/' ,'inputDefaults' => array( 'div' => false),'type'=> 'post')); ?>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
    <div class="form-group form-group-sm form-inline">
    <?php echo $this->Form->create(array('name' => 'inputSearch', 'id' => 'inputSearch', 'class' => 'form-group', 'inputDefaults' => array( 'div' => false))); 
    echo $this->Form->input('<b> Project Name&nbsp;: </b> &nbsp;', array('options' => $Projects,'id' => 'ProjectId','selected'=>$ProjectId, 'name' => 'ProjectId', 'class'=>'form-control', 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); 
    echo '<br>';
    $Region=array(0=>'--Select--');
    echo '<div id="LoadRegion">';
    echo $this->Form->input('<b> Region Name&nbsp;: </b> &nbsp;', array('options' => $Region,'id' => 'RegionId', 'name' => 'RegionId', 'class'=>'form-control')); 
    echo '</div>';
    $InputTypeArr=array('0'=>'--Select--',1=>'Client Input',2=>'Automation Output');
    echo $this->Form->input('<b> Input Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </b> &nbsp;', array('options' => $InputTypeArr,'id' => 'InputType','selected'=>$InputType, 'name' => 'InputType', 'class'=>'form-control','onchange'=>'return StatusDisp(this.value)' )); 
    echo '<div id="LoadFiles">';
    echo $this->Form->input('<b> Files Name&nbsp;&nbsp;&nbsp;&nbsp;: </b> &nbsp;', array('options' => $Region,'id' => 'FileName', 'name' => 'FileName', 'class'=>'form-control')); 
    echo '</div>';
    echo '<div id="StatusId">';
    echo $this->Form->input('<b> Status&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </b> &nbsp;', array('options' => $status,'id' => 'Status', 'name' => 'Status', 'class'=>'form-control')); 
    echo '</div>';
    echo $this->Form->submit('Submit', array( 'id' => 'submit','style'=>'margin-left:200px;float:left;padding-bottom:5 px;', 'name' => 'submit', 'value' => 'Submit','class'=>'btn btn-warning','onclick'=>'return ValidateForm()')); 
    echo $this->Form->button('Cancel', array( 'id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:2px;','class'=>'btn btn-warning','onclick'=>'return CancelForm()','type'=>'button'));   
    echo $this->Form->end();
    ?>
    </div>
</div>
<!-- Index -->
<div id='detail'>
    <table style='width:98%;' class='table-responsive'>
        <?php echo $this->Html->tableHeaders(array('Project Name','Region Name','Input Type','Input File','Input to Status','Import Status'),array('class' => 'Heading'),array('class' => 'Cell'));
        $i = 0;
        foreach ($importList as $inputVal => $input):
            
           // $test='<a href="'.$this->webroot.'OptionMasters/index/'.$input['ProjectAttributeMasterId'].'">Edit</a>';
            echo $this->Html->tableCells(array(
               array(
                  array($input['ProjectName'],array('class' => 'Cell')),
                  array($input['Region'],array('class' => 'Cell')),
                  array($input['InputType'],array('class' => 'Cell')),
                  array($input['FileName'],array('class' => 'Cell')),
                  array($input['InputToStatus'],array('class' => 'Cell')),
                  array($input['RecordStatus'],array('class' => 'Cell')),
                  )
                ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
            $i++;
           endforeach;
        ?>
    </table>
</div>
<!-- Index end -->
<script>
    function getRegion(projectId) {
        $.post('<?php echo $this->Html->url('/ImportInitiates/ajax_region'); ?>', {projectId: projectId}, function (result) {
            document.getElementById('LoadRegion').innerHTML = result;
        });
    }
    function getFiles() {
        $.post('<?php echo $this->Html->url('/ImportInitiates/ajax_FileList'); ?>', function (result) {
            document.getElementById('LoadFiles').innerHTML = result;
        });
    }
    function ValidateForm(){
        if($('#ProjectId').val()==0){
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if($('#RegionId').val()==0){
            alert('Select Region');
            $('#RegionId').focus();
            return false;
        }
        if($('#FileName').val()==0){
            alert('Select File Name');
            $('#FileName').focus();
            return false;
        }
        if($('#Status').val()==0){
            alert('Select Status');
            $('#Status').focus();
            return false;
        }
       
        return false;
    }
    
    function StatusDisp(value){
        
        if(value==1){
            $('#Status').val('0');
            $('#Status').attr("disabled","disabled");
        }
        else{
            $('#Status').val('0');
            $('#Status').removeAttr("disabled","disabled");
        }
    }
</script>