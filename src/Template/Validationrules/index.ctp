<!--Form : Dropdown Mapping
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>
<!--<script>jQuery.noConflict();</script>-->
<!--<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>-->
<script type="text/javascript">
    
		
		
    function validateForm()
    {
        if ($('#ProjectId').val() == 0)
        {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#RegionId').val() == 0)
        {
            alert('Select Region Name');
            $('#RegionId').focus();
            return false;
        }
                if ($('#ModuleId').val() == 0)
        {
            alert('Select Module Name');
            $('#ModuleId').focus();
            return false;
        }
        var checkboxChecked = $('input[type="checkbox"]:checked').length;
        if (checkboxChecked == 0) {
            alert('Select atleast one Attribute to do mapping.');
            return false;
        }

    }
    
    function allowedchar(id)
    {
       var notallow = $('#NotAllow_'+id).val() ;
       var allow = $('#Allow_'+id).val() ;
       notallow = notallow.split("");
       allow = allow.split("");
       var error = false;
        for (var i = 0; i < allow.length; i++) {
            if (notallow.indexOf(allow[i]) !== -1) {
                error = true;        
                break;
            }
        }
        if(error==true){
            alert('Allowed and NotAllowed values are not should be same');
            $('#Allow_'+id).val("");
            $('#Allow_'+id).focus();
        }else{
            return true;
        }
    }
    function notallowedchar(id)
    {
       var notallow = $('#NotAllow_'+id).val() ;
       var allow = $('#Allow_'+id).val() ;
       notallow = notallow.split("");
       allow = allow.split("");
       var error = false;
        for (var i = 0; i < notallow.length; i++) {
            if (allow.indexOf(notallow[i]) !== -1) {
                error = true;        
                break;
            }
        }
        if(error==true){
            alert('NotAllowed and Allowed values are not should be same');
            $('#NotAllow_'+id).val("");
            $('#NotAllow_'+id).focus();
        }else{
            return true;
        }
    }
//    function allowedchar(id)
//    {
//       var notallow = $('#NotAllow_'+id).val() ;
//       if(notallow != ''){
//           $('#Allow_'+id).val("");
//           $('#NotAllow_'+id).focus();
//           alert('Enter either Allowed or NotAllowed.');
//       }
//    }
//    function notallowedchar(id)
//    {
//       var allow = $('#Allow_'+id).val() ;
//       if(allow != ''){
//           $('#NotAllow_'+id).val("");
//           $('#Allow_'+id).focus();
//           alert('Enter either Allowed or NotAllowed.');
//       }
//    }

    function getRegion(ProjectId) {
        //alert(ProjectId);
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Validationrules', 'action' => 'ajaxregion')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //alert(result);
                document.getElementById('LoadRegion').innerHTML = result;
                //$(document).ready(function() {
        
                                
			//});
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
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var ModuleId = $('#ModuleId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Validationrules', 'action' => 'ajaxattribute')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadAttribute').innerHTML = result;
                $("#fixTable").tableHeadFixer({"left" : 1}); 
            }
        });
    }
    
    function emailCheck(chkval){
    if (($("#IsEmail_" + chkval + ":checked").val()) != undefined) {
            $("#IsUrl_" + chkval).attr("disabled", "disabled");
            $("#IsUrl_" + chkval).prop('checked', false);
            
            $("#IsDate_" + chkval).attr("disabled", "disabled");
            $("#IsDate_" + chkval).prop('checked', false);
            $("#DateFormat_" + chkval).prop('selectedIndex', 0);
            $("#DateFormat_" + chkval).css('pointer-events','none');
            $("#DateFormat_" + chkval).attr("readonly", "readonly");
            
             $("#IsSpl_" + chkval).attr("disabled", "disabled");
            $("#IsSpl_" + chkval).prop('checked', false);
             $("#Allow_" + chkval).val('');
            $("#Allow_" + chkval).attr("readonly", "readonly");
            $("#NotAllow_" + chkval).val('');
            $("#NotAllow_" + chkval).attr("readonly", "readonly");
            
            $("#IsDecimal_" + chkval).attr("disabled", "disabled");
            $("#IsDecimal_" + chkval).prop('checked', false);
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
        }
    if (($("#IsEmail_" + chkval + ":checked").val()) == undefined) {
           $("#IsUrl_" + chkval).removeAttr("disabled", "disabled");
            
           $("#IsDate_" + chkval).removeAttr("disabled", "disabled");
   
           $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
           
            $("#IsSpl_" + chkval).removeAttr("disabled", "disabled");
        }
    }
    
     function urlCheck(chkval){
    if (($("#IsUrl_" + chkval + ":checked").val()) != undefined) {
            $("#IsEmail_" + chkval).attr("disabled", "disabled");
            $("#IsEmail_" + chkval).prop('checked', false);
           
            $("#IsDate_" + chkval).attr("disabled", "disabled");
            $("#IsDate_" + chkval).prop('checked', false);
            $("#DateFormat_" + chkval).prop('selectedIndex', 0);
            $("#DateFormat_" + chkval).css('pointer-events','none');
            $("#DateFormat_" + chkval).attr("readonly", "readonly");
            
            $("#IsDecimal_" + chkval).attr("disabled", "disabled");
            $("#IsDecimal_" + chkval).prop('checked', false);
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
            
            $("#IsDecimal_" + chkval).attr("disabled", "disabled");
            $("#IsDecimal_" + chkval).prop('checked', false);
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
        }
     if (($("#IsUrl_" + chkval + ":checked").val()) == undefined) {
           $("#IsEmail_" + chkval).removeAttr("disabled", "disabled");
          
           $("#IsDate_" + chkval).removeAttr("disabled", "disabled");
          
           $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
        }
    }
    
    function updteStatuschk(chkval)
    {
        if (($("#IsSpl_" + chkval + ":checked").val()) != undefined) {
            $("#Allow_" + chkval).removeAttr("disabled", "disabled");
            $("#NotAllow_" + chkval).removeAttr("disabled", "disabled");
        }
        if (($("#IsSpl_" + chkval + ":checked").val()) == undefined)
        {
            $("#Allow_" + chkval).val('');
            $("#Allow_" + chkval).attr("disabled", "disabled");
            $("#NotAllow_" + chkval).val('');
            $("#NotAllow_" + chkval).attr("disabled", "disabled");
        }
    }

    function updteStatuschk1(chkval)
    {
        if (($("#IsSpl_" + chkval + ":checked").val()) != undefined) {
            $("#Allow_" + chkval).removeAttr("readonly", "readonly");
            $("#NotAllow_" + chkval).removeAttr("readonly", "readonly");
            
              $("#IsUrl_" + chkval).attr("disabled", "disabled");
            $("#IsUrl_" + chkval).prop('checked', false);
            
            $("#IsDate_" + chkval).attr("disabled", "disabled");
            $("#IsDate_" + chkval).prop('checked', false);
            $("#DateFormat_" + chkval).prop('selectedIndex', 0);
            $("#DateFormat_" + chkval).css('pointer-events','none');
            $("#DateFormat_" + chkval).attr("readonly", "readonly");
            
             $("#IsEmail_" + chkval).attr("disabled", "disabled");
            $("#IsEmail_" + chkval).prop('checked', false);
            
             $("#IsDecimal_" + chkval).attr("disabled", "disabled");
            $("#IsDecimal_" + chkval).prop('checked', false);
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
            
        }
        if (($("#IsSpl_" + chkval + ":checked").val()) == undefined)
        {
            $("#Allow_" + chkval).val('');
            $("#Allow_" + chkval).attr("readonly", "readonly");
            $("#NotAllow_" + chkval).val('');
            $("#NotAllow_" + chkval).attr("readonly", "readonly");
            
              $("#IsUrl_" + chkval).removeAttr("disabled", "disabled");
            
           $("#IsDate_" + chkval).removeAttr("disabled", "disabled");
   
           $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
           
             $("#IsEmail_" + chkval).removeAttr("disabled", "disabled");
        }
    }

    function updteDatechk(chkval)
    {
        var test = $("#IsDate_" + chkval + ":checked").val();

        if (($("#IsDate_" + chkval + ":checked").val()) != undefined) {
            $("#DateFormat_" + chkval).prop('selectedIndex', 1);
            $("#DateFormat_" + chkval).css('pointer-events','');
            $("#DateFormat_" + chkval).removeAttr("readonly", "readonly");
            
            $("#IsNumeric_" + chkval).attr("disabled", "disabled");
            $("#IsNumeric_" + chkval).prop('checked', false);
            
            $("#IsAlphabet_" + chkval).attr("disabled", "disabled");
            $("#IsAlphabet_" + chkval).prop('checked', false);
            
            $("#IsEmail_" + chkval).attr("disabled", "disabled");
            $("#IsEmail_" + chkval).prop('checked', false);
            
            $("#IsUrl_" + chkval).attr("disabled", "disabled");
            $("#IsUrl_" + chkval).prop('checked', false);
            
            $("#IsDecimal_" + chkval).attr("disabled", "disabled");
            $("#IsDecimal_" + chkval).prop('checked', false);
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
            
            $("#IsSpl_" + chkval).attr("disabled", "disabled");
            $("#IsSpl_" + chkval).prop('checked', false);
            $("#Allow_" + chkval).val('');
            $("#Allow_" + chkval).attr("readonly", "readonly");
            $("#NotAllow_" + chkval).val('');
            $("#NotAllow_" + chkval).attr("readonly", "readonly");
            
            $("#MaxLength_" + chkval).val('');
            $("#MaxLength_" + chkval).attr("readonly", "readonly");
            
            $("#Format_" + chkval).val('');
            $("#Format_" + chkval).attr("readonly", "readonly");
            
            $("#MinLength_" + chkval).val('');
            $("#MinLength_" + chkval).attr("readonly", "readonly");
        }
        if (($("#IsDate_" + chkval + ":checked").val()) == undefined)
        {
            $("#DateFormat_" + chkval).prop('selectedIndex', 0);
            $("#DateFormat_" + chkval).css('pointer-events','none');
            $("#DateFormat_" + chkval).attr("readonly", "readonly");
            
            $("#IsNumeric_" + chkval).removeAttr("disabled", "disabled");
            
            $("#IsAlphabet_" + chkval).removeAttr("disabled", "disabled");
            
            $("#IsEmail_" + chkval).removeAttr("disabled", "disabled");
          
            $("#IsUrl_" + chkval).removeAttr("disabled", "disabled");
          
            $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
            
            $("#IsSpl_" + chkval).removeAttr("disabled", "disabled");
            
            $("#MaxLength_" + chkval).removeAttr("readonly", "");
            
            $("#MinLength_" + chkval).removeAttr("readonly", "");
            
            $("#Format_" + chkval).removeAttr("readonly", "");
        }
    }
function checkLength(chkval,val){
  
    if (($("#IsDecimal_" + chkval + ":checked").val()) != undefined) {
      if(val == ''){
       alert('Please enter allowed decimal point');
      //  $("#AllowedDecimalPoint_" + chkval).focus();
        setTimeout(function() { document.getElementById("AllowedDecimalPoint_" + chkval).focus(); }, 10);
            return false;
        }
    }
}
    function updteDecimalchk(chkval)
    {

        if (($("#IsDecimal_" + chkval + ":checked").val()) != undefined) {
               
            $("#AllowedDecimalPoint_" + chkval).removeAttr("readonly", "readonly");
            
            $("#AllowedDecimalPoint_" + chkval).focus();
               
            $("#IsNumeric_" + chkval).attr("disabled", "disabled");
            $("#IsNumeric_" + chkval).prop('checked', false);
            
            $("#IsAlphabet_" + chkval).attr("disabled", "disabled");
            $("#IsAlphabet_" + chkval).prop('checked', false);
            
            $("#IsEmail_" + chkval).attr("disabled", "disabled");
            $("#IsEmail_" + chkval).prop('checked', false);
            
            $("#IsUrl_" + chkval).attr("disabled", "disabled");
            $("#IsUrl_" + chkval).prop('checked', false);
            
            $("#IsSpl_" + chkval).attr("disabled", "disabled");
            $("#IsSpl_" + chkval).prop('checked', false);
            
            $("#IsDate_" + chkval).attr("disabled", "disabled");
            $("#IsDate_" + chkval).prop('checked', false);
            $("#DateFormat_" + chkval).prop('selectedIndex', 0);
            $("#DateFormat_" + chkval).css('pointer-events','none');
            $("#DateFormat_" + chkval).attr("readonly", "readonly");
            
            
            
        }
        if (($("#IsDecimal_" + chkval + ":checked").val()) == undefined)
        {
        
            $("#AllowedDecimalPoint_" + chkval).val('');
            $("#AllowedDecimalPoint_" + chkval).attr("readonly", "readonly");
            
            $("#IsNumeric_" + chkval).removeAttr("disabled", "disabled");
            
            $("#IsAlphabet_" + chkval).removeAttr("disabled", "disabled");
            
            $("#IsEmail_" + chkval).removeAttr("disabled", "disabled");
          
            $("#IsUrl_" + chkval).removeAttr("disabled", "disabled");
          
            $("#IsDate_" + chkval).removeAttr("disabled", "disabled");
            
             $("#IsSpl_" + chkval).removeAttr("disabled", "disabled");
        }
    }

 function numericCheck(chkval){
   // alert($("#IsEmail_" + chkval + ":checked").val());
     if (($("#IsNumeric_" + chkval + ":checked").val()) != undefined) {
             $("#IsDecimal_" + chkval).val('');
            $("#IsDecimal_" + chkval).attr("disabled", "disabled");
           
        }
        if (($("#IsNumeric_" + chkval + ":checked").val()) == undefined)
        {
           $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
            $("#IsDecimal_" + chkval).removeAttr("disabled", "disabled");
        }
    }

			
