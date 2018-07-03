
<?php 
 use Cake\Routing\Router
?>

<div class="container-fluid">
    <div class="formcontent">
        <h4>User Group Master Edit</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label" style="padding-top:10px;"><b>Group Name</b></label>
                <div class="col-sm-6" style="margin-top:3px;" >
                     <?php 
                    $i = 0;
                                if ($assigned_details[$i]['GroupName'] != '') { ?>
                    <input name="UserGroupName" class="form-control" id="UserGroupName_<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigned_details[$i]['GroupName'] ?>"></td>
                             <?php }else {?>
                    <td class="non-bor"> <input name="UserGroupName" id="UserGroupName_<?php echo ($i + 1); ?>" style="width:141px;" class="form-control"></td>
                        <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="Save" class="btn btn-primary btn-sm" onclick="return ValidateForm()">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">

    function ValidateForm() {
        if ($('#UserGroupName_1').val() == 0) {
            alert('Enter Group Name value');
            $('#UserGroupName_1').focus();
            return false;
        }
    }


</script>

