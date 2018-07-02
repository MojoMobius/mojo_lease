<!--Form : Production Field Mapping
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
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
        var counter = $('#AddUniqueTable tbody tr').length;
        for (i = 1; i <= counter; i++)
        {
            if ($.trim($('#attributename' + i).val()) == '')
            {
                alert('Enter Attribute Name in Row - ' + i);
                $('#attributename' + i).focus();
                return false;
            }
            if ($.trim($('#AttributeMasterId' + i).val()) == '')
            {
                alert('Enter Attribute Master ID in Row - ' + i);
                $('#AttributeMasterId' + i).focus();
                return false;
            }
            if ($.trim($('#ProjectAttributeMasterId' + i).val()) == '')
            {
                alert('Enter Project Attribute ID in Row - ' + i);
                $('#ProjectAttributeMasterId' + i).focus();
                return false;
            }

            for (j = 1; j <= counter; j++)
            {
                if (i != j)
                {
                    if ($('#attributename' + i).val() == $('#attributename' + j).val())
                    {
                        alert("Attribute name Entered in Row " + i + " matched with Row " + j);
                        $('#attributename' + j).focus();
                        return false;
                    }
                    if ($('#AttributeMasterId' + i).val() == $('#AttributeMasterId' + j).val())
                    {
                        alert("Attribute Master Id Entered in Row " + i + " matched with Row " + j);
                        $('#AttributeMasterId' + j).focus();
                        return false;
                    }
                    if ($('#ProjectAttributeMasterId' + i).val() == $('#ProjectAttributeMasterId' + j).val())
                    {
                        alert("Project Attribute Id Entered in Row " + i + " matched with Row " + j);
                        $('#ProjectAttributeMasterId' + j).focus();
                        return false;
                    }
                }
            }
        }

    }

    function getRegion(ProjectId) {
        //alert(ProjectId);
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Uniqueidfields', 'action' => 'ajaxregion')); ?>",
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
        var cols ="";
        cols +='<select class="form-control" name="attributename[]" id="attributename1" style="width:141px;" onchange="getids(this.value,1);">';
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Uniqueidfields', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //document.getElementById('LoadAttributeids').innerHTML = result;
                cols += result;
                cols += '</select>';
                document.getElementById('LoadAttributeids').innerHTML = cols;
            }
        });
    }
    function getids(ids,val) {
    var res = ids.split("_");
    document.getElementById("AttributeMasterId"+val).value = res[1];
    document.getElementById("ProjectAttributeMasterId"+val).value = res[0];
    }

    function AddRow() {
        if ($('#ProjectId').val() == 0) {
            alert('Please Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        if ($('#RegionId').val() == 0)
        {
            alert('Please Select Region Name');
            $('#RegionId').focus();
            return false;
        }
        
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var count = $('#AddUniqueTable tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="non-bor">';
        cols +='<select class="form-control" name="attributename[]" id="attributename' + count + '" style="width:141px;" onchange="getids(this.value,'+count+');">';
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Uniqueidfields', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                cols += result;
                //document.getElementById('LoadAttributeids').innerHTML = result;
            }
        });

        
        
        cols += '</select>';
        cols += '</td>';
        cols += '<td class="non-bor"><input type="text" readonly="readonly" name="AttributeMasterId[]" onkeypress="return ValidateNumber(event);" id="AttributeMasterId' + count + '" class="form-control"></td>';
        cols += '<td class="non-bor"><input type="text" readonly="readonly" name="ProjectAttributeMasterId[]" onkeypress="return ValidateNumber(event);" id="ProjectAttributeMasterId' + count + '" class="form-control"></td>';
        //cols +='&nbsp;';
        //cols +='&nbsp;';
        //cols +='<input type="button" style="margin-top:-5px;" name="remove"  class="btnpdf btn-default btn-sm remove_symbol" onclick="RemoveRow('+count+');"></td>';
        cols += '<td class="non-bor"><img style="cursor: pointer;" src="<?php echo Router::url('/', true);?>webroot/img/images/delete.png" onclick="RemoveRow(' + count + ');"></td>';

        newRow.append(cols);
        $("#AddUniqueTable").append(newRow);
    }

    function RemoveRow(r) {
        var counter = $('#AddUniqueTable tbody tr').length;
        //var r = count;
        if (counter > 1) {
            $("#AddUniqueTable tbody tr:nth-child(" + r + ")").remove();
            var table = document.getElementById('AddUniqueTable');

            for (var r = 1, n = table.rows.length; r < n; r++) {

                for (var c = 0, m = table.rows[r].cells.length; c < m; c++) {

                    if (c == 0)
                    {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {
                            if (nodes[i].nodeName.toLowerCase() == 'select')
                                nodes[i].id = 'attributename' + r;
                        }
                    }
                    if (c == 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {

                            if (nodes[i].nodeName.toLowerCase() == 'input')
                                nodes[i].id = 'AttributeMasterId' + r;
                        }
                    }

                    if (c == 2) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {

                            if (nodes[i].nodeName.toLowerCase() == 'input')
                                nodes[i].id = 'ProjectAttributeMasterId' + r;
//                            if (i == 3 && r > 1)
//                            {
//                                nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");
//                            }

                        }
                    }
                    
                    if (c == 3 && r > 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {
                            nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");

                        }
                    }


                }
            }

        }
        else
        {
            alert('Minimum One Row Required')
        }
    }
</script>

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Job Definition</h4>
        <?php echo $this->Form->create($OptionMaster, array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
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
                                <option selected>Select</option>
                            </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
                <div class="col-sm-6">

                </div>
            </div>
        </div>

        <div class="bs-example">


            <table class="table list-master" style="width: 25%; margin-left: 80px;" id="AddUniqueTable"><thead><tr>


                        <td  align="center">Attribute Name</td>
                        <td  align="center">AttributeMasterId</td>
                        <td  align="center">ProjectAttributeMasterId</td>
                        <td ></td >
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < $assigned_details_cnt; $i++) { ?>
                        <tr>
                            <td class="non-bor">
                    <div id="LoadAttributeids">
                        <?php
                        if ($assigneddetails[$i]['UniqueIndentityValue'] != '') { ?>
                        <select name="attributename[]" class="form-control" id="attributename<?php echo ($i + 1); ?>" style="width:141px;" class="form-control" onchange="getids(this.value,<?php echo ($i+1); ?>);">
                        <?php echo $assigneddetails[$i]['UniqueIndentityValue']; ?>
                            </select>
                        <?php }else {?>
                        <select style="width:141px;" class="form-control">
                                <option value="">--Select--</option>
                            </select>
                        <?php } ?>
                    </div>
<!--                                <input name="FieldName[]" class="form-control" id="FieldName<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigneddetails[$i]['UniqueIndentityValue'] ?>">-->
                            </td>
                            <td class="non-bor"><input name="AttributeMasterId[]" readonly="readonly" class="form-control" id="AttributeMasterId<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigneddetails[$i]['AttributeMasterId'] ?>"></td>
                            <td class="non-bor"><input name="ProjectAttributeMasterId[]" readonly="readonly" class="form-control" id="ProjectAttributeMasterId<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigneddetails[$i]['ProjectAttributeMasterId'] ?>"></td>
                            <?php if ($i == 0) { ?>
                            <td class="non-bor"><a><?php echo $this->Html->image("images/add.png", array('name' => 'add', 'onclick' => 'AddRow();')); ?></a></td >
                            <?php } else { ?>
                                <td class="non-bor"><?php echo $this->Html->image("images/delete.png", array('onclick' => 'RemoveRow(' . ($i + 1) . ');')); ?></td >
                            <?php } ?>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>

        </div>

        <div class="form-group" style="text-align:center;">
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
    <div class="bs-example">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Region Name</th>
                    <th>Edit</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($UniqueIdFieldsList as $inputVal => $input) {
                    $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
                    $content = file_get_contents($path);
                    $contentArr = json_decode($content, true);
                    $RegionName = $contentArr['RegionList'][$input['RegionId']];
                    $url = urlencode($input['ProjectId'] . '-' . $input['RegionId']);
                    $EdiT = $this->Html->link('edit', ['action' => 'index', $url]);
                    ?>
                    <tr>
                        <?php
                        echo '<td>' . $input['ProjectName'] . '</td>';
                        echo '<td>' . $RegionName . '</td>';
                        echo '<td>' . $EdiT . '</td>';
                        ?>    
                    </tr>
                <?php }
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