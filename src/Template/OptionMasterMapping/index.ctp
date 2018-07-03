<!--Form : Dropdown Mapping
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>
<script type="text/javascript">
    $(function () {
        $("#file").change(function () {
          $("#fileuploads").show();
          $("#submitbtwn").hide();
          
        });
        
    });

    function validatefileForm()
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
        if ($('#ModuleId').val() == 0)
        {
            alert('Select Module Name');
            $('#ModuleId').focus();
            return false;
        }
        if ($('#LoadPrimaryAttributeids').val() == 0)
        {
            alert('Select Primary Attribute List');
            $('#LoadPrimaryAttributeids').focus();
            return false;
        }
        if ($('#LoadSecondaryAttributeids').val() == 0)
        {
            alert('Select Secondary Attribute List');
            $('#LoadSecondaryAttributeids').focus();
            return false;
        }

        if ($('#file').val() == '')
        {
            alert('Choose a file for Upload');
            $('#file').focus();
            return false;
        }


    }

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
        if ($('#ModuleId').val() == 0)
        {
            alert('Select Module Name');
            $('#ModuleId').focus();
            return false;
        }
        if ($('#LoadPrimaryAttributeids').val() == 0)
        {
            alert('Select Primary Attribute List');
            $('#LoadPrimaryAttributeids').focus();
            return false;
        }
        if ($('#LoadSecondaryAttributeids').val() == 0)
        {
            alert('Select Secondary Attribute List');
            $('#LoadSecondaryAttributeids').focus();
            return false;
        }
        var counter = $('#AddOptionMapTable tbody tr').length;
        for (i = 1; i <= counter; i++)
        {
            if ($.trim($('#childid_' + i).val()) == '')
            {
                alert('Select Dependency Attribute Option in Row  ' + i);
                $('#childid_' + i).focus();
                return false;
            }

//            for (j = 1; j < counter; j++)
//            {
//                if (i != j)
//                {
//                    if ($.trim($('#childid_' + i).val()) === $.trim($('#childid_' + j).val()))
//                    {
//                        alert("Dependency Attribute Option Entered in Row " + i + " matched with Row " + j);
//                        $('#childid_' + j).focus();
//                        return false;
//                    }
//                }
//            }
        }

    }

    function getRegion(ProjectId) {
        //alert(ProjectId);
        var result = new Array();
        document.getElementById('LoadPrimaryAttributeids').innerHTML = '';
        document.getElementById('LoadSecondaryAttributeids').innerHTML = '';
        var element1 = document.getElementById('ModuleId');
        if (typeof (element1) != 'undefined' && element1 != null)
        {
            document.getElementById('ModuleId').innerHTML = '';
        }
        var element2 = document.getElementById('Loadattribute');
        if (typeof (element2) != 'undefined' && element2 != null)
        {
            var loadval = '<tr><td>NULL</td><td>NULL</td></tr>';
            document.getElementById('Loadattribute').innerHTML = loadval;
            document.getElementById('optshow').style.display = 'none';
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmastermapping', 'action' => 'ajaxregion')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //alert(result);
                document.getElementById('LoadRegion').innerHTML = result;
            }
        });
    }
    function getAttributeids()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmastermapping', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadPrimaryAttributeids').innerHTML = result;
                document.getElementById('LoadSecondaryAttributeids').innerHTML = result;
            }
        });
    }
    function getModule()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmastermapping', 'action' => 'ajaxmodule')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadModule').innerHTML = result;
            }
        });
    }
    function getDependencyatt()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var ModuleId = $('#ModuleId').val();
        var PrimaryId = $('#LoadPrimaryAttributeids').val();
        var SecondaryId = $('#LoadSecondaryAttributeids').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmastermapping', 'action' => 'ajaxloadattribute')); ?>",
            data: ({PrimaryId: PrimaryId, SecondaryId: SecondaryId, ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result != '') {
                    document.getElementById('Loadattribute').innerHTML = result;
                    document.getElementById('optshow').style.display = '';
                } else {
                    var loadval = '<tr><td>NULL</td><td>NULL</td></tr>';
                    document.getElementById('Loadattribute').innerHTML = loadval;
                    document.getElementById('optshow').style.display = 'none';
                }
            }
        });
    }
    function getDependencyattmodule()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var ModuleId = $('#ModuleId').val();
        $('#LoadPrimaryAttributeids').prop('selectedIndex', 0);
        $('#LoadSecondaryAttributeids').prop('selectedIndex', 0);
        var PrimaryId = '0_0';
        var SecondaryId = '0_0';
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmastermapping', 'action' => 'ajaxloadattribute')); ?>",
            data: ({PrimaryId: PrimaryId, SecondaryId: SecondaryId, ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                if (result != '') {
                    document.getElementById('Loadattribute').innerHTML = result;
                    document.getElementById('optshow').style.display = '';
                } else {
                    var loadval = '<tr><td>NULL</td><td>NULL</td></tr>';
                    document.getElementById('Loadattribute').innerHTML = loadval;
                    document.getElementById('optshow').style.display = 'none';
                }
            }
        });
    }