</script>
<!--<style type="text/css">
th{position: fixed;}
</style>-->
<style type="text/css">#parent {height: 500px;}#fixTable {width: 1800px !important;}</style>
<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Validation Rule Engine</h4>
        <?php echo $this->Form->create($Validationrules, array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
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
                    <?php $Region = array(0 => '--Select--'); ?>
                    <div id="LoadRegion">
                        <?php
                        echo $RegList;
                        if ($RegList == '') {
                            ?>
                            <select class="form-control">
                                <option selected>Select</option>
                            </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">Module</label>
                        <div class="col-sm-6">
        <?php $Module = array(0 => '--Select--'); ?>
                            <div id="LoadModule">
        <?php
        echo $ModuleList;
        if ($ModuleList == '') {
            ?>
                                        <select class="form-control" id="module">
                                            <option selected>Select</option>
                                        </select>
        <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
        <br>
        <div id="LoadAttribute"></div>
        <br>        





        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <!--                     <button type="cancel" class="btn btn-primary btn-sm pull-right">Cancel</button>
                                     <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>-->
                
                <?php //echo $this->Form->submit('Cancel', array('id' => 'cancel', 'name' => 'cancel', 'value' => 'Cancel', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return CancelForm()')); ?>
                <?php //echo $this->Form->submit('Submit', array('id' => 'submit', 'name' => 'submit', 'value' => 'Submit', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return validateForm()'));
                ?>
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
            </div>
        </div>
       
        <?php echo $this->Form->end(); ?>
    </div>


</div>

