<h1>Edit Project Type</h1>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
        <div class="form-group form-group-sm form-inline">
<?php 
    echo $this->Form->create($Projecttypemaster); 
     echo $this->Form->input('ProjectType', ['type' => 'text','class'=>'form-control','style'=>'margin-left:21px','id' => 'ProjectType','required' => true,'label' => ['text' => 'Project Type']]);
    echo $this->Form->button(__('Save'),array('class'=>'form-control') );
    echo $this->Html->link(__('Cancel'),['action' => 'index'],array('class'=>'form-control') );
    echo $this->Form->end();
 ?>
        </div>
    </div>
