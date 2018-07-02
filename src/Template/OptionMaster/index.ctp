<!--Form : Production Field Mapping
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>
<script type="text/javascript">


    $(function () {
        var select_valRegion = $('#RegionId option:selected').val();
        document.getElementById('Region').value = select_valRegion;

        var select_valModule = $('#ModuleId option:selected').val();
        document.getElementById('Module').value = select_valModule;

        var select_valAttribute = $('#AttributeId option:selected').val();
        document.getElementById('attribute').value = select_valAttribute;
    });


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
        if ($('#AttributeId').val() == 0)
        {
            alert('Select Attribute List');
            $('#AttributeId').focus();
            return false;
        }
        var fileName = $('#file').val();
        if (fileName != '') {
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

        var counter = $('#AddOptionTable tbody tr').length;

        if (fileName == '') {
            for (i = 1; i <= counter; i++)
            {
                if ($.trim($('#attributeOption_' + i).val()) == '')
                {
                    alert('Enter Attribute Option in Row - ' + i);
                    $('#attributeOption_' + i).focus();
                    return false;
                }
                if ($.trim($('#displayOrder_' + i).val()) == '')
                {
                    alert('Enter Dispaly Order in Row - ' + i);
                    $('#displayOrder_' + i).focus();
                    return false;
                }

                for (j = 1; j <= counter; j++)
                {
                    if (i != j)
                    {
                        if ($('#attributeOption_' + i).val() == $('#attributeOption_' + j).val())
                        {
                            alert("Attribute Option Entered in Row " + i + " matched with Row " + j);
                            $('#attributeOption_' + j).focus();
                            return false;
                        }
                        if ($('#displayOrder_' + i).val() == $('#displayOrder_' + j).val())
                        {
                            alert("Display order Entered in Row " + i + " matched with Row " + j);
                            $('#displayOrder_' + j).focus();
                            return false;
                        }
                    }
                }
            }
        }

    }

    function getRegion(ProjectId) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmaster', 'action' => 'ajaxregion')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
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
            url: "<?php echo Router::url(array('controller' => 'Optionmaster', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadAttributeids').innerHTML = result;
            }
        });
    }
    function getModule()
    {
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Optionmaster', 'action' => 'ajaxmodule')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadModule').innerHTML = result;
            }
        });
    }

    function AddRow() {
        if ($('#ProjectId').val() == 0) {
            alert('Please Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#RegionId').val() == 0) {
            alert('Please Select Region Name');
            $('#RegionId').focus();
            return false;
        }
        if ($('#ModuleId').val() == 0) {
            alert('Please Select Module List');
            $('#ModuleId').focus();
            return false;
        }
        if ($('#AttributeId').val() == 0) {
            alert('Please Select Attribute List');
            $('#AttributeId').focus();
            return false;
        }
        var count = $('#AddOptionTable  tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="non-bor"><input type="text" name="attributeOption[]" id="attributeOption_' + count + '" class="form-control"></td><td class="non-bor"><input type="text" name="displayOrder[]" id="displayOrder_' + count + '" onblur="NumbersOnly(this.id,this.value)" class="form-control"></td>';
        cols += '<td class="non-bor"><img src="<?php echo Router::url('/', true); ?>webroot/img/images/delete.png" onclick="RemoveRow(' + count + ');"></td>';

        newRow.append(cols);
        $("#AddOptionTable").append(newRow);
    }

    function RemoveRow(r) {
        var counter = $('#AddOptionTable tbody tr').length;
        if (counter > 1) {
            $("#AddOptionTable tbody tr:nth-child(" + r + ")").remove();
            var table = document.getElementById('AddOptionTable');

            for (var r = 1, n = table.rows.length; r < n; r++) {

                for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {

                    if (c == 0)
                    {
                        var nodes = table.rows[r].cells[c].childNodes;

                        for (var i = 0; i < nodes.length; i++) {
                            if (nodes[i].nodeName.toLowerCase() == 'input')
                                nodes[i].id = 'attributeOption_' + r;
                        }
                    }
                    if (c == 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {

                            if (nodes[i].nodeName.toLowerCase() == 'input')
                                nodes[i].id = 'displayOrder_' + r;

                        }
                    }
                    if (c == 2 && r > 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {
                            nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");

                        }
                    }
                }
            }
        } else
        {
            alert('Minimum One Row Required');
        }
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

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Possible Values Configuration</h4>
        <?php echo $this->Form->create($OptionMaster, array('class' => 'form-horizontal', 'id' => 'projectforms', 'enctype' => 'multipart/form-data')); ?>
        <input type="hidden" name="ProjectId"  value= <?php echo $ProjValue; ?>> 
        <input type="hidden" name="RegionId" id="Region"> 
        <input type="hidden" name="ModuleId" id="Module"> 
        <input type="hidden" name="AttributeId" id="attribute"> 


        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6">
                    <?php echo $ProListopt; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Module</label>
                <div class="col-sm-6">
                    <?php $Module = array(0 => '--Select--'); ?>
                    <div id="LoadModule">
                        <?php
                        echo $ModuleList;
                        if ($ModuleList == '') {
                            ?>
                        <select class="form-control">
                            <option selected>--Select--</option>
                        </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">Attribute List</label>
                <div class="col-sm-6">
                    <?php $Attributeids = array(0 => '--Select--'); ?>
                    <div id="LoadAttributeids">
                        <?php
                        echo $AttributeList;
                        if ($AttributeList == '') {
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
                <div class="col-sm-6">
                    <table class="table list-master" style="width: 25%; margin-left: 5px;" id="AddOptionTable"><thead><tr>


                                <td  align="center">Attribute Value</td>
                                <td  align="center">Display Order</td>
                                <td ></td >
                            </tr>
                        </thead>
                        <tbody>

                    <?php for ($i = 0; $i < $assigned_details_cnt; $i++) { ?>

                            <tr>
                                <td class="non-bor"><input name="attributeOption[]" class="form-control" id="attributeOption_<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigned_details[$i]['DropDownValue'] ?>"></td>
                                <td class="non-bor"><input name="displayOrder[]" class="form-control" id="displayOrder_<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigned_details[$i]['OrderId'] ?>" onblur="NumbersOnly(this.id, this.value);"></td>
                            <?php if ($i == 0) { ?>
                                <td class="non-bor"><a><?php echo $this->Html->image("images/add.png", array('onclick' => 'AddRow();')); ?></a></td >
                            <?php } else { ?>
                                <td class="non-bor"><?php echo $this->Html->image("images/delete.png", array('onclick' => 'RemoveRow(' . ($i + 1) . ');')); ?></td >
                            <?php } ?>
                            </tr>
                    <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-5 control-label" style="margin-left:-86px;margin-top: 13px;">Choose File</label>
                <div class="col-sm-7">
                    <span><input type="file" name="file" id="file"  style="border:none; margin-top:11px;"></span>(Allowed Formats:.csv and .xlsx)<a href="webroot/auto.xlsx" style="font-size: 12px;"> Sample</a>
                </div>
            </div>
        </div>
     <!--     <div class="col-md-6"><span class="btn btn-default btn-file"><span> Choose File<input type="file" name="file" id="file" style="border:none;"></span>(Allowed Formats:.csv and .xlsx)<a href="webroot/auto.xlsx" style="font-size: 12px;"> Sample </a></span></div>
              <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-6 control-label">Upload</label>
                        <div class="col-sm-6">
                            <div class="input file">
                                <input type="file" name="suggestionvalues" value="" id="suggestionvalues" class="col-sm-6 control-label"/>
                                <input type="file" name="file" id="file"  class="col-sm-6 control-label" onchange="validate_fileupload(this.value);">(Allowed Formats:.csv and .xlsx)<a href="auto.xlsx">Sample</a>
                            </div>
        
                        </div>
                    </div>
                </div>-->
        <!--        <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-7 control-label">Not in List</label>
                        <div class="col-sm-5">
        <?php
//if ($NotInList == 1) {
//    $checked = 'checked';
//} else {
//    $checked = '';
//}
//echo $this->Form->checkbox('Not in List', array('id' => 'NotInList', 'name' => 'NotInList', 'checked' => $checked, 'class' => 'form-control'));
        ?></div>
                    </div>
                </div>-->
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <!--                     <button type="cancel" class="btn btn-primary btn-sm pull-right">Cancel</button>
                                     <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>-->
                <?php //echo $this->Form->submit('Cancel', array('id' => 'cancel', 'name' => 'cancel', 'value' => 'Cancel', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return CancelForm()'));  ?>
                <?php //echo $this->Form->submit('Submit', array('id' => 'submit', 'name' => 'submit', 'value' => 'Submit', 'class' => 'btn btn-primary btn-sm pull-right', 'onclick' => 'return validateForm()'));
                ?>	
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Region</th>
                    <th>Module Name</th>
                    <th>Attribute Name</th>
                    <th>Attribute Value</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Option_Masters as $inputVal => $input) {
                    $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
                    $content = file_get_contents($path);
                    $contentArr = json_decode($content, true);
                    $RegionName = $contentArr['RegionList'][$input['RegionId']];
                    
                    if($input['RecordStatus'] == 1){
                    $url = urlencode($input['ProjectAttributeMasterId'] . '-' . $input['RegionId']);
                    $EdiT = $this->Html->link('edit', ['action' => 'index', $url]);
                    $delete=$this->Html->link('Delete', ['action' => 'delete', $url]);
                    ?>
                <tr>
                        <?php
                        echo '<td>' . $input['ProjectName'] . '</td>';
                        echo '<td>' . $RegionName . '</td>';
                        echo '<td>' . $input['ModuleName'] . '</td>';
                        echo '<td>' . $input['AttributeName'] . '</td>';
                        echo '<td>' . $input['DropDownValue'] . '</td>';
                        echo '<td>' . $EdiT . '</td>';
                        echo '<td>' . $delete . '</td>';
                        ?>    
                </tr>
                <?php }
                }
                ?>

            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
            <div class="col-sm-6">

            </div>
        </div>
    </div>
</div>