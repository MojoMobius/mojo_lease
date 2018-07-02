<!--Form : Production Field Mapping
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
    if($('#TemplateMasterId').val()==0)
    {
    alert('Select Template Name');
    $('#TemplateMasterId').focus();
    return false;
    }
    var checkboxChecked = $('input[name="Attribute[]"]:checked').length;
   if(checkboxChecked ==0) {
       alert('Select atleast one Attribute to do mapping');
       return false;
   }
}
function getRegion(ProjectId) {
    //alert(ProjectId);
    var result = new Array();
        $.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Productionfieldsmapping','action'=>'ajaxregion'));?>",
                data:({ProjectId:ProjectId}),
                dataType: 'text',
                async:false,
                success: function(result){
                    //alert(result);
                   document.getElementById('LoadRegion').innerHTML = result; 
                }
            });
    }
    function getTemplate()
{
    var result = new Array();
    var ProjectId=$('#ProjectId').val();
        $.ajax({
        type:"POST",
        url:"<?php echo Router::url(array('controller'=>'Productionfieldsmapping','action'=>'ajaxtemplate')); ?>",
        data:({ProjectId:ProjectId}),
        dataType: 'text',
                async:false,
                success: function(result){
                   document.getElementById('LoadTemplate').innerHTML = result; 
                }
        });
}
    function getModule()
    {
        var result = new Array();
        var ProjectId=$('#ProjectId').val();
        $.ajax({
        type:"POST",
        url:"<?php echo Router::url(array('controller'=>'Productionfieldsmapping','action'=>'ajaxmodule')); ?>",
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
        var TemplateMasterId=$('#TemplateMasterId').val();
        var ModuleId=$('#ModuleId').val();
        $.ajax({
        type:"POST",
        url:"<?php echo Router::url(array('controller'=>'Productionfieldsmapping','action'=>'ajaxattribute')); ?>",
        data:({ProjectId:ProjectId,RegionId:RegionId,TemplateMasterId:TemplateMasterId,ModuleId:ModuleId}),
        dataType: 'text',
                async:false,
                success: function(result){
                   document.getElementById('LoadAttribute').innerHTML = result; 
                }
        });
    }
   
   function checkAll() {

        var select_all = document.getElementById("select_all"); //select all checkbox
        var checkboxes = document.getElementsByClassName("chk-wid"); //checkbox items

        //select all checkboxes
        select_all.addEventListener("change", function(e){
            for (i = 0; i < checkboxes.length; i++) { 
                checkboxes[i].checked = select_all.checked;
            }
        });


        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function(e){ //".checkbox" change 
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if(this.checked == false){
                    select_all.checked = false;
                }

              $(".chk-wid").change(function(){
            //check "select all" if all checkbox item are checked
            if ($(".chk-wid:checked").length == $(".chk-wid").length ){
                $("#select_all").prop("checked", true);
            }
        });
            });
        }
 }
 function checkAllAtt() {

    var select_all = document.getElementById("select_all"); //select all checkbox
    var checkboxes = document.getElementsByClassName("chk-wid"); //checkbox items


    for (var i = 0; i < checkboxes.length; i++) {
            //uncheck "select all", if one of the listed checkbox item is unchecked
           $(".chk-wid").change(function(){   
                if(this.checked == false){
                select_all.checked = false;
            }

        //check "select all" if all checkbox item are checked
        if ($(".chk-wid:checked").length == $(".chk-wid").length ){
            $("#select_all").prop("checked", true);
        }
    });

    }
 }
 

    </script>


<div class="container-fluid">
         <div class="jumbotron formcontent">
            <h4>Production Template</h4>
                <?php echo $this->Form->create($ProductionFieldsMapping,array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
			<div class="col-md-12">
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
				   <div class="col-md-4">
				   <div class="form-group">
					  <label for="inputPassword3" class="col-sm-6 control-label">Module Name</label>
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
			   </div>
			   <div class="col-md-12">
				   <div class="col-md-4">
				   <div class="form-group">
					  <label for="inputPassword3" class="col-sm-6 control-label">Template Type</label>
					  <div class="col-sm-6">
                                              <?php $Template=array(0=>'--Select--'); ?>
                                              <div id="LoadTemplate">
						 <select class="form-control">
							<option selected>Select</option>
						 </select>
                                              </div>
					  </div>
				   </div>
				   </div>
				   <div class="col-md-4">
				   <div class="form-group">
					  <label for="inputPassword3" class="col-sm-6 control-label"></label>
					  <div class="col-sm-6">
						&nbsp; 
					  </div>
				   </div>
				   </div>
				   <div class="col-md-4">
				   <div class="form-group">
					  <label for="inputPassword3" class="col-sm-6 control-label"></label>
					  <div class="col-sm-6">
						&nbsp; 
					  </div>
				   </div>
				   </div>
				   </div>
                                   <div id="LoadAttribute"></div>
				   
                                   <div class="form-group" style="text-align:center;">
                  <div class="col-sm-12">
                    <?php
                        echo $this->Form->submit('Submit', array( 'id' => 'submit', 'name' => 'submit', 'value' => 'Submit','style'=>'width:70px;','class'=>'btn btn-primary btn-sm','onclick'=>'return validateForm()')); 
                    //    echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:2px;','class'=>'btn btn-warning','onclick'=>'return ClearFields()','type'=>'button'));   
                     ?>		 
                  </div>
               </div>
            <?php echo $this->Form->end(); ?>

            
            
         </div>
      </div>

