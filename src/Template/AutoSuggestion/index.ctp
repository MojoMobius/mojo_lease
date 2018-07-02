<!--Form : Auto Suggestion Master
  Developer: Sivaraj K
  Created On: Nov 16 2016 -->
<?php

use Cake\Routing\Router; ?>
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
        if ($('#ModuleId').val() == 0)
        {
            alert('Select Module Name');
            $('#ModuleId').focus();
            return false;
        }
        var fileName = $('#file').val();
        //alert(fileName);
        if (fileName == '') {
            alert('Please upload file');
            return false;
        } else {

            var allowed_extensions = new Array("xlsx", "csv");
            var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

            for (var i = 0; i <= allowed_extensions.length; i++)
            {
                if (allowed_extensions[i] == file_extension)
                {
                    return true; // valid file extension
                } else {
                    alert('Please upload file in xlsx/csv format');
                    return false;
                }
            }
        }
        //}
//        $('#OutputAttribute option').prop('selected', true);
//
//        var exists = false;
//        $('#OutputAttribute option').each(function () {
//            exists = true;
//            return false;
//        });
//
//        if (exists === false)
//        {
//            alert("Select atleast one item for Mapping");
//            return false;
//        }

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
//document.getElementById('LoadAttributeButton').style.display='inline-block';

    }

    function SelectMoveUp(move)
    {
        //alert(move);
        var $op = $('#OutputAttribute option:selected');
        var upval = move;
        if ($op.length) {
            (upval == '1') ?
                    $op.first().prev().before($op) :
                    $op.last().next().after($op);
        }
    }

    function getRegion(ProjectId) {
        //alert(ProjectId);
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Autosuggestion', 'action' => 'ajaxregion')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //alert(result);
                document.getElementById('LoadRegion').innerHTML = result;
            }
        });
    }
//    function getModule()
//    {
//        var result = new Array();
//        var ProjectId=$('#ProjectId').val();
//        $.ajax({
//        type:"POST",
//        url:"<?php echo Router::url(array('controller' => 'Autosuggestion', 'action' => 'ajaxmodule')); ?>",
//        data:({ProjectId:ProjectId}),
//        dataType: 'text',
//                async:false,
//                success: function(result){
//                   document.getElementById('LoadModule').innerHTML = result; 
//                }
//        });
//    }
    function getAttributes()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Autosuggestion', 'action' => 'ajaxattribute')); ?>",
            //data:({ProjectId:ProjectId,RegionId:RegionId,ModuleId:ModuleId}),
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadAttribute').innerHTML = result;
            }
        });
        //document.getElementById('LoadAttributeButton').style.display='inline-block';
    }
    function validate_fileupload(fileName)
    {
        var allowed_extensions = new Array("xlsx", "csv");
        var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

        for (var i = 0; i <= allowed_extensions.length; i++)
        {
            if (allowed_extensions[i] == file_extension)
            {
                return true; // valid file extension
            }
        }
        alert('Please upload file in xlsx/csv format');
        return false;
    }


</script>
<div class="container-fluid mt-15">
    <div class="jumbotron formcontent">
        <h4>Autosuggest Configuration</h4>
        <?php echo $this->Form->create($AutoSuggestion, array('name' => 'inputSearch', 'class' => 'form-horizontal', 'id' => 'projectforms', 'enctype' => 'multipart/form-data')); ?>

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
                        <select class="form-control">
                            <option selected>--Select--</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Attributes</label>
                <div class="col-sm-6">
                    <?php $Attributes = array(0 => '--Select--'); ?>
                    <div id="LoadAttribute">
                        <select class="form-control">
                            <option selected>--Select--</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label" style="line-height: 37px;">Choose File</label>
                <div class="col-sm-6">
                    <span><input type="file" name="file" id="file"  style="border:none;"></span>(Allowed Formats:.csv and .xlsx)<a href="webroot/auto.xlsx" style="font-size: 12px;"> Sample </a>
                </div>
            </div>
        </div>
      <!--   <div class="col-md-4"><span class="btn btn-default btn-file"><span> Choose File<input type="file" name="file" id="file"  style="border:none;"></span>(Allowed Formats:.csv and .xlsx)<a href="webroot/auto.xlsx" style="font-size: 12px;"> Sample </a></span></div>
       <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Upload</label>
                <div class="col-sm-6">
                    <div class="input file">
                        <input type="file" name="suggestionvalues" value="" id="suggestionvalues" class="col-sm-6 control-label"/>
                        <input type="file" name="file" id="file" multiple class="col-sm-6 control-label">
                        <input type="file" name="file" id="file"  class="col-sm-6 control-label" onchange="validate_fileupload(this.value);">(Allowed Formats:.csv and .xlsx)<a href="auto.xlsx">Sample</a>
                    </div>

                </div>
            </div>
        </div>-->

        <!--                <div id="LoadAttribute"></div>-->

        <div class="col-md-12">
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <?php
                //echo $this->Form->button('Clear', array( 'id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear','class'=>'btn btn-primary btn-sm pull-right','onclick'=>'return ClearFields()','type'=>'button'));   
                //echo $this->Form->submit('submit', array('id' => 'submit', 'class' => 'btn btn-primary btn-sm pull-right', 'name' => 'submit', 'value' => 'Submit', 'onclick' => 'return validateForm()'));
                ?>
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

