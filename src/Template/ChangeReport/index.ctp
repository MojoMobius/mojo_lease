<?php

use Cake\Routing\Router
?>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>Change Report</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">From</label>
                <div class="col-sm-6 prodash-txt">
                 <?php echo $this->Form->input('', array( 'id' => 'batch_from', 'name' => 'batch_from', 'class'=>'form-control','value'=>$postbatch_from)); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">To</label>
                <div class="col-sm-6 prodash-txt">
                 <?php  echo $this->Form->input('', array( 'id' => 'batch_to', 'name' => 'batch_to', 'class'=>'form-control','value'=>$postbatch_to)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6 prodash-txt">
                    <?php 
                     echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'class'=>'form-control','value'=>$ProjectId, 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); 
                        ?>  
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Region Name</label>
                <div class="col-sm-6">
                  <?php 
                   if ($RegionId == '') {
                  $Region=array(0=>'--Select--');
                    echo '<div id="LoadRegion">';
                    echo $this->Form->input('', array('options' => $Region,'id' => 'RegionId', 'name' => 'RegionId', 'class'=>'form-control','value'=>$RegionId,'onchange'=>'getModule(this.value);getFiles(this.value);getusergroupdetails(this.value);')); 
                    echo '</div>';
                     } else {
                        echo $RegionId;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
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
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Resource</label>
                <div class="col-sm-6 prodash-txt">
                    <div id="LoadUserDetails">
                        <?php
                        echo $this->Form->input('', array( 'default' => '0', 'options' => $User, 'class' => 'form-control', 'selected' => $postuser_id, 'value' => $postuser_id, 'id' => 'user_id', 'name' => 'user_id', 'style' => 'width:200px; margin-top:0px;', 'multiple' => true));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    <br><br>
        <div class="form-group">
            <div class="col-md-12" style="text-align: center;">
                <?php
                    echo $this->Form->submit('Export', array( 'id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Search','style'=>'margin-left:550px;width:70px;float:left;padding-bottom:2 px;','class'=>'btn btn-primary btn-sm','onclick'=>'return Mandatory()'));
                  echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','style'=>'margin-left:10px;float:left;display:inline;padding-bottom:6px;','class'=>'btn btn-primary btn-sm','onclick'=>'return ClearFields()','type'=>'button'));
                ?>
            </div>
        </div>
        <?php
         echo $this->Form->end();
        ?>
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
        });
        function getRegion(projectId) {
            var result = new Array();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'ChangeReport','action'=>'ajaxregion'));?>",
                data: ({projectId: projectId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadRegion').innerHTML = result;
                    
                    // document.getElementById('RegionId').value = result;
                }
            });
        }
        
        function getusergroupdetails(RegionId) {
            var ProjectId = $('#ProjectId').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller' => 'UrlStatusReport', 'action' => 'getusergroupdetails')); ?>",
                data: ({projectId: ProjectId,regionId: RegionId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadUserGroup').innerHTML = result;
                    var optionValues = [];
                    $('#UserGroupId option').each(function() {
                        optionValues.push($(this).val());
                    });
                    optionValues.join(',')
                    $('#UserGroupId').prepend('<option selected value="'+optionValues+'">All</option>');
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
                url: "<?php echo Router::url(array('controller' => 'UrlStatusReport', 'action' => 'getresourcedetails')); ?>",
                data: ({projectId: ProjectId,regionId: RegionId,userGroupId: UserGroupId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadUserDetails').innerHTML = result;
                }
            });
        }

        function ClearFields()
        {
            $('#ProjectId').val(0);
            $('#RegionId').val(0);
            $('#batch_from').val('');
            $('#batch_to').val('');
            $('#user_id').val('0');
            $('#status').val('0');
            
        }

        function Mandatory()
        {
            if (($('#batch_from').val() == '') && ($('#batch_to').val() == '') && ($('#user').val() == null))
            {
                alert('Select any one date!');
                return false;
            }
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
            if ($('#UserGroupId').val() == 0) {
                alert('Select Region Name');
                $('#RegionId').focus();
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
                $('#UserGroupId option').each(function() {
                    optionValues.push($(this).val());
                });
                optionValues.join(',')
                $('#UserGroupId').prepend('<option selected value="'+optionValues+'">All</option>');
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