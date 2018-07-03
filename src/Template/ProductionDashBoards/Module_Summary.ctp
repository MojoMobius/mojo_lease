<?php

use Cake\Routing\Router
?>

<div class="panel-group" id="accordion-db1" role="tablist" aria-multiselectable="true" style="margin-top:10px;">
    <div class="container-fluid">

        <div class="panel panel-default formcontent">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h3 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion-db1" href="#collapsedb1" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration:none;">
                        <i class="more-less glyphicon glyphicon-plus"></i>
                        Production DashBoard Module Summary
                    </a> 
                </h3>
            </div>
            <div id="collapsedb1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
              <?php echo $this->Form->create('', array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Project</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                     <?php
                    echo $this->Form->input('', array('options' => $Projects, 'id' => 'ProjectId', 'name' => 'ProjectId','style' => 'width:220px', 'class' => 'form-control', 'value' => $ProjectId, 'onchange' => 'getRegion(this.value);getStatus(this.value);'));
                    ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Region</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                            <div id="LoadRegion">
                    <?php
                    if ($RegionId == '') {
                        $Region = array(0 => '--Select--');
                        echo $this->Form->input('', array('options' => $Region, 'id' => 'RegionId', 'name' => 'RegionId','style' => 'width:220px', 'class' => 'form-control', 'value' => $RegionId, 'onchange' => 'getusergroupdetails(this.value)'));
                    } else {
                        echo $RegionId;
                    }
                    ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">User Group</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                            <div id="LoadUserGroup">
                     <?php
                    if ($UserGroupId == '') {
                        $UserGroup = array(0 => '--Select--');
                        echo $this->Form->input('', array('options' => $UserGroup, 'id' => 'UserGroupId', 'name' => 'UserGroupId', 'class' => 'form-control', 'value' => $UserGroupId, 'selected' => $UserGroupId, 'onchange' => 'getresourcedetails'));
                    } else {
                        echo $UserGroupId;
                    }
                    ?>
                            </div>      
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">From</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                   <?php
                    echo $this->Form->input('', array('id' => 'batch_from', 'name' => 'batch_from','style' => 'width:220px', 'class' => 'form-control', 'value' => $postbatch_from));
                    ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">To</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                  <?php
                    echo $this->Form->input('', array('id' => 'batch_to', 'name' => 'batch_to', 'style' => 'width:220px','class' => 'form-control', 'value' => $postbatch_to));
                    ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Search</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                  <?php
            echo $this->Form->input('', array('id' => 'query', 'placeholder' => 'Search', 'name' => 'query', 'class' => 'form-control', 'value' => $postquery));
            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Resource</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                            <div id="LoadUserDetails">
                    <?php
                    echo $this->Form->input('', array('options' => $User, 'default' => '0', 'class' => 'form-control', 'selected' => $postuser_id, 'value' => $postuser_id, 'id' => 'user_id', 'name' => 'user_id', 'style' => 'height:120px; width:220px;', 'multiple' => true));
                    ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Status</label>
                        <div class="col-sm-6" style="line-height: 0px;">
                            <div id="LoadStatus">
                       <?php echo $this->Form->input('', array('options' => $Status, 'selected' => $poststatus, 'value' => $poststatus, 'id' => 'status', 'name' => 'status', 'style' => 'height:120px; width:220px;', 'multiple' => true, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--        <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">Status</label>
                        <div class="col-sm-6">
                    <?php
        //echo $this->Form->input('', array('options' => $Status, 'default' => '0', 'class' => 'form-control', 'selected' => $poststatus, 'value' => $poststatus, 'id' => 'status', 'name' => 'status', 'style' => 'height:100px', 'multiple' => true));
                    ?>
                        </div>
                    </div>
                        </div>-->


                <div class="form-group" style="text-align:center;">
                    <div class="col-sm-12">
            <?php
            echo $this->Form->submit('Job Status', array('id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Job Status', 'style' => 'margin-left:250px;width:100px;float:left;padding-bottom:2 px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()'));
            
            echo $this->Form->submit('Productivity Report', array('id' => 'productivityReport_submit', 'name' => 'productivityReport_submit', 'value' => 'Job Status', 'style' => 'margin-left:10px;width:150px;float:left;padding-bottom:2 px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()'));
            
            echo $this->Form->submit('Module Summary', array('id' => 'ModuleSummary_submit', 'name' => 'ModuleSummary_submit', 'value' => 'Job Status', 'style' => 'margin-left:10px;width:120px;float:left;padding-bottom:2 px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()'));
            
            echo $this->Form->button('Load', array('id' => 'load_data', 'name' => 'load_data', 'value' => 'Load', 'style' => 'margin-left:10px;float:left;display:inline;padding-bottom:6px;', 'class' => 'btn btn-primary btn-sm', 'type' => 'submit'));

            echo $this->Form->button('Clear', array('id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear', 'style' => 'margin-left:10px;float:left;display:inline;padding-bottom:6px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return ClearFields()', 'type' => 'button'));
            
            
            echo $this->Form->submit('Export Module Summary Results', array('id' => 'ModuleSummary_downloadFile', 'name' => 'ModuleSummary_downloadFile', 'value' => 'ModuleSummary_downloadFile', 'style' => 'margin-left:35px;float:left;display:inline;padding-bottom:6px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()', 'type' => 'submit'));
              ?>
                        <span id='xlscnt' style="float:right; margin-right:20px; margin-top:27px;display:none;">
                            <button type='submit' name='ModuleSummary_downloadFile' id='ModuleSummary_downloadFile' value='ModuleSummary_downloadFile'><img width="20" height="20" src="img/file-xls.jpg"><img width="12" height="12" src="img/down_arrow.gif"></button>
                        </span>

                <?php 
                
            // echo $this->Form->button('Reload', array( 'id' => 'Reload', 'name' => 'Reload', 'value' => 'Reload','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:6px;','class'=>'btn btn-warning','type'=>'submit'));

            if (count($Production_dashboard) > 0) {
                echo '<br>';
                echo '<br>';
                echo '<br>';
                ?>          
                    </div>    

                </div>   


            <?php
        }
        echo $this->Form->end();
        ?>
            </div>    
        </div>
    </div>
</div>  


<?php
//echo $error; 
//pr($Production_dashboard);

if (count($Production_dashboard) > 0) {
    $UserGroupIdList = explode(',', $postbatch_UserGroupId);
    ?>


<div class="container-fluid">
    <div class="bs-example">
        <div id="vertical">
            <div id="top-pane">
                <div id="horizontal" style="height: 100%; width: 100%;">
                    <div id="left-pane"  class="pa-lef-10">
                        <div class="pane-content" >
                            <input type="hidden" name="UpdateId" id="UpdateId">
                            <button style="float:right; height:18px; visibility: hidden; margin-right:15px;" type='hidden' name='downloadFile' id='downloadFile' value='downloadFile'></button>
                            <table style='width:100%;' class="table table-striped table-center" id='example'>
                                <thead>
                                    <tr class="Heading">
                                        <th class="Cell" width="20%">Module</th> 
                                        <th class="Cell" width="20%">Count</th> 
                                            <?php
                                                    foreach ($UserGroupIdList as $key => $val){
                                            ?>
                                        <th class="Cell" width="20%"><?php echo $UGNamedetails[$val]; ?></th>
                                            <?php
                                                    }
                                            ?>
                                    </tr>
                                </thead>
                                <tbody><?php
                                        $i = 0;
                                        foreach ($Production_dashboard as $inputVal => $input):
                                            ?>
                                    <tr>
                                        <td><?php if($input['IsModuleName'] == 'yes') echo "<b>"; echo $input['Module']; if($input['IsModuleName'] == 'yes') echo "</b>"; ?></td>
                                        <td><?php if($input['IsModuleName'] == 'yes') echo "<b>"; echo $input['Count']; if($input['IsModuleName'] == 'yes') echo "</b>"; ?></td>
                                                <?php
                                                foreach ($UserGroupIdList as $key=>$val){
                                                    ?>
                                        <td><?php if($input['IsModuleName'] == 'yes') echo "<b>"; echo $input[$val]; if($input['IsModuleName'] == 'yes') echo "</b>"; ?></td>	
                                                    <?php
                                                }
                                                ?>
                                    </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                </tbody>

                            </table>

                                <?php
                            }

                            echo $this->Form->end();
                            ?>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>



<div id="fade" class="black_overlay"></div>
<?php
//pr($productionjobNew);
echo $this->Form->end();
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#vertical").kendoSplitter({
            orientation: "vertical",
            panes: [
                {collapsible: false},
                {collapsible: false, size: "100px"},
                {collapsible: false, resizable: false, size: "100px"}
            ]
        });

        $("#horizontal").kendoSplitter({
            panes: [
                {collapsible: true},
                {collapsible: false},
                {collapsible: true}
            ]
        });
    });
</script>
<style>
    #vertical {
        height: 750px;
        margin: 0 auto;
    }
    #left-pane,#top-pane  {background-color: rgba(60, 70, 80, 0.05); }
    #left-pane{padding-top:12px !important};
    .pane-content {
        padding: 0 10px;
    }
    .lastrow label{position:relative !important;}
</style>
<script type="text/javascript">
    $(document).ready(function (projectId) {
<?php
$js_array = json_encode($ProdDB_PageLimit);

echo "var mandatoryArr = " . $js_array . ";\n";
?>
        var pageCount = mandatoryArr;
        $('#example').DataTable({
            "bPaginate": false,
            "bInfo": false,
            "bFilter": false,
            "pageLength": mandatoryArr,
            "ordering": false
        });

        var id = $('#RegionId').val();

//        if ($('#ProjectId').val() != '') {
//            getRegion();
//            var e = document.getElementById("RegionId");
//            var strUser = e.options[e.selectedIndex].text;
//
//        }

    });


    function getRegion(projectId) {

        var result = new Array();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionDashboards', 'action' => 'ajaxregion')); ?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadRegion').innerHTML = result;
                //$('#UserGroupId').find('option').remove();
                $('#user_id').find('option').remove();
                // document.getElementById('RegionId').value = result;
            }
        });
    }

    function getusergroupdetails(RegionId) {
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionDashboards', 'action' => 'getusergroupdetails')); ?>",
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
            url: "<?php echo Router::url(array('controller' => 'ProductionDashboards', 'action' => 'getresourcedetails')); ?>",
            data: ({projectId: ProjectId, regionId: RegionId, userGroupId: UserGroupId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadUserDetails').innerHTML = result;
            }
        });
    }

    function getStatus(projectId) {
        var result = new Array();
        //        if(ProjectId=projectId){
        //         vesselName=$("#RegionId option:selected").text();
        //         
        //         alert(vesselName);
        //         
        //        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionDashBoards', 'action' => 'ajaxstatus')); ?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadStatus').innerHTML = result;

                // document.getElementById('RegionId').value = result;
            }
        });
    }

    function ClearFields()
    {
        $('#ProjectId').val('');
        $('#RegionId').val('');
        $('#batch_from').val('');
        $('#batch_to').val('');
        $('#user_id').val('');
        $('#UserGroupId').val('');
        $('#status').val('');
        $('#query').val('');
        $('#detail').hide();
        $('#pagination').hide();
        $('#xlscnt').hide();
    }

    function reallocate(domain, status)
    {
        //alert(status);
        document.getElementById('LoadResult').innerHTML = "";
        document.getElementById('inputentityids').value = domain;
        document.getElementById('moduleids').value = status;
        var id = $('#DomainId').text(domain);
        var status = $('#AssignedTo').val(status);
        reallocate_user = new Array();
        reallocate_user = reallocate_user_list.split(',');
        reallocate_user_id = new Array();
        reallocate_user_id = user_id.split(',');
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
        $('#DomainId').text(domain);
        $('#statusVal').text(status);
        if (AssignedTo == '')
            AssignedTo = '-';
        $('#AssignedTo').text(domain);
        $('#production_id').val(production_id);
        $('#AssignedTohidden').val(AssignedToHidden);
        $('#att').val(att);
        $('#allocateTo option[value!="0"]').remove();
        if (count > 0) {
            for (i = 0; i < count; i++)
            {
                $('#allocateTo')
                        .append($("<option></option>")
                                .attr("value", reallocate_user_id[i])
                                .text(reallocate_user[i]));
            }

        }
    }
    function reallocateClose()
    {
        //alert('test');
        if ($('#allocateTo').val() == 0)
        {
            alert('Select Allocate TO');
            $('#allocateTo').focus();
            return false;
        }
        if ($('#allocateTo').val() == $('#AssignedTohidden').val())
        {
            alert('Alerady Assigned to this user only!');
            $('#allocateTo').focus();
            return false;
        }
//        document.getElementById('light').style.display = 'none';
//        document.getElementById('fade').style.display = 'none';

        var domain = document.getElementById('inputentityids').value;
        var status = document.getElementById('moduleids').value;
        var userid = document.getElementById('allocateTo').value;

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionDashboards', 'action' => 'ajaxupdateuser')); ?>",
            data: ({InputEntityId: domain, moduleid: status, userid: userid}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadResult').innerHTML = result;
                //document.getElementById('LoadRegion').innerHTML = result;

                // document.getElementById('RegionId').value = result;
            }
        });
    }
    function reallocateCancel()
    {
        radioId = document.getElementById('att').value;
        document.getElementById('radioReallocate' + radioId).checked = false;
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    }

    function Mandatory()
    {
        if ($('#query').val() == '') {
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

            if (($('#batch_from').val() == '') && ($('#batch_to').val() == '') && ($('#user').val() == null))
            {
                alert('Select any one date!');
                $('#batch_from').focus();
                return false;
            }
        }
    }

