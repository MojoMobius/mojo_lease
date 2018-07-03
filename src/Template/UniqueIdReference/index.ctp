
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
        var cols1 = "";
        var cols2 = "";
        var cols3 = "";
        cols1 += '<select class="form-control" name="attributename[1]" id="attributename1" style="width:141px;" onchange="getids(this.value,1);">';
        cols2 += '<select class="form-control" name="attributename[2]" id="attributename2" style="width:141px;" onchange="getids(this.value,2);">';
        cols3 += '<select class="form-control" name="attributename[3]" id="attributename3" style="width:141px;" onchange="getids(this.value,3);">';
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Uniqueidfields', 'action' => 'ajaxattributeids')); ?>",
            data: ({ProjectId: ProjectId, RegionId: RegionId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //document.getElementById('LoadAttributeids').innerHTML = result;
                cols1 += result;
                cols1 += '</select>';
                cols2 += result;
                cols2 += '</select>';
                cols3 += result;
                cols3 += '</select>';
                document.getElementById('LoadAttributeids1').innerHTML = cols1;
                document.getElementById('LoadAttributeids2').innerHTML = cols2;
                document.getElementById('LoadAttributeids3').innerHTML = cols3;

            }
        });
    }
    function getids(ids, val) {
        var res = ids.split("_");

        var text1 = $("#attributename1 option:selected").text();
        var text2 = $("#attributename2 option:selected").text();
        var text3 = $("#attributename3 option:selected").text();

        alert
        document.getElementById("AttributeMasterId" + val).value = res[1];
        document.getElementById("ProjectAttributeMasterId" + val).value = res[0];
        document.getElementById("attributeText1").value = text1;
        document.getElementById("attributeText2").value = text2;
        document.getElementById("attributeText3").value = text3;
    }


</script>

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Unique Id Reference</h4>
        <?php echo $this->Form->create($UniqueIdReference, array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
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
                            <option selected>Select</option>
                        </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label">&nbsp;</label>
                <div class="col-sm-6">

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-7 control-label">&nbsp;</label>
                <div class="col-sm-5">

                </div>
            </div>
        </div>


        <div class="bs-example">


            <table class="table list-master" style="width: 25%; margin:0 0 10px 100px" id="AddUniqueTable"><thead><tr>

                        <td  align="center">Reference Name</td>
                        <td  align="center">Attribute Name</td>
                        <td  align="center">AttributeMasterId</td>
                        <td  align="center">ProjectAttributeMasterId</td>
                        <td ></td >
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    <tr>
                        <td class="non-bor"><input name="ReferenceId[1]" readonly="readonly" class="form-control" id="ReferenceId<?php echo ($i + 1); ?>" type="text" value="DomainId"></td>
                        <td class="non-bor">
                            <div id="LoadAttributeids1">
                        <?php
                        if ($assigneddetails[$i]['UniqueIndentityValue'] != '') { ?>
                                <select name="attributename[1]" class="form-control" id="attributename1" style="width:141px;" class="form-control" onchange="getids(this.value,<?php echo ($i+1); ?>);">
                        <?php echo $assigneddetails[$i]['UniqueIndentityValue']; ?>
                                </select>
                        <?php }else {?>
                                <select style="width:141px;" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                        <?php } ?>
                            </div></td>
                        <td class="non-bor"><input name="AttributeMasterId[1]" readonly="readonly" class="form-control" id="AttributeMasterId<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigneddetails[$i]['AttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="ProjectAttributeMasterId[1]" readonly="readonly" class="form-control" id="ProjectAttributeMasterId<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigneddetails[$i]['ProjectAttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="attributeText[1]" class="form-control" id="attributeText<?php echo ($i + 1); ?>" type="hidden" value=""></td>

                    </tr>
                    <tr>
                        <td class="non-bor"><input name="ReferenceId[2]" readonly="readonly" class="form-control" id="ReferenceId<?php echo ($i + 2); ?>" type="text" value="DomainUrl"></td>
                        <td class="non-bor">
                            <div id="LoadAttributeids2">
                        <?php
                        if ($assigneddetails[$i+1]['UniqueIndentityValue'] != '') { ?>
                                <select name="attributename[2]" class="form-control" id="attributename2" style="width:141px;" class="form-control" onchange="getids(this.value,<?php echo ($i+2); ?>);">
                        <?php echo $assigneddetails[$i+1]['UniqueIndentityValue']; ?>
                                </select>
                        <?php }else {?>
                                <select style="width:141px;" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                        <?php } ?>
                            </div></td>
                        <td class="non-bor"><input name="AttributeMasterId[2]" readonly="readonly" class="form-control" id="AttributeMasterId<?php echo ($i + 2); ?>" type="text" value="<?php echo $assigneddetails[$i+1]['AttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="ProjectAttributeMasterId[2]" readonly="readonly" class="form-control" id="ProjectAttributeMasterId<?php echo ($i + 2); ?>" type="text" value="<?php echo $assigneddetails[$i+1]['ProjectAttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="attributeText[2]" class="form-control" id="attributeText<?php echo ($i + 2); ?>" type="hidden"></td>
                    </tr>
                    <tr>
                        <td class="non-bor"><input name="ReferenceId[3]" readonly="readonly" class="form-control" id="ReferenceId<?php echo ($i + 3); ?>" type="text" value="InputId"></td>
                        <td class="non-bor">
                            <div id="LoadAttributeids3">
                        <?php
                        if ($assigneddetails[$i+2]['UniqueIndentityValue'] != '') { ?>
                                <select name="attributename[3]" class="form-control" id="attributename3" style="width:141px;" class="form-control" onchange="getids(this.value,<?php echo ($i+3); ?>);">
                        <?php echo $assigneddetails[$i+2]['UniqueIndentityValue']; ?>
                                </select>
                        <?php }else {?>
                                <select style="width:141px;" class="form-control">
                                    <option value="">--Select--</option>
                                </select>
                         <?php } ?>
                            </div></td>
                        <td class="non-bor"><input name="AttributeMasterId[3]" readonly="readonly" class="form-control" id="AttributeMasterId<?php echo ($i + 3); ?>" type="text" value="<?php echo $assigneddetails[$i+2]['AttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="ProjectAttributeMasterId[3]" readonly="readonly" class="form-control" id="ProjectAttributeMasterId<?php echo ($i + 3); ?>" type="text" value="<?php echo $assigneddetails[$i+2]['ProjectAttributeMasterId'] ?>"></td>
                        <td class="non-bor"><input name="attributeText[3]" class="form-control" id="attributeText<?php echo ($i + 3); ?>" type="hidden"></td>
                    </tr>


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