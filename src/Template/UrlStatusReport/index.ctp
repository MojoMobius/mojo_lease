<?php
    use Cake\Routing\Router;
?>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>UrlStatus Report</h4>
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
                   <?php echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'style'=>'width:150px;', 'class'=>'form-control','value'=>$ProjectId, 'onchange'=>'getRegion(this.value);getFiles(this.value);' )); ?>  
                </div>
            </div>
        </div>
         
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Region</label>
                <div class="col-sm-6 ">
                    <div id="LoadRegion" style ="width:150px;">
                    <?php if($RegionId==''){
                        $Region=array(0=>'--Select--');
                        echo '';
                        echo $this->Form->input('', array('options' => $Region,'id' => 'RegionId', 'name' => 'RegionId', 'class'=>'form-control','value'=>$RegionId,'onchange'=>'getModule(this.value);getFiles(this.value);getusergroupdetails(this.value);'));
                        echo '';
                    }else{
                        echo $RegionId;    
                    } ?>
                    </div>
                </div>
            </div>
        </div>  
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="UserGroupId" class="col-sm-6 control-label">User Group</label>
                <div class="col-sm-6 prodash-txt">
                    <div id="LoadUserGroup">
                    <?php
                    if ($UserGroupId == '') {
                        $UserGroup = array(0 => '--Select--');
                        echo '';
                        echo $this->Form->input('', array('options' => $UserGroup, 'id' => 'UserGroupId', 'name' => 'UserGroupId', 'class' => 'form-control', 'value' => $UserGroupId, 'selected' => $UserGroupId, 'onchange' => 'getresourcedetails'));
                        echo '';
                    } else {
                        echo $UserGroupId;
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Resource</label>
                <div class="col-sm-6 prodash-txt">
                    <div id="LoadUserDetails">
                        <?php
                        echo $this->Form->input('', array( 'options' => $User, 'class' => 'form-control', 'selected' => $postuser_id, 'value' => $postuser_id, 'id' => 'user_id', 'name' => 'user_id', 'style' => 'width:200px; margin-top:0px;', 'multiple' => true));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Status</label>
                <div class="col-sm-6 prodash-txt">
                     <?php  echo $this->Form->input('',array('options' => $Remarks, 'class'=>'form-control', 'style'=>'', 'selected'=>$post_Remarks, 'value'=>$post_Remarks, 'id' => 'Remarks', 'name' => 'Remarks')); ?>
                </div>
            </div>
        </div>
        
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <?php
                    echo $this->Form->button('Search', array( 'id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Search','class'=>'btn btn-primary btn-sm','onclick'=>'return Mandatory()'));
                    echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','style' => 'margin-left:10px','class'=>'btn btn-primary btn-sm','onclick'=>'return ClearFields()','type'=>'button'));
                    if(count($UrlStatusValue)>0){
                        echo '<br>';
                            echo '<br>';
                                echo '<br>';
                ?> 	 
            </div>
        </div>
        
        <div id='xlscnt'>

            <button type='submit' name='downloadFile' id='downloadFile' value='downloadFile'><img width="20" height="20" src="img/file-xls.jpg"><img width="12" height="12" src="img/down_arrow.gif"></button>
        </div>

	<?php
	}
         echo $this->Form->end();
        ?>
    </div>
</div>

   <?php    echo $error;

if(count($UrlStatusValue)>0){ ?>

<div class="bs-example">
    <div id='detail' class="bs-example">
        <table style='width:98%;' class='table table-striped table-center'>
            <input type="hidden" name="UpdateId" id="UpdateId">
           <?php echo $this->Html->tableHeaders(array('S.No','Project','Region','Production Start Date','Production End Date','Domain Id','Input Id','User Group','User Name','Domain Url','Remarks','Reason','UrlStatus'),array('class' => 'Heading'),array('class' => 'Cell','width'=>'10%'));?>
           <?php
            $i = 1;

          //pr($Remarks);
            
          foreach ($UrlStatusValue as $inputVal => $input):
        
            if($input['Remarks']=='NULL' || $input['Remarks']==''){
                $flag = "Valid";
            }else{
                $flag = "InValid";
            }
           
           echo $this->Html->tableCells(array(
                      array(
                            array($i,array('class' => 'Cell')),
                            array($Projects[$input['ProjectId']],array('class' => 'Cell')),
                            array($JsonArray['RegionList'][$input['RegionId']],array('class' => 'Cell')),  
                            array($input['ProductionStartDate'],array('class' => 'Cell')),
                            array($input['ProductionEndDate'],array('class' => 'Cell')),
                            //array($input['TotalTimeTaken'],array('class' => 'Cell')),
                            array($input[$DomainId],array('class' => 'Cell')),
                            array($input['InputId'],array('class' => 'Cell')),
                            array($UrlUserGroupValue[$input['UserId']],array('class' => 'Cell')),
                            array($JsonArray['UserList'][$input['UserId']],array('class' => 'Cell')),
                            array($input['DomainUrl'],array('class' => 'Cell')),
                            array($Remarks[$input['Remarks']],array('class' => 'Cell')),
                            array($input['Reason'],array('class' => 'Cell')),
                            array($flag,array('class' => 'Cell'))
                              )
                    ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                     $i++;
                endforeach;
                ?>

        </table>
      <?php
        }
    ?>
    </div>
</div>

    <script type="text/javascript">
        $(document).ready(function (projectId) {
            var id = $('#RegionId').val();

            if ($('#ProjectId').val() != '') {
                getRegion();
                var e = document.getElementById("RegionId");
                var strUser = e.options[e.selectedIndex].text;
            }
        });

        function getRegion(projectId) {
            var result = new Array();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'UrlStatusReport','action'=>'ajaxregion'));?>",
                data: ({projectId: projectId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadRegion').innerHTML = result;
                }
            });
        }
        
        function getModule()
        {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'UrlStatusReport', 'action' => 'ajaxmodule')); ?>",
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
            $('#ProjectId').val('0');
            $('#RegionId').val('0');
            $('#batch_from').val('');
            $('#batch_to').val('');
            $('#UserGroupId').val('');
            $('#status').val('0');
            $('#user_id').val('0');
            $('#detail').hide();
            $('#pagination').hide();
            $('#xlscnt').hide();
        }

        function Mandatory()
        {
//            alert($('#user_id').val());
            
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
            if ($('#user_id').val() ==  0) {
                alert('Select User');
                $('#user_id').focus();
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
            //getModule();
            getusergroupdetails(regId);
        });    
    </script>
    <?php
}
?>