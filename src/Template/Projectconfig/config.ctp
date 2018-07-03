
<?php 
 use Cake\Routing\Router
?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Project Configuration</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms', 'name' => 'projectforms')); ?>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label"><b>Project:</b></label>
                <div class="col-sm-6" style="margin-top:3px;" >
                    <input type="hidden" name='ProjectId' value=<?php echo $ProjectId;?>> 
                    <?php 
                    echo $Projects[$ProjectId];
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <div class="col-sm-12" style="margin-top:3px;" >
                     <?php 
                    for ($i = 0; $i < count($Module); $i++) {
                    $ModuleIdName = 'UserList_'.$Module[$i]['Id'].'';  
                    echo '<br>'; ?>
                    <b>  <?php echo $ModuleName[$i+1]; ?> </b>
                    <?php  echo $this->Form->input('', array('options' => $RoleName, 'class' => 'form-control', 'id' => $ModuleIdName, 'name' => $ModuleIdName, 'selected' => $selected[$i], 'style' => 'height:100px', 'multiple' => true, 'value' => $selected[$i]));
                    }
                  ?>
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
