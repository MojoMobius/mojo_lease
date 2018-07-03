<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>
<script type="text/javascript">
    function validateForm()
    {

        if ($('#ProjectId').val() == 0)
        {
            alert('Enter Project Id');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#ProjectId').val() != '')
        {
            var ProjectId = $('#ProjectId').val();
            var result = new Array();

            $.post("<?php echo Router::url(array('controller' => 'Projectconfig', 'action' => 'ajaxcheckproject')); ?>", {ProjectId: ProjectId}, function (result)
            {
                if (result == '') {
                    alert('Project Id not available in D2K');
                    $('#ProjectId').focus();
                    return false;
                }
                //alert(result);
                else if ($('#ProjectName').val() == 0)
                {
                    alert('Enter Project Name');
                    $('#ProjectName').focus();
                    return false;
                } else if ($('#workflow_template').val() == 0)
                {
                    alert('Select workflow template');
                    $('#workflow_template').focus();
                    return false;
                } else if ($('#default_prod_view').val() == 0)
                {
                    alert('Select default production view');
                    $('#default_prod_view').focus();
                    return false;
                } else if ($('#default_dashboard_count').val() == 0)
                {
                    alert('Default Dashboard Count is Mandatory');
                    $('#default_dashboard_count').focus();
                    return false;
                } else if (!/^[0-9]+$/.test($('#default_dashboard_count').val())) {
                    //var isValid = $('#default_dashboard_count').val();
                    alert("Only Numbers are Allowed in Default Dashboard count");
                    $('#default_dashboard_count').focus();
                    return false;
                } else if ($('#quality_limit').val() == 0)
                {
                    alert('quality limit is Mandatory');
                    $('#quality_limit').focus();
                    return false;
                } else if (!/^[0-9]+$/.test($('#quality_limit').val())) {
                    //var isValid = $('#default_dashboard_count').val();
                    alert("Only Numbers are Allowed in Quality limit");
                    $('#quality_limit').focus();
                    return false;
                } else if ($('#monthly_target').val() == 0)
                {
                    alert('Monthly target is Mandatory');
                    $('#monthly_target').focus();
                    return false;
                } else if (!/^[0-9]+$/.test($('#monthly_target').val())) {
                    //var isValid = $('#default_dashboard_count').val();
                    alert("Only Numbers are Allowed in Monthly target");
                    $('#monthly_target').focus();
                    return false;
                }
//                else if ($('#quality_limit').val() != '')
//                {
//                    var regex = /^[0-9]+$/;
//                    var isValids = $('#quality_limit').val();
//                    if(!/^[0-9]+$/.test(isValids)){
//                        alert("Only Numbers are Allowed in Quality limit");
//                        $('#quality_limit').focus();
//                        return false;
//                    }
//                }
                else if ((!$('#input_mandatory_yes').prop("checked")) && (!$('#input_mandatory_no').prop("checked")))
                {
                    alert('Select Is Input mandatory?');
                    return false;
                } else if ((!$('#input_mandatory_yes').prop("checked")) && (!$('#input_mandatory_no').prop("checked")))
                {
                    alert('Select Is Input mandatory?');
                    return false;
                } else if ((!$('#is_bulk_yes').prop("checked")) && (!$('#is_bulk_no').prop("checked")))
                {
                    alert('Select Is Bulk?');
                    return false;
                } else if ((!$('#hygine_check_yes').prop("checked")) && (!$('#hygine_check_no').prop("checked")))
                {
                    alert('Select Is HygineCheck?');
                    return false;
                } else
                {
                    document.projectforms.submit();
                }
            });
        }


    }

    function checkprojectid(ProjectId) {
        //alert(ProjectId);
//        var result = new Array();
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo Router::url(array('controller' => 'Projectconfig', 'action' => 'ajaxcheckproject'));  ?>",
//            data: ({ProjectId: ProjectId}),
//            dataType: 'text',
//            async: false,
//            success: function (result) {
//                if(result==''){
//                alert('Project Id not available in D2K');
//                setTimeout(function(){
//                    $('#ProjectId').focus();
//                    return false;
//                    }, 1);
//            
//                }else{
//                 return true;   
//                }
//                //document.getElementById('LoadRegion').innerHTML = result;
//            }
//        });
    }


</script>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Project Configuration</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project Id</label>
                <div class="col-sm-6">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project Name</label>
                <div class="col-sm-6">
                    <input type="text" name="ProjectName" id="ProjectName" value="<?php echo trim($ProjectNameEdit); ?>" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Workflow Template</label>
                <div class="col-sm-6">
                    <?php echo $ProTypeListopt; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Default Production View</label>
                <div class="col-sm-6">
                    <select class="form-control" id="default_prod_view" name="default_prod_view">
                        <option value=0> --Select-- </option>
                        <option <?php echo $selectedvertical; ?> value="1">Grid View</option>
                        <option <?php echo $selectedhorizontal; ?>  value="2">Vertical view</option>
                        <option <?php echo $selectedCengage; ?>  value="3">Cengage Process view</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Default Dashboard Count</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="default_dashboard_count" name="default_dashboard_count" value="<?php echo trim($default_dashboard_count_edit); ?>">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Quality Acceptance Limit</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="quality_limit" name="quality_limit" value="<?php echo trim($QualityLimitEdit); ?>">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Is Input Mandatory?</label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: 5px;">Yes</div>
                    <div class="col-sm-2" style="margin-top: -3px;"><input <?php echo $selectedyes; ?> type="radio" class="form-control" id="input_mandatory_yes" name="input_mandatory" value="1"></div>
                    <div class="col-sm-2" style="margin-top: 5px;">No</div>
                    <div class="col-sm-2" style="margin-top: -3px;"><input <?php echo $selectedno; ?> type="radio" class="form-control" id="input_mandatory_no" name="input_mandatory" value="2"></div>

                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Is Bulk?</label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: 3px;">Yes</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedbulkyes; ?> type="radio" class="form-control" id="is_bulk_yes" name="is_bulk" value="1"></div>
                    <div class="col-sm-2" style="margin-top: 3px;">No</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedbulkno; ?> type="radio" class="form-control" id="is_bulk_no" name="is_bulk" value="2"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Is HygineCheck?</label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: 3px;">Yes</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedhyginecheckyes; ?> type="radio" class="form-control" id="hygine_check_yes" name="hygine_check" value="1"></div>
                    <div class="col-sm-2" style="margin-top: 3px;">No</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedhyginecheckno; ?> type="radio" class="form-control" id="hygine_check_no" name="hygine_check" value="2"></div>
                </div>
            </div>
        </div>
	<div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Is Cengage Project?</label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: 3px;">Yes</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedCengageyes; ?> type="radio" class="form-control" id="cengage_project__yes" name="CengageProject" value="1"></div>
                    <div class="col-sm-2" style="margin-top: 3px;">No</div>
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedCengageno; ?> type="radio" class="form-control" id="cengage_project__no" name="CengageProject" value="0"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Monthly Target</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="monthly_target" name="monthly_target" value="<?php echo trim($monthlytargetEdit); ?>">
                </div>
            </div>
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary btn-sm" value="Submit" id="testbut" name="testbut" onclick="return validateForm()">Submit</button>
            </div>
        </div>

        <!--        <div class="form-group" style="text-align:center;">
                    <div class="col-sm-12">
        <?php //echo $this->Form->submit('Submit', array('id' => 'submit', 'name' => 'submit', 'value' => 'Submit', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return validateForm()')); ?>
                    </div>
                </div>-->
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example mt15">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>Project Id</th>
                    <th>Project Name</th>
                    <th>Edit</th>
                    <th>Config Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($ProList as $inputVal => $input) {
                    $EdiT = $this->Html->link('edit', ['action' => 'index', $input['Id']]);
                    $ConfigRole = $this->Html->link('RoleConfig', ['action' => 'config', $input['ProjectId']]);
                    ?>
                <tr>
                        <?php
                        echo '<td>' . $input['ProjectId'] . '</td>';
                        echo '<td>' . $input['ProjectName'] . '</td>';
                        echo '<td>' . $EdiT . '</td>';
                        echo '<td>' . $ConfigRole . '</td>';
                        ?>    
                </tr>
                <?php }
                ?>

            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
</div>

