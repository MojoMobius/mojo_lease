<?php

use Cake\Routing\Router;
?>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>Production View</h4>
        <?php echo $this->Form->create('', array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project Name</label>
                <div class="col-sm-6">
                    <?php
                    echo $this->Form->input('', array('options' => $Projects, 'id' => 'ProjectId', 'name' => 'ProjectId', 'class' => 'form-control prodash-txt', 'value' => $ProjectId, 'onchange' => 'getRegion(this.value);'));
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Region Name</label>
                <div class="col-sm-6">
                    <?php
                    if ($RegionId == '') {
                        $Region = array(0 => '--Select--');
                        echo '<div id="LoadRegion">';
//                        echo $this->Form->input('', array('options' => $Region, 'id' => 'RegionId', 'name' => 'RegionId', 'class' => 'form-control', 'value' => $RegionId, 'onchange' => 'getModule(this.value)'));
                        echo $this->Form->input('', array('options' => $Region, 'id' => 'RegionId', 'name' => 'RegionId', 'class' => 'form-control', 'value' => $RegionId, 'onchange' => 'getModule(this.value); getusergroupdetails(this.value);'));
                        echo '</div>';
                    } else {
                        echo $RegionId;
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="UserGroupId" class="col-sm-6 control-label">User Group</label>
                <div class="col-sm-6 prodash-txt">
                    <?php
                    if ($UserGroupId == '') {
                        $UserGroup = array(0 => '--Select--');
                        echo '<div id="LoadUserGroup">';
                        echo $this->Form->input('', array('options' => $UserGroup, 'id' => 'UserGroupId', 'name' => 'UserGroupId', 'class' => 'form-control', 'value' => $UserGroupId, 'selected' => $UserGroupId, 'onchange' => 'getresourcedetails'));
                        echo '</div>';
                    } else {
                        echo $UserGroupId;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Module</label>
                <div class="col-sm-6">
                    <?php
                    if ($ModuleIds == '') {
                        $Modules = array(0 => '--Select--');
                        ?>
                        <div id="LoadModule">
                            <?php
                            echo $this->Form->input('', array('options' => $Modules, 'id' => 'ModuleIds', 'name' => 'ModuleIds', 'class' => 'form-control prodash-txt', 'value' => $ModuleIds, 'onchange' => 'getStatus(this.value);'));
                            //echo $ModuleList;
                            ?>
                        </div>
                    <?php
                    } else {
                        echo $ModuleIds;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">From</label>
                <div class="col-sm-6 prodash-txt">
<?php
echo $this->Form->input('', array('id' => 'batch_from', 'name' => 'batch_from', 'class' => 'form-control', 'value' => $postbatch_from));
?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">To</label>
                <div class="col-sm-6 prodash-txt" >
<?php
echo $this->Form->input('', array('id' => 'batch_to', 'name' => 'batch_to', 'class' => 'form-control', 'value' => $postbatch_to));
?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Resource</label>
                <div class="col-sm-6 prodash-txt">
                    <div id="LoadUserDetails">
<?php
echo $this->Form->input('', array('default' => '0', 'options' => $User, 'class' => 'form-control', 'selected' => $postuser_id, 'value' => $postuser_id, 'id' => 'user_id', 'name' => 'user_id', 'style' => 'width:200px; margin-top:0px;', 'multiple' => true));
?>
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">Status</label>
                        <div class="col-sm-6 prodash-txt" >
                            <div id="LoadStatus">
        <?php
        echo $selstatus;
        if ($selstatus == '') {
            ?>
                                        <select class="form-control" style="margin-top:15px;width:200px ;" multiple="multiple">
                                            <option></option>
                                        </select>
<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>-->
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Search</label>
                <div class="col-sm-6 prodash-txt" >
<?php
echo $this->Form->input('', array('id' => 'query', 'placeholder' => 'Search', 'name' => 'query', 'class' => 'form-control', 'value' => $postquery));
?>
                </div>
            </div>
        </div>

        <!--        <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">Status</label>
                        <div class="col-sm-6">
        <?php $Status = array(0 => '--Select--'); ?>
                            <div id="LoadStatus">
        <?php
        echo $StatusList;
        if ($StatusList == '') {
            ?>
                                        <select class="form-control">
                                            <option selected>Select</option>
                                        </select>
<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>-->

        <!--<div class="col-md-3">
                    <div class="form-group">
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                    </div>
                </div>-->
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <?php
                echo $this->Form->button('Search', array('id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Search', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()'));

                echo $this->Form->button('Clear', array('id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear', 'style' => 'margin-left:5px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return ClearFields()', 'type' => 'button'));


                if (count($Production_dashboard) > 0) {
                    echo '<br>';
                    echo '<br>';
                    echo '<br>';
                    ?> 
                </div>
            </div>   


            <!--        <div id='xlscnt'>
            
                        <button type='submit' name='downloadFile' id='downloadFile' value='downloadFile'><img width="20" height="20" src="img/file-xls.jpg"><img width="12" height="12" src="img/down_arrow.gif"></button>
                    </div>-->


            <?php
        }
        echo $this->Form->end();
        ?>
    </div>


    <?php
    echo $error;

    if (count($Production_dashboard) > 0) {
        ?>



        <div class="bs-example">
            <div id='detail' class="bs-example">
                <table style='width:100%;' class='table table-striped table-center'>
                    <input type="hidden" name="UpdateId" id="UpdateId">
                    <?php echo $this->Html->tableHeaders(array('Project Id', 'DomainId', 'Resource', 'UserGroup', 'Status', 'Production Start Date', 'Production End Date', 'Production Time', 'View'), array('class' => 'Heading'), array('class' => 'Cell', 'width' => '10%')); ?>
                    <?php
                    $i = 0;
//pr($Statusid);
                    //pr($tableName);
//                pr($UserGroup);
//                exit;
                    foreach ($Production_dashboard as $inputVal => $input):

                        $options = array($input['Id'] => '');
                        $teset = '';
                        $DId = $input['AttributeValue'];
                        $ProjectId = $input['ProjectId'];
                        $SId = $input['StatusId'];
                        $PId = $input['Id'];
                        $UId = $input['UserId'];
                        $DomainId = $input['domainId'];
                        $InputEntId = $input['InputEntityId'];
                        $table = date("n_Y", strtotime($input['ProductionStartDate']));
                        $Resource = $User[$timeDetails[$ModuleId][$input['Id']]['UserId']];
                        $UserGroupId = $timeDetails[$ModuleId][$input['Id']]['UserGroupId'];

                        //$table = $tableName['tablename'];
                        //$table = '';
                        $attributes = array('name' => 'checkbox', 'id' => 'checkbox', 'class' => 'checkbox', 'onClick' => "Setvalue('$PId');");
                        //if (($input['StatusId'] == 'Query' || $input['StatusId'] == 'Production Rework') && $input['UserId'] != '') {
                        if ($Resource != '') {
                            //$teset=$this->Form->checkbox('', $options, $attributes);
                            //$teset=$this->Form->checkbox('', array('hiddenField' => false,'onClick'=>"Setvalue('$PId');",'id'=>'checkbox','class' => 'checkbox','name'=>'sample[]'));
                            //$EdiT = $this->Html->link('Click to View', ['action' =>  Router::fullbaseUrl().'/Getjobnoncoreview/index/', $input['InputEntityId']]);
                            //$EdiT = "<a onclick=viewjob('$ModuleId','$InputEntId','$table',$ProjectId);  >Click to View</a>";
                            $Edit = '<button type="submit" class="input_submit"  onClick="placeOrder()">Place Order</button>';
                            //
                            //$EdiT = "';
                            //if (($SId == '1') || ($SId == '4')) {
                            //$url = trim($ModuleId).'/'.trim($InputEntId).'/'.trim($table);
                            //$EdiT = $this->Html->link('Click to View', ['controller' => 'ProductionViewUser', 'action' => 'view', $url]);
                            //  $EdiT = $this->Html->link('Click to View', ['controller' => 'ProductionViewUser', 'onclick' => 'viewjob('.$ModuleId.');']);
                            //}
                            //if (($SId == '12') || ($SId == '9')) {
                            //$EdiT = $this->Html->link('Click to View', ['controller' => 'Getjobhooview', 'action' => 'index', $input['InputEntityId']]);
                            //}
                            //if (($SId == '18') || ($SId == '15')) {
                            //  $EdiT = $this->Html->link('Click to View', ['controller' => 'Getjobnoncoreview', 'action' => 'index', $input['InputEntityId']]);
                            //}
                            ?>
                            <form target="_blank" method="post" action="ProductionViewUser" name="ProductionViewUser_<?php echo $i; ?>">
                                <input type="hidden" name="module" value="<?php echo $ModuleId; ?>">
                                <input type="hidden" name="inputenitityid" value="<?php echo $InputEntId; ?>">
                                <input type="hidden" name="table" value="<?php echo $table; ?>">
                                <input type="hidden" name="projectid" value="<?php echo $ProjectId; ?>">

                                <tr class="Row" style="overflow: hidden;">
                                    <td class="Cell"><?php echo $Projects[$input['ProjectId']]; ?></td>
                                    <td class="Cell"><?php echo $DomainId; ?></td>
                                    <td class="Cell"><?php echo $Resource; ?></td>
                                    <td class="Cell"><?php echo $UserGroupId; ?></td>
                                    <td class="Cell"><?php echo $Statusid[$input['StatusId']]; ?></td>
                                    <td class="Cell"><?php echo $input['ProductionStartDate']; ?></td>
                                    <td class="Cell"><?php echo $input['ProductionEndDate']; ?></td>
                                    <td class="Cell"><?php echo $input['TotalTimeTaken']; ?></td>
                                    <td class="Cell"><input type="submit" class="input_submit" name="submit_view" value="view" ></td>
                                </tr></form>

                            <?php
                            // echo $this->Html->tableCells(array(
                            //array(
                            //array($Projects[$input['ProjectId']], array('class' => 'Cell')),
                            //array($DomainId, array('class' => 'Cell')),
                            //array($Resource, array('class' => 'Cell')),
                            //array($UserGroupId, array('class' => 'Cell')),
                            //array($Statusid[$input['StatusId']], array('class' => 'Cell')),
                            //array($input['ProductionStartDate'], array('class' => 'Cell')),
                            //array($input['ProductionEndDate'], array('class' => 'Cell')),
                            //array($input['TotalTimeTaken'], array('class' => 'Cell')),
                            //array($EdiT, array('class' => 'Cell'))
                            //)
                            //), array('class' => 'Row', 'style' => 'overflow: hidden;'), array('class' => 'Row1', 'style' => 'overflow: hidden;'));
                        }
                        ?>

        <?php
        $i++;
    endforeach;
    ?>

                </table>
    <?php
}
?>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function (projectId) {
        var id = $('#RegionId').val();

        // alert(id);
        if ($('#ProjectId').val() != '') {
            getRegion();
            var e = document.getElementById("RegionId");
            var strUser = e.options[e.selectedIndex].text;

            //alert(strUser);
        }


        //$(".form-control,.form-controlVEF").prop("disabled", "disabled");
    });
    function viewjob(moduleid, inputentid, tablename, projectid) {
        var action = 'view'
        var url = action + '/' + moduleid + '/' + inputentid + '/' + tablename + '/' + projectid;
        var currenturl = document.URL;
//window.location.href=currenturl+'/'+url;
        var urlopen = currenturl + '/' + url;
        window.open(urlopen, '_blank');
//alert(url);
    }

    function getRegion(projectId) {

        var result = new Array();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Productionview', 'action' => 'ajaxregion')); ?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadRegion').innerHTML = result;
                //$('#UserGroupId').find('option').remove();
                $('#user_id').find('option').remove();
            }
        });
    }
    function getModule()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Productionview', 'action' => 'ajaxmodule')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadModule').innerHTML = result;
            }
        });
    }
    function getusergroupdetails(RegionId) {
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Productionview', 'action' => 'getusergroupdetails')); ?>",
            data: ({projectId: ProjectId, regionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadUserGroup').innerHTML = result;
                var optionValues = [];
                $('#UserGroupId option').each(function () {
                    optionValues.push($(this).val());
                });
                optionValues.join(',')
                $('#UserGroupId').prepend('<option selected value="' + optionValues + '">All</option>');
                getresourcedetails();

            }
        });
    }
    function getresourcedetails() {
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var UserGroupId = $('#UserGroupId').val();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Productionview', 'action' => 'getresourcedetails')); ?>",
            data: ({projectId: ProjectId, regionId: RegionId, userGroupId: UserGroupId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadUserDetails').innerHTML = result;
            }
        });
    }
    function getStatus()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var ModuleId = $('#ModuleId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Productionview', 'action' => 'ajaxstatus')); ?>",
            data: ({ProjectId: ProjectId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadStatus').innerHTML = result;
            }
        });
    }

    function ClearFields()
    {
        $('#ProjectId').val('0');
        $('#RegionId').val('0');
        $('#ModuleId').val('0');
        $('#batch_from').val('');
        $('#batch_to').val('');
        $('#UserGroupId').val('');
        $('#status').val('');
        $('#query').val('');
        $('#detail').hide();
        $('#pagination').hide();
        $('#xlscnt').hide();
        $('#status').find('option').remove();
        $('#user_id').find('option').remove();
    }

    function Mandatory()
    {
        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#RegionId').val() == 0) {
            alert('Select Region Name');
            $('#RegionId').focus();
            return false;
        }
        if ($('#ModuleId').val() == 0) {
            alert('Select Module');
            $('#ModuleId').focus();
            return false;
        }

        if (($('#batch_from').val() == '') && ($('#batch_to').val() == '') && ($('#user').val() == null))
        {
            alert('Select any one date!');
            return false;
        }
    }

</script>
<?php
if (isset($this->request->data['check_submit']) || isset($this->request->data['downloadFile'])) {
    ?>
    <script>
        $(window).bind("load", function () {
            var optionValues = [];
            $('#UserGroupId option').each(function () {
                optionValues.push($(this).val());
            });
            optionValues.join(',')
            $('#UserGroupId').prepend('<option selected value="' + optionValues + '">All</option>');
            $("#UserGroupId option[value='<?php echo $postbatch_UserGroupId; ?>']").prop('selected', true);
            //getresourcedetails();
            //$("#UserGroupId option[value='<?php echo $this->request->data['user_id']; ?>']").prop('selected', true);
        });
    </script>
    <?php
}
if ($CallUserGroupFunctions == 'yes') {
    ?>
    <script>
        $(window).bind("load", function () {
            var regId = $('#RegionId').val();
            getModule();
            getusergroupdetails(regId);
        });
    </script>
    <?php
}
?>