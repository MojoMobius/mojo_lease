<?php echo $this->Form->create(array('name' => 'projectEdit', 'id' => 'projectEdit', 'style'=>'width:100%','class' => 'form-group','action'=>'/' ,'inputDefaults' => array( 'div' => false),'type'=> 'post')); ?>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
  <div class="form-group form-group-sm form-inline">
    <?php echo $this->Form->create(array('name' => 'inputSearch', 'id' => 'inputSearch', 'class' => 'form-group', 'inputDefaults' => array( 'div' => false))); 
    echo $this->Form->input('<b> Project Name: </b> &nbsp;', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'class'=>'form-control', 'onchange'=>'getAtibuteIds(this.value);' )); 
    echo '<br><br>';
    $Attribute=array(0=>'--Select--');
    echo '<div id="LoadAttribute">';
    echo $this->Form->input('<b> AttributeList: </b> &nbsp;', array('options' => $Attribute,'id' => 'AttributeId', 'name' => 'AttributeId', 'class'=>'form-control')); 
    echo '</div></br>';
    echo '<table id="AddOptionTable"><tr><td align="center">';
    echo '<label for="AttributeList"><b> Attribute Option: </b> &nbsp;</label>';
    echo '</td><td align="center">';
    echo '<label for="AttributeList"><b> Display Order: </b> &nbsp;</label>';
    echo'</td></tr><tr><td>';
    echo $this->Form->input('&nbsp;', array('id' => 'attributeOption_1', 'name' => 'attributeOption[]', 'class'=>'form-control')); 
    echo '</td><td>';
    echo $this->Form->input('&nbsp;', array('id' => 'displayOrder_1', 'name' => 'displayOrder[]', 'class'=>'form-control')); 
    echo '&nbsp';
    echo $this->Form->button('&nbsp;', array('type'=>'button','class'=>'btnpdf btn-default btn-sm add_symbol', 'onclick'=>'AddRow();')); 
    echo '&nbsp';
    echo $this->Form->button('&nbsp;', array('type'=>'button','class'=>'btnpdf btn-default btn-sm remove_symbol' , 'onclick'=>'RemoveRow();')); 
    echo'</td></tr></table></br></br>';
    echo $this->Form->checkbox('Not in List', array('id' => 'NotInList', 'name' => 'NotInList','value'=>'1', 'class'=>'form-control'  )); 
    echo ' Not In List';
    echo $this->Form->submit('Submit', array( 'id' => 'submit','style'=>'margin-left:200px;float:left;padding-bottom:5 px;', 'name' => 'submit', 'value' => 'Submit','class'=>'btn btn-warning','onclick'=>'return valdateForm()')); 
    echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:2px;','class'=>'btn btn-warning','onclick'=>'return ClearFields()','type'=>'button'));   
    echo $this->Form->end(); 
    ?>
    
  </div>
</div>


<?php echo $this->Form->end(); ?>

<script>
function FormValidation()
{
    
    if($('#ProcessType').val()==0)
    {
        alert('Select ProcessType!')
        $('#ProcessType').focus();
        return false;
        
    }
    if($('#AllocationAlogritham').val()==0)
    {
        alert('Select AllocationAlogritham!')
        $('#AllocationAlogritham').focus();
        return false;
        
    }
    if($('#DownloadType').val()==0)
    {
        alert('Select DownloadType!')
        $('#DownloadType').focus();
        return false;
        
    }
    if($('#LeaseIdLevel').val()==0)
    {
        alert('Select LeaseIdLevel!')
        $('#LeaseIdLevel').focus();
        return false;
        
    }
    
}
function CancelForm()
{
    window.location.href = '<?php echo $this->Html->url('/ProjectConfigs'); ?>'
}
function getAtibuteIds(projectId)
{
    $.post('<?php echo $this->Html->url('/OptionMasters/ajax_getAtibuteIds'); ?>',{projectId:projectId}, function(result){
      document.getElementById('LoadAttribute').innerHTML=result;
    });
}
function AddRow() {
    if($('#ProjectId').val()==0){
        alert('Please Select Project Name');
        $('#ProjectId').focus();
        return false;
    }
    if($('#AttributeId').val()==0){
        alert('Please Select Attribute List');
        $('#AttributeId').focus();
        return false;
    }
    var count = $('#AddOptionTable tr').length ;
    var newRow = $("<tr>");
    var cols = "";
    cols +='<td></br>&nbsp<input type="text" name="attributeOption[]" id="attributeOption_'+count+'" class="form-control"></td><td></br>&nbsp;<input type="text" name="displayOrder[]" id="displayOrder_'+count+'" class="form-control"></td>';
    newRow.append(cols);
    $("#AddOptionTable").append(newRow);
}
function RemoveRow() {
    var counter = $('#AddOptionTable tr').length ;
    if(counter>2){
    $("#AddOptionTable tr:last-child").remove(); 
    }
    else
    {
        alert('Minimum One Row Required')
    }
}
function AddNotInList() {
    var count = $('#AddOptionTable tr').length ;
    if($('#NotInList').is(":checked")) {
    var newRow = $("<tr>");
    var cols = "";
    cols +='<td></br>&nbsp<input type="text" name="attributeOption[]" id="attributeOption_'+count+'" value="Not In List" class="form-control"></td><td></br>&nbsp;<input type="text" name="displayOrder[]" id="displayOrder_'+count+'" class="form-control" value="'+count+'"></td>';
    newRow.append(cols);
    $("#AddOptionTable").append(newRow);
    }
    else {
        var cnt= count-1;
        if($('#attributeOption_'+cnt).val()=='Not In List'){
            $("#AddOptionTable tr:last-child").remove();
            
        }
    }
}
</script>
    


