<h1>Add Project Type</h1>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
        <div class="form-group form-group-sm form-inline">
<?php 
    echo $this->Form->create($ProjectTypeMasters); 
     echo $this->Form->input('ProjectType', ['type' => 'text','class'=>'form-control','style'=>'margin-left:21px','id' => 'ProjectType','required' => true,'label' => ['text' => 'Project Type']]);
    echo $this->Form->button(__('Submit'),array('class'=>'form-control') );
    echo $this->Form->end();
 ?>
        </div>
    </div>

<div id='detail'>
        <table style='width:98%;' class='table-responsive'>
            <?php echo $this->Html->tableHeaders(array('S.No', 'Project Type','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
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

