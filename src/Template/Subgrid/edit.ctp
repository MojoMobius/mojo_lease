<?php 
echo $this->Form->create(array('name' => 'projectEdit', 'id' => 'projectEdit', 'style'=>'width:100%','class' => 'form-group','action'=>'/' ,'inputDefaults' => array( 'div' => false),'type'=> 'post')); ?>
<div class="form-horizontal" style="margin:0px 7px 7px 7px;">
  <div class="form-group form-group-sm form-inline">
    <?php echo $this->Form->create(array('name' => 'inputSearch', 'id' => 'inputSearch', 'class' => 'form-group', 'inputDefaults' => array( 'div' => false))); 
    echo $this->Form->input('<b> Project Name: </b> &nbsp;', array('options' => $Projects,'id' => 'ProjectId','selected'=>$ProjectId, 'name' => 'ProjectId', 'class'=>'form-control', 'onchange'=>'getAtibuteIds(this.value);' )); 
    echo '<br><br>';
    
    echo '<div id="LoadAttribute">';
    echo $this->Form->input('<b> AttributeList: </b> &nbsp;', array('options' => $Attribute,'id' => 'AttributeId','selected'=>$attributeId, 'name' => 'AttributeId', 'class'=>'form-control')); 
    echo '</div></br>';
    echo '<table id="AddOptionTable"><thead><tr><td align="center">';
    echo '<label for="AttributeList"><b> Attribute Values: </b> &nbsp;</label>';
    echo '</td><td align="center">';
    echo '<label for="AttributeList"><b> Display Order: </b> &nbsp;</label>';
    echo'</td></tr></thead><tbody>';
    
    for($i=0;$i<$assigned_details_cnt;$i++) {
    echo '<tr><td></br>';
    echo $this->Form->input('&nbsp;', array('label' => false,'id' => 'attributeOption_'.($i+1), 'name' => 'attributeOption[]','value'=>$assigned_details[$i]['OptionMasters']['DropDownValue'], 'class'=>'form-control')); 
    echo '</td><td></br> &nbsp;';
    echo $this->Form->input('&nbsp;', array('label' => false,'id' => 'displayOrder_'.($i+1), 'name' => 'displayOrder[]','value'=>$assigned_details[$i]['OptionMasters']['OrderId'], 'class'=>'form-control')); 
    echo '&nbsp';
    if($i==0){
    echo $this->Form->button('&nbsp;', array('type'=>'button','name'=>'add','class'=>'btnpdf btn-default btn-sm add_symbol', 'onclick'=>'AddRow();')); 
    echo '&nbsp';
    }
    else
    {
    echo $this->Form->button('&nbsp;', array('type'=>'button','name'=>'remove','class'=>'btnpdf btn-default btn-sm remove_symbol' , 'onclick'=>'RemoveRow('.($i+1).');')); 
    echo '&nbsp';
    }
    //echo $this->Form->button('&nbsp;', array('type'=>'button','name'=>'remove','class'=>'btnpdf btn-default btn-sm remove_symbol' , 'onclick'=>'RemoveRow('.($i+1).');')); 
    echo'</td></tr>';
    }
    echo '</tbody></table></br></br>';
    if($NotInList==1){ $checked='checked'; } else { $checked=''; }
    echo $this->Form->checkbox('Not in List', array('id' => 'NotInList', 'name' => 'NotInList','checked'=>$checked ,'class'=>'form-control')); 
    echo ' Not In List';
    echo $this->Form->submit('Submit', array( 'id' => 'submit','style'=>'margin-left:200px;float:left;padding-bottom:5 px;', 'name' => 'Edit', 'value' => 'Submit','class'=>'btn btn-warning','onclick'=>'return FormValidation()')); 
    echo $this->Form->button('Cancel', array( 'id' => 'Cancel', 'name' => 'Cancel', 'value' => 'Cancel','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:2px;','class'=>'btn btn-warning','onclick'=>'return CancelForm()','type'=>'button'));   
    echo $this->Form->end(); 
    ?>
    
  </div>
</div>


<?php echo $this->Form->end(); ?>

<script>
function FormValidation()
{
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
    
    var counter = $('#AddOptionTable tbody tr').length ;
    for(i=1;i<=counter;i++)
    {
        if($('#attributeOption_'+i).val()=='')
        {
            alert('Enter Attribute Option in Row - '+ i);
            $('#attributeOption_'+i).focus();
            return false;
        }
        if($('#displayOrder_'+i).val()=='')
        {
            alert('Enter Dispaly Order in Row - '+ i);
            $('#displayOrder_'+i).focus();
            return false;
        }
        
        for(j=1;j<=counter;j++)
        {
            if(i!=j)
            {
                if($('#attributeOption_'+i).val() == $('#attributeOption_'+j).val())
                {
                    alert("Attribute Option Entered in Row "+ i + " matched with Row "+j );
                    $('#attributeOption_'+j).focus();
                    return false;
                }
                if($('#displayOrder_'+i).val() == $('#displayOrder_'+j).val())
                {
                    alert("Display order Entered in Row "+ i + " matched with Row "+j );
                    $('#displayOrder_'+j).focus();
                    return false;
                }
            }
        }
    }
    
}
function CancelForm()
{
    window.location.href = '<?php echo $this->Html->url('/OptionMasters'); ?>'
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
    cols +='<td></br><input type="text" name="attributeOption[]" id="attributeOption_'+count+'" class="form-control"></td><td></br>&nbsp;<input type="text" name="displayOrder[]" id="displayOrder_'+count+'" class="form-control">';
    cols +='&nbsp;';
    cols +='<input type="button" name="remove" class="btnpdf btn-default btn-sm remove_symbol" onclick="RemoveRow('+count+');"></td>';
    
    newRow.append(cols);
    $("#AddOptionTable").append(newRow);
}
function RemoveRow(r) {
    var counter = $('#AddOptionTable tbody tr').length ;
    if(counter>1){
    $("#AddOptionTable tbody tr:nth-child("+r+")").remove();
     var table = document.getElementById('AddOptionTable');
        for (var r = 1, n = table.rows.length; r < n; r++) {    
            for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {
               if(c==0)
               {
                   var nodes = table.rows[r].cells[c].childNodes;
                   for(var i=0; i<nodes.length; i++) {
                       if(nodes[i].nodeName.toLowerCase() == 'input')
                       nodes[i].id='attributeOption_'+r;
                    }
               }
               if(c==1) {
                   var nodes = table.rows[r].cells[c].childNodes;
                   for(var i=0; i<nodes.length; i++) {
                       
                       if(nodes[i].nodeName.toLowerCase() == 'input')
                            nodes[i].id='displayOrder_'+r;
                        
                        if(i==4 && r>1)
                        {
                            nodes[i].setAttribute('onclick',"RemoveRow("+r+")");
                        }
                        
                    }
               }
               
               
            }
        }
        
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
    cols +='<td></br>&nbsp<input type="text" name="attributeOption[]" id="attributeOption_'+count+'" value="Not In List" class="form-control"></td><td></br>&nbsp;<input type="text" name="displayOrder[]" id="displayOrder_'+count+'" class="form-control" value="'+count+'">';
    cols +='&nbsp;<input type="button" class="btnpdf btn-default btn-sm add_symbol" onclick="AddRow();">&nbsp;';
    cols +='<input type="button" class="btnpdf btn-default btn-sm remove_symbol" onclick="RemoveRow();"></td>';
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
    