</script>

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Attribute Dependency Definition</h4>
        <?php echo $this->Form->create($OptionMaster, array('class' => 'form-horizontal', 'id' => 'projectforms','enctype' => 'multipart/form-data')); ?>
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
                    <?php $Region = array(0 => '--Select--'); ?>
                    <div id="LoadRegion">
                        <?php
                        echo $RegList;
                        if ($RegList == '') {
                            ?>
                        <select class="form-control">
                            <option selected>--Select--</option>
                        </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Module</label>
                <div class="col-sm-6">
                    <?php $Module = array(0 => '--Select--'); ?>
                    <div id="LoadModule">
                        <?php
                        echo $ModuleList;
                        if ($ModuleList == '') {
                            ?>
                        <select class="form-control" id="module">
                            <option selected>--Select--</option>
                        </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Primary Attribute</label>
                <div class="col-sm-6">
                    <select class="form-control" name="PrimaryAttributeId" id="LoadPrimaryAttributeids" onchange="getDependencyatt()">

                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Secondary Attribute</label>
                <div class="col-sm-6">
                    <select class="form-control" name="SecondaryAttributeId" id="LoadSecondaryAttributeids" onchange="getDependencyatt();">

                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-5 control-label" style="margin-left:-86px;margin-top: 13px;">Choose File</label>
                <div class="col-sm-6">
                    <span><input type="file" name="file" id="file"  style="border:none; margin-top:11px;">
                    </span>
                    <br>(Allowed Formats:.csv and .xlsx)<a href="webroot/attributedependancy.xlsx" style="font-size: 12px;"> Sample</a>

                </div>
            </div>
        </div>

        <div class="form-group" style="text-align:center;display:none;" id="fileuploads">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validatefileForm()">Upload</button>
            </div>
        </div>


        <div id="optshow" class="bs-example" style="display: none;">
            <table class="table table-striped table-center" id="AddOptionMapTable">
                <thead>
                    <tr>
                        <th>Primary Attribute values</th>
                        <th>Dependency Attribute Mapping</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="Loadattribute">
                    <tr><td>NULL</td><td>NULL</td></tr>
                </tbody>
            </table>
        </div>


        <div id="submitbtwn" class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <!--                     <button type="cancel" class="btn btn-primary btn-sm pull-right">Cancel</button>
                                     <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>-->
                <?php //echo $this->Form->submit('Cancel', array('id' => 'cancel', 'name' => 'cancel', 'value' => 'Cancel', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return CancelForm()')); ?>
                <?php //echo $this->Form->submit('Submit', array('id' => 'submit', 'name' => 'submit', 'value' => 'Submit', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return validateForm()'));
                ?>	
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>


</div>