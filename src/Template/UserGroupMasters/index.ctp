<?php

use Cake\Routing\Router; ?>

<div class="container-fluid">
    <div class="jumbotron formcontent">
        <h4>User Group Master</h4>
        <?php echo $this->Form->create($UserGroupMasters, array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Group Name</label>
                <div class="col-sm-6">
                    <table class="table list-master" style="width: 25%; margin-left: 75px;" id="AddUniqueTable">
                        <thead><tr style="display:none"></tr></thead>
                        <tbody>
                  <?php      for ($i = 0; $i <= $assigned_details_cnt; $i++) { ?>
                            <tr>
                                <td class="non-bor"><input name="UserGroupName[]" class="form-control" id="UserGroupName_<?php echo ($i + 1); ?>" type="text" autocomplete="off" value="<?php echo $assigned_details[$i]['GroupName'] ?>"></td>
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
            </div>
        </div>



        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" id= "check_submit" name="check_submit" class="btn btn-primary btn-sm" onclick="return ValidateForm()">Submit</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($User_Group_Masters as $inputVal => $input) {
                    $url = urlencode($input['Id']);
                    $EdiT = $this->Html->link('Edit', ['action' => 'edit', $url]);
                    $delete=$this->Html->link('Delete', ['action' => 'delete', $url]);
                    ?>
                <tr>
                        <?php
                        echo '<td>' . $input['GroupName'] . '</td>';
                        echo '<td>' . $EdiT . '</td>';
                        echo '<td>' . $delete . '</td>';
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

<script type="text/javascript">

    function AddRow() {

        var count = $('#AddUniqueTable  tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="non-bor"><input type="text" name="UserGroupName[]" id="UserGroupName_' + count + '" autocomplete="off" class="form-control"></td>';
        cols += '<td class="non-bor"><img src="<?php echo Router::url('/', true); ?>webroot/img/images/delete.png" onclick="RemoveRow(' + count + ');"></td>';

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
                            if (nodes[i].nodeName.toLowerCase() == 'input')
                                nodes[i].id = 'UserGroupName_' + r;
                        }
                    }
                    if (c == 1 && r > 1) {
                        var nodes = table.rows[r].cells[c].childNodes;
                        for (var i = 0; i < nodes.length; i++) {
                            nodes[i].setAttribute('onclick', "RemoveRow(" + r + ")");

                        }
                    }
                }
            }

        } else
        {
            alert('Minimum One Row Required')
        }
    }
    function ValidateForm()
    {
        var counter = $('#AddUniqueTable tbody tr').length;
        for (i = 1; i <= counter; i++)
        {
            if ($.trim($('#UserGroupName_' + i).val()) == '')
            {
                alert('Enter Group Name Option in Row - ' + i);
                $('#UserGroupName_' + i).focus();
                return false;
            }

            // var regex = new RegExp(/^[a-z0-9]+$/i);
            var regex = new RegExp(/^[a-z\d\s]+$/i);
            var value = $('#UserGroupName_' + i).val();
            var result = regex.test(value);

            if (result == false) {

                alert("Only allowed AlphaNumeric Values");
                $('#UserGroupName_' + i).focus();
                return false;
            }


            for (j = 1; j <= counter; j++)
            {
                if (i != j)
                {
                    if ($('#UserGroupName_' + i).val() == $('#UserGroupName_' + j).val())
                    {
                        alert("Group Name Entered in Row " + i + " matched with Row " + j);
                        $('#UserGroupName_' + j).focus();
                        return false;
                    }

                }
            }
        }
    }
</script>