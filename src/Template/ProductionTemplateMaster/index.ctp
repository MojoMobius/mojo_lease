<?php

use Cake\Routing\Router; ?>
<script type="text/javascript">

    function getAttributeids(ProjectId)
    {
        var ProjectId = $('#ProjectId').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'ProductionTemplateMaster', 'action' => 'ajaxModuleAttributes')); ?>",
            data: ({ProjectId: ProjectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                //  alert(result);

                var attrList = JSON.parse(result);
                var length = 0;
                for (var key  in attrList['BlockName']) {
                    if (attrList['BlockName'].hasOwnProperty(key)) {
                        length++;
                    }
                }
                // $("#AddOptionTable tbody").remove();
                //   $("#AddOptionTable").not(":first-child").remove();


            }
        });
    }


    function AddRow() {
        if ($('#ProjectId').val() == 0) {
            alert('Please Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        var count = $('#AddOptionTable  tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="non-bor"><input type="text" name="BlockName[]" id="BlockName_' + count + '" class="form-control"></td>';
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
                                nodes[i].id = 'BlockName_' + r;
                        }
                    }
                    if (c == 1) {
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
            alert('Minimum One Row Required');
        }
    }

    function ValidateForm()
    {
        if ($('#ProjectId').val() == 0)
        {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }
        var counter = $('#AddOptionTable tbody tr').length;

        for (i = 1; i <= counter; i++)
        {
            if ($.trim($('#BlockName_' + i).val()) == '')
            {
                alert('Enter Block Name Option in Row - ' + i);
                $('#BlockName_' + i).focus();
                return false;
            }

            for (j = 1; j <= counter; j++)
            {
                if (i != j)
                {
                    if ($('#BlockName_' + i).val() == $('#BlockName_' + j).val())
                    {
                        alert("Block Name Entered in Row " + i + " matched with Row " + j);
                        $('#BlockName_' + j).focus();
                        return false;
                    }

                }
            }
        }


    }
</script>
<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>Production Template Master</h4>
        <?php echo $this->Form->create($ProductionTemplateMaster, array('class' => 'form-horizontal', 'id' => 'projectforms', 'enctype' => 'multipart/form-data')); ?>
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
                <label for="inputEmail3" class="col-sm-6 control-label">Block Name</label>
                <div class="col-sm-6">
                    <table id="AddOptionTable" class="table table-striped">
                        <thead><tr style="display:none"></tr></thead>
                        <tbody>
                   <?php 
                  for ($i = 0; $i < $assigned_details_cnt; $i++) { ?>
                            <tr>
                                <td class="non-bor"><input name="BlockName[]" class="form-control" id="BlockName_<?php echo ($i + 1); ?>" type="text" value="<?php echo $assigned_details[$i]['BlockName'] ?>"></td>
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
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return ValidateForm()">Submit</button>		 
            </div>
        </div>

    </div> 


    <!--        <div class="col-md-4">
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




    <div class="bs-example">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Block Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Production_Template_Master as $inputVal => $input) {
                    $EdiT = $this->Html->link('edit', ['action' => 'index', $input['ProjectId']]);
                    ?>
                <tr>
                        <?php
                        echo '<td>' . $ProjectsList[$input[ProjectId]] . '</td>';
                        echo '<td>' . $input['BlockName'] . '</td>';
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
</form>
</div>