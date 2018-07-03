
<?php use Cake\Routing\Router; ?>

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
        if ($('#UserGroup').val() == 0)
        {
            alert('Select User Group');
            $('#UserGroup').focus();
            return false;
        }

        $('#OutputUser option').prop('selected', true);

        var exists = false;
        $('#OutputUser option').each(function () {
            exists = true;
            return false;
        });

        if (exists === false)
        {
            alert("Select atleast one item for Mapping");
            return false;
        }

    }

    function getRegion(ProjectId) {

        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'UserGroupMapping','action'=>'ajaxregion'));?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //alert(result);
                document.getElementById('LoadRegion').innerHTML = result;
            }
        });

        document.getElementById('LoadAttributeButton').style.display = 'inline-block';
    }

    function getUserList()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var UserGroup = $('#UserGroup').val();
        if ((ProjectId != 0) && (RegionId != 0) && (UserGroup != 0)) {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller'=>'UserGroupMapping','action'=>'ajaxuser')); ?>",
                data: ({ProjectId: ProjectId, RegionId: RegionId, UserGroup: UserGroup}),
                dataType: 'text',
                async: false,
                success: function (result) {
                    document.getElementById('LoadAttribute').innerHTML = result;
                }
            });
        }
    }

    function SelectMoveRows(SS1, SS2)
    {
        var SelID = '';
        var SelText = '';
        // Move rows from SS1 to SS2 from bottom to top
        for (i = SS1.options.length - 1; i >= 0; i--)
        {
            if (SS1.options[i].selected == true)
            {
                SelID = SS1.options[i].value;
                SelText = SS1.options[i].text;
                var newRow = new Option(SelText, SelID);
                SS2.options[SS2.length] = newRow;
                SS1.options[i] = null;
            }
        }
        // document.getElementById('LoadAttribute').style.display='block';

    }

</script>

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>User Group Mapping</h4>
            <?php 
    echo $this->Form->create($UserGroupMapping,array('name'=>'inputSearch' , 'class' => 'form-horizontal', 'id' => 'projectforms')); ?>

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
                      <?php $Region=array(0=>'--Select--'); ?>
                    <div id="LoadRegion">
                        <select class="form-control">
                            <option selected>Select</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">User Group</label>
                <div class="col-sm-6">
                    <?php echo $UserListopt; ?>
                </div>
            </div>
        </div>
        <div id="LoadAttribute"></div>

        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
            </div>
        </div>
            <?php echo $this->Form->end();  ?>
    </div>
</div>