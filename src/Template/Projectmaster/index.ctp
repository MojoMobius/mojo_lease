<?php 
//<div class="page-title">
//<p>Project Master</p>
//<img width="9" height="11" alt="" src="/mojo_entity/img/page_title.png">
//</div>
//echo $this->Form->create(array('name' => 'login', 'id' => 'login','style'=>'width:100%','class' => 'form-group', 'onSubmit' => 'return login_validation()', 'class' => 'input', 'inputDefaults' => array('label' => false, 'div' => false))); 
//echo $this->Form->create($ProjectMasters); 
?>

<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
  <div class="form-group form-group-sm form-inline">
    <?php echo $this->Form->create($ProjectMasters); 
    echo $this->Form->input('Project Id', array('id' => 'Project_Id', 'name' => 'ProjectId', 'class'=>'form-control', 'style'=>'margin-left:55px'));
    echo '<br><br>';
    
      echo $this->Form->end(); 
     ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<!-- add end-->







<h1>Add Project Type</h1>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
        <div class="form-group form-group-sm form-inline">
<?php 
    echo $this->Form->create($ProjectMasters); 
    echo $this->Form->input('ProjectId', ['type' => 'text','class'=>'form-control','style'=>'margin-left:21px','id' => 'ProjectId','required' => true,'label' => ['text' => 'Project Id']]);
    echo $this->Form->input('ProjectName', ['type' => 'text','class'=>'form-control','style'=>'margin-left:21px','id' => 'ProjectName','required' => true,'label' => ['text' => 'Project Name']]);
//    echo $this->Form->input('ProjectType', ['type' => 'select','id' => 'ProjectType','required' => true,'label' => ['text' => 'Project Type']]);
    //echo $this->Form->label('ProjectType');
    echo '<label for="ProjectType"><b>Project Type<b></label>';
    echo $this->Form->select('ProjectTypeId',['1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5],['empty' => '--select--'],['required' => true,'class'=>'form-control','style'=>'margin-left:21px',]);
    echo $this->Form->input('ProdDB_PageLimit', ['type' => 'text','class'=>'form-control','style'=>'margin-left:21px','id' => 'ProdDB_PageLimit','required' => false,'label' => ['text' => 'ProdDB_PageLimit']]);
    echo $this->Form->input('isBulk', ['type' => 'checkbox','id' => 'isBulk','required' => false,'class'=>'form-control','style'=>'margin-left:21px','label' => ['text' => 'isBulk']]);
    echo $this->Form->input('InputCheck', ['type' => 'checkbox','id' => 'InputCheck','class'=>'form-control','style'=>'margin-left:21px','required' => false,'label' => ['text' => 'InputCheck']]);
    echo $this->Form->button(__('Submit'),array('class'=>'form-control') );
    echo $this->Form->end();
 ?>
        </div>
    </div>

<div id='detail'>
        <table style='width:98%;' class='table-responsive'>
            <?php echo $this->Html->tableHeaders(array('S.No', 'Project Id','Project Name','Project Type','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
            $i = 1;
            foreach ($query as $query):
                  //$this->Html->link('edit', ['action' => 'edit', $query->id]) ;
                $EdiT=$this->Html->link('edit', ['action' => 'edit', $query->Id]);
                echo $this->Html->tableCells(array(
                    array(
                        array($i,array('class' => 'Cell')),
                        array($query->ProjectType,array('class' => 'Cell')),
                        array($EdiT,array('class' => 'Cell')),
                        )
                    ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                $i++;
            endforeach;
            ?>
        </table>
    </div>