</script>
<style>
    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 500ms;
        visibility: hidden;
        opacity: 0;
    }
    .overlay:target {
        visibility: visible;
        opacity: 1;
    }


    .popup {
        margin: 150px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        width: 40%;
        position: relative;
        transition: all 5s ease-in-out;

    }

    .popup h2 {
        margin-top: 0;
        color: #333;
        font-family: Tahoma, Arial, sans-serif;
    }
    .popup .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }
    .popup .close:hover {
        color: #fdc382;
    }
    .popup .content {
        max-height: 30%;
        overflow: auto;
    }
    .query_outerbdr {
        background: #fff none repeat scroll 0 0;
        border-radius: 5px;
        margin: 3px;
        padding: 6px;
    }

    .allocation_popuphgt {
        font-size: 12px;
        height: 157px;
        overflow: auto;
    }
    .white_content {
        background: #fdfdfd url("../img/popupbg.png") repeat-x scroll left top;
        border: 5px solid #fff;
        display: none;
        height: auto;
        left: 25%;
        padding: 16px;
        position: absolute;
        top: 25%;
        width: 50%;
        z-index: 1002;
    }
    #example_paginate { display:none; }
</style>
<?php
if (isset($this->request->data['ModuleSummary_submit']) || isset($this->request->data['ModuleSummary_downloadFile'])) {
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
?>