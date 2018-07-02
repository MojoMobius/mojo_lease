<?php

use Cake\Routing\Router
?>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>Disposition definition</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6" style="line-height: 0px;">
                      <?php 
                     echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId', 'name' => 'ProjectId', 'class'=>'form-control', 'onchange'=>'getRegion(this.value);' )); 
                        ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Region</label>
                <div class="col-sm-6" style="line-height: 0px;">
                     <?php 
                  $Region=array(0=>'--Select--');
                    echo '<div id="LoadRegion">';
                   // $call='getDisposition(this.value);getFiles(this.value);getModule();';
                    echo $this->Form->input('', array('options' => $Region,'id' => 'RegionId', 'name' => 'RegionId', 'class'=>'form-control','onchange'=>'getDisposition(this.value);')); 
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Module</label>
                <div class="col-sm-6" style="margin-top:3px;">
                             <?php $Module = array(0 => '--Select--'); ?>
                    <div id="LoadModule">
        <?php echo $ModuleList;
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






        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="Cancel" id= "Clear" name="Clear" class="btn btn-primary btn-sm" onclick="return CancelForm()">Cancel</button>
                <button type="submit" id= "check_submit" name="check_submit" class="btn btn-primary btn-sm" onclick="return ValidateForm()">Submit</button>

            </div>
        </div>

        <div class="bs-example">
            <table class="table table-striped table-center">
                <thead><tr><th>Input</th><th>Production</th><th>Disposition</th></tr></thead>
                <tbody>
                    <tr> <td style="display:none"><input type="text" name='ID[0]' Id='Id[0]'></td><td><input type="hidden" name='input[0]' value=0>Blank/NULL</td><td><input type="hidden" name='production[0]' value=1>Value</td><td><div class="col-sm-4"><input class='form-control' type="text" id='disp[0]' name='disposition[0]'></div></td> </tr>
                    <tr> <td style="display:none"><input type="text" name='ID[1]' Id='Id[1]'></td><td><input type="hidden" name='input[1]' value=0>Blank/NULL</td><td><input type="hidden" name='production[1]' value=0>Blank/NULL</td ><td><div class="col-sm-4"><input class='form-control' type="text" id='disp[1]' name='disposition[1]'></div></td> </tr>
                    <tr> <td style="display:none"><input type="text" name='ID[2]' Id='Id[2]'></td><td><input type="hidden" name='input[2]' value=1>Value</td><td><input type="hidden" name='production[2]' value=0>Blank/NULL</td><td><div class="col-sm-4"><input class='form-control' type="text" id='disp[2]' name='disposition[2]'></div></td> </tr>
                    <tr> <td style="display:none"><input type="text" name='ID[3]' Id='Id[3]'></td><td><input type="hidden" name='input[3]' value=1>Value</td><td><input type="hidden" name='production[3]' value=2>Same Value</td><td><div class="col-sm-4"><input class='form-control' type="text" id='disp[3]' name='disposition[3]'></div></td> </tr>
                    <tr> <td style="display:none"><input type="text" name='ID[4]' Id='Id[4]'></td><td><input type="hidden" name='input[4]' value=1>Value</td><td><input type="hidden" name='production[4]' value=3>Modified Value</td><td><div class="col-sm-4"><input class='form-control' type="text" id='disp[4]' name='disposition[4]'></div></td> </tr>
                </tbody>
            </table>

        </div>

        <div class="form-group" style="text-align:center;">
            <div class="col-sm-6">
                <table id="AddOptionTable" class="table table-striped"><thead><tr>


                            <td  align="center"><b>Client Input</b></td>
                            <td  align="center"><b>Mob Input</b></td>
                            <td ></td >
                        </tr>
                    </thead>
                    <tbody>

                   <?php 
                   
                   $assigned_cnt = 1;
                   for ($i = 0; $i < $assigned_cnt; $i++) { ?>
                        <tr>
                            <td class="non-bor"> 

                                <select class="form-control" name="PrimaryAttributeId[]" id="LoadPrimaryAttributeids_<?php echo ($i + 1); ?>" onchange="getDependencyatt()"><option value="">--Select--</option> </select></td>

                            <td class="non-bor">

                                <select class="form-control" name="SecondaryAttributeId[]" id="LoadSecondaryAttributeids_<?php echo ($i + 1); ?>" onchange="getDependencyatt();"><option value="">--Select--</option>  </select>
                            </td>
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
</form>
<div class="col-md-3">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
        <div class="col-sm-6">

        </div>
    </div>
</div>
</div> 
<script type="text/javascript">
    function getRegion(projectId) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'DispositionRules','action'=>'ajaxregion'));?>",
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
        var RegionId = $('#RegionId').val();


        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'DispositionRules', 'action' => 'ajaxmodule')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadModule').innerHTML = result;
            }
        });
    }

    function getAttributeids()
    {
        // debugger;
        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var ModuleId = $('#ModuleId').val();
        count = 1;

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'DispositionRules', 'action' => 'ajaxProject')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                var attrList = JSON.parse(result);
                var ModuleAttr = attrList[RegionId][ModuleId];

                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('controller' => 'DispositionRules', 'action' => 'ajaxModuleAttributes')); ?>",
                    data: ({ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
                    dataType: 'text',
                    async: false,
                    success: function (result) {

                        var attrList = JSON.parse(result);

                        var length = 0;
                        for (var key  in attrList['Client_Input']) {
                            if (attrList['Client_Input'].hasOwnProperty(key)) {
                                length++;
                            }
                        }
                        $("#AddOptionTable tbody").remove();
                        $("#AddOptionTable").not(":first-child").remove();

                        if (ModuleAttr == undefined) {
                            length = 1;
                            for (i = 1; i <= length; i++) {
                                var newRow = $("<tr>");
                                var cols = "";
                                cols += '<td class="non-bor"><select class="form-control" name="PrimaryAttributeId[]" id="LoadPrimaryAttributeids_' + i + '">\n\
                        <option value=0> --Select-- </option>';

                                cols += '</select></td>\n\
                        <td class="non-bor"><select class="form-control" name="SecondaryAttributeId[]" id="LoadSecondaryAttributeids_' + i + '">\n\
                        <option value=0>--Select--</option>';

                                cols += '</select></td>';


                                if (i == 1) {
                                    cols += '<td class="non-bor"><a><img src="img/images/add.png" onclick="AddRow();"></a></td>';
                                } else {
                                    cols += '<td class="non-bor"><img src="img/images/delete.png" onclick="RemoveRow(' + i + ');"></td>';
                                }
                                newRow.append(cols);

                                $("#AddOptionTable").append(newRow);
                            }
                        }

                        if (length == 0) {

                            length = 1;
                            for (i = 1; i <= length; i++) {

                                var newRow = $("<tr>");
                                var cols = "";
                                cols += '<td class="non-bor"><select class="form-control" name="PrimaryAttributeId[]" id="LoadPrimaryAttributeids_' + i + '">\n\
                     <option value=""> --Select-- </option>';
                                var ModuleAttribute = ModuleAttr["production"];
                                $.each(ModuleAttribute, function (key, element) {
                                    cols += '<option  value=' + element["ProjectAttributeMasterId"] + '>' + element["DisplayAttributeName"] + '</option>'
                                });
                                cols += '</select></td>\n\
                        <td class="non-bor"><select class="form-control" name="SecondaryAttributeId[]" id="LoadSecondaryAttributeids_' + i + '">\n\
                        <option value="">--Select--</option>';
                                var ModuleAttribute = ModuleAttr["production"];
                                $.each(ModuleAttribute, function (key, element) {

                                    cols += '<option   value=' + element["ProjectAttributeMasterId"] + '>' + element["DisplayAttributeName"] + '</option>'
                                });

                                cols += '</select></td>';
                                if (i == 1) {
                                    cols += '<td class="non-bor"><a><img src="img/images/add.png" onclick="AddRow();"></a></td>';
                                } else {
                                    cols += '<td class="non-bor"><img src="img/images/delete.png" onclick="RemoveRow(' + i + ');"></td>';
                                }
                                newRow.append(cols);

                                $("#AddOptionTable").append(newRow);
                            }
                        }

                        for (i = 1; i <= length; i++) {


                            var newRow = $("<tr>");
                            var cols = "";
                            cols += '<td class="non-bor"><select class="form-control" name="PrimaryAttributeId[]" id="LoadPrimaryAttributeids_' + i + '">\n\
                     <option value=""> --Select-- </option>';
                            var ModuleAttribute = ModuleAttr["production"];
                            $.each(ModuleAttribute, function (key, element) {


                                var ClientAttrId = attrList['Client_Input'][i];

                                var opval = element["ProjectAttributeMasterId"];


                                if (opval == ClientAttrId) {
                                    selected = 'selected=' + ClientAttrId;
                                    // alert($selected);     
                                } else {
                                    selected = '';
                                }

                                cols += '<option ' + selected + ' value=' + element["ProjectAttributeMasterId"] + '>' + element["DisplayAttributeName"] + '</option>'
                            });

                            cols += '</select></td>\n\
    <td class="non-bor"><select class="form-control" name="SecondaryAttributeId[]" id="LoadSecondaryAttributeids_' + i + '">\n\
<option value="">--Select--</option>';
                            var ModuleAttribute = ModuleAttr["production"];
                            $.each(ModuleAttribute, function (key, element) {
                                var MobAttrId = attrList['Mob_Input'][i];
                                var opval = element["ProjectAttributeMasterId"];

                                if (opval == MobAttrId) {
                                    selected = 'selected=' + MobAttrId;
                                    // alert($selected);     
                                } else {
                                    selected = '';
                                }

                                cols += '<option ' + selected + '  value=' + element["ProjectAttributeMasterId"] + '>' + element["DisplayAttributeName"] + '</option>'
                            });

                            cols += '</select></td>';
                            if (i == 1) {
                                cols += '<td class="non-bor"><a><img src="img/images/add.png" onclick="AddRow();"></a></td>';
                            } else {
                                cols += '<td class="non-bor"><img src="img/images/delete.png" onclick="RemoveRow(' + i + ');"></td>';
                            }
                            newRow.append(cols);
                            $("#AddOptionTable").append(newRow);
                        }


                    }

                });

            }
        });

    }

    function getDisposition(RegionId) {
        //  
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'DispositionRules','action'=>'ajaxDisposition'));?>",
            data: ({RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //alert(result);
                var dispValue = JSON.parse(result);
                var disp1 = dispValue['Disposition'][1];
                var disp2 = dispValue['Disposition'][2];
                var disp3 = dispValue['Disposition'][3];
                var disp4 = dispValue['Disposition'][4];
                var disp5 = dispValue['Disposition'][5];

                document.getElementById('disp[0]').value = disp1;
                document.getElementById('disp[1]').value = disp2;
                document.getElementById('disp[2]').value = disp3;
                document.getElementById('disp[3]').value = disp4;
                document.getElementById('disp[4]').value = disp5;

            }
        });
        var result1 = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'DispositionRules','action'=>'ajaxId'));?>",
            data: ({RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result1) {

                var idValue = JSON.parse(result1);
                var Id1 = idValue['Id'][1];
                var Id2 = idValue['Id'][2];
                var Id3 = idValue['Id'][3];
                var Id4 = idValue['Id'][4];
                var Id5 = idValue['Id'][5];

                document.getElementById('Id[0]').value = Id1;
                document.getElementById('Id[1]').value = Id2;
                document.getElementById('Id[2]').value = Id3;
                document.getElementById('Id[3]').value = Id4;
                document.getElementById('Id[4]').value = Id5;

            }
        });

    }

    function ValidateForm() {

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
            alert('Select Module Name');
            $('#RegionId').focus();
            return false;
        }


        var counter = $('#AddOptionTable tbody tr').length;
        for (i = 1; i <= counter; i++)
        {

            if ($.trim($('#LoadPrimaryAttributeids_' + i).val()) == '')
            {
                alert('Enter Client Input in Row - ' + i);
                $('#LoadPrimaryAttributeids_' + i).focus();
                return false;
            }
            if ($.trim($('#LoadSecondaryAttributeids_' + i).val()) == '')
            {
                alert('Enter Mob Input in Row - ' + i);
                $('#LoadSecondaryAttributeids_' + i).focus();
                return false;
            }

            for (j = 1; j <= counter; j++)
            {
                if (i != j)
                {
                    if ($('#LoadPrimaryAttributeids_' + i).val() == $('#LoadPrimaryAttributeids_' + j).val())
                    {
                        alert("Client Input Entered in Row " + i + " matched with Row " + j);
                        $('#LoadPrimaryAttributeids_' + j).focus();
                        return false;
                    }
                    if ($('#LoadSecondaryAttributeids_' + i).val() == $('#LoadSecondaryAttributeids_' + j).val())
                    {
                        alert("Mob input Entered in Row " + i + " matched with Row " + j);
                        $('#LoadSecondaryAttributeids_' + j).focus();
                        return false;
                    }
                }
            }
        }

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


        var count = $('#AddOptionTable  tr').length;
        //  alert(count);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="non-bor"><select class="form-control" name="PrimaryAttributeId[]" id="LoadPrimaryAttributeids_' + count + '"><option value="0">--Select--</option> </select></td>\n\
    <td class="non-bor"><select class="form-control" name="SecondaryAttributeId[]" id="LoadSecondaryAttributeids_' + count + '"><option value="0">--Select--</option></select></td>';

        cols += '<td class="non-bor"><img src="img/images/delete.png" onclick="RemoveRow(' + count + ');"></td>';

        newRow.append(cols);
        $("#AddOptionTable").append(newRow);

        var result = new Array();
        var ProjectId = $('#ProjectId').val();
        var RegionId = $('#RegionId').val();
        var ModuleId = $('#ModuleId').val();


        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'DispositionRules', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId, ModuleId: ModuleId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadPrimaryAttributeids_' + count + '').innerHTML = result;
                document.getElementById('LoadSecondaryAttributeids_' + count + '').innerHTML = result;
            }
        });

    }

    function RemoveRow(r) {
        //debugger;
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
                            if (nodes[i].nodeName.toLowerCase() == 'select')
                                nodes[i].id = 'LoadPrimaryAttributeids_' + r;
                        }
                    }
                    if (c == 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {

                            if (nodes[i].nodeName.toLowerCase() == 'select')
                                nodes[i].id = 'LoadSecondaryAttributeids_' + r;

//                            if (i == 2 && r > 1)
//                            {
//
//                                nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");
//                            }

                        }
                    }

                    if (c == 2) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {
                            if (i == 0 && r > 1)
                            {
                                nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");
                            }
                        }
                    }


                }
            }

        } else
        {
            alert('Minimum One Row Required')
        }


    }

</script>