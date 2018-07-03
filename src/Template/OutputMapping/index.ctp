<!--Form : Output Template Mapping
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php use Cake\Routing\Router; ?>
<script type="text/javascript">
function validateForm()
{
    if($('#ProjectId').val()==0)
    {
    alert('Select Project Name');
    $('#ProjectId').focus();
    return false;
    }
    if($('#RegionId').val()==0)
    {
    alert('Select Region Name');
    $('#RegionId').focus();
    return false;
    }
    if($('#ModuleId').val()==0)
    {
    alert('Select Module Name');
    $('#ModuleId').focus();
    return false;
    }
   $('#OutputAttribute option').prop('selected', true);

    var exists = false;
$('#OutputAttribute option').each(function(){
        exists = true;
        return false;
    });

    if ( exists === false )
    {
        alert("Select atleast one item for Mapping");
        return false;
    }
    
}
function SelectMoveRows(SS1,SS2)
{
var SelID='';
    var SelText='';
    // Move rows from SS1 to SS2 from bottom to top
    for (i=SS1.options.length - 1; i>=0; i--)
    {
        if (SS1.options[i].selected == true)
        {
            SelID=SS1.options[i].value;
            SelText=SS1.options[i].text;
            var newRow = new Option(SelText,SelID);
            SS2.options[SS2.length]=newRow;
            SS1.options[i]=null;
        }
    }
//document.getElementById('LoadAttributeButton').style.display='inline-block';
    
}

function SelectMoveUp(move)
{
    //alert(move);
    var $op = $('#OutputAttribute option:selected');
        var upval = move;
        if($op.length){
            (upval == '1') ? 
                $op.first().prev().before($op) : 
                $op.last().next().after($op);
        }
}

function getRegion(ProjectId) {
    //alert(ProjectId);
    var result = new Array();
        $.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Outputmapping','action'=>'ajaxregion'));?>",
                data:({ProjectId:ProjectId}),
                dataType: 'text',
                async:false,
                success: function(result){
                    //alert(result);
                   document.getElementById('LoadRegion').innerHTML = result; 
                }
            });
    }
    function getModule()
    {
        var result = new Array();
        var ProjectId=$('#ProjectId').val();
        $.ajax({
        type:"POST",
        url:"<?php echo Router::url(array('controller'=>'Outputmapping','action'=>'ajaxmodule')); ?>",
        data:({ProjectId:ProjectId}),
        dataType: 'text',
                async:false,
                success: function(result){
                   document.getElementById('LoadModule').innerHTML = result; 
                }
        });
    }
    function getAttributes()
    {
        var result = new Array();
        var ProjectId=$('#ProjectId').val();
        var RegionId=$('#RegionId').val();
        var ModuleId=$('#ModuleId').val();
        $.ajax({
        type:"POST",
        url:"<?php echo Router::url(array('controller'=>'Outputmapping','action'=>'ajaxattribute')); ?>",
        data:({ProjectId:ProjectId,RegionId:RegionId,ModuleId:ModuleId}),
        //data:({ProjectId:ProjectId,RegionId:RegionId}),
        dataType: 'text',
                async:false,
                success: function(result){
                   document.getElementById('LoadAttribute').innerHTML = result; 
                }
        });
        //document.getElementById('LoadAttributeButton').style.display='inline-block';
    }
 

    </script>
<div class="container-fluid">
         <div class="jumbotron formcontent">
            <h4>Output Template</h4>
            <?php 
    echo $this->Form->create($OutputMapping,array('name'=>'inputSearch' , 'class' => 'form-horizontal', 'id' => 'projectforms')); ?>
              
               <div class="col-md-4">
			   <div class="form-group">
                  <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                  <div class="col-sm-6">
                    <?php echo $ProListopt; ?>
                  </div>
               </div>
			   </div>
			   <div class="col-md-4">
               <div class="form-group">
                  <label for="inputPassword3" class="col-sm-6 control-label">Region</label>
                  <div class="col-sm-6">
                      <?php $Region=array(0=>'--Select--'); ?>
                      <div id="LoadRegion">
                     <select class="form-control">
                        <option selected>Select</option>
                     </select>
                      </div>
                  </div>
               </div>
			   </div>
<!--            <div class="col-md-4">
               <div class="form-group">
                  <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
                  <div class="col-sm-6">
                      &nbsp;
                  </div>
               </div>
			   </div>-->
			   <div class="col-md-4">
               <div class="form-group">
                  <label for="inputPassword3" class="col-sm-6 control-label">Module</label>
                  <div class="col-sm-6">
                      <?php $Module=array(0=>'--Select--'); ?>
                      <div id="LoadModule">
                     <select class="form-control">
                        <option selected>Select</option>
                     </select>
                      </div>
                  </div>
               </div>
			   </div>
			   
                <div id="LoadAttribute"></div>

			   <div class="col-md-12">
			   </div>
               <div class="form-group" style="text-align:center;">
                  <div class="col-sm-12">
                    <?php
                     //echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','class'=>'btn btn-primary btn-sm pull-right','onclick'=>'return ClearFields()','type'=>'button'));   
                     //echo $this->Form->submit('submit', array( 'id' => 'submit','class'=>'btn btn-primary btn-sm pull-right', 'name' => 'submit', 'value' => 'Submit','onclick'=>'return validateForm()')); 
                     ?>		
                      <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
                  </div>
               </div>
            <?php echo $this->Form->end();  ?>
         </div>
      </div>

