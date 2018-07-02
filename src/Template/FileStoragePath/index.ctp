<?php

use Cake\Routing\Router
?>
<script type="text/javascript">
    function deleteAttribute(obj) {
        // debugger;
        var getConform = confirm("Are you sure you want to delete!");
        if (getConform) {
            var fieldId = $(obj).attr("fieldName")

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller' => 'FileStoragePath', 'action' => 'deleteRow')); ?>",
                data: ({Id: fieldId}),
                dataType: 'text',
                async: false,
                success: function (result) {
                }
            });
            alert("Deleted Successfully");
            window.location.reload();
//setInterval(function(){ alert("Deleted Successfully"); }, 1000);
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

        if ($('#FilePath').val() == 0)
        {
            alert('Enter File path');
            $('#FilePath').focus();
            return false;
        }
    }
  
</script> 

<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <h4>File Storage Path</h4>
            <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6">
                    <?php echo $ProListopt; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label">File Path</label>
                <div class="col-sm-6">

                    <input name="FilePath" class="form-control" id="FilePath" type="text" value="<?php echo $assigneddetails[0]['FilePath'] ?>">

                </div>
            </div>
        </div>


        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" value="Submit" id="submit" name="submit" onclick="return validateForm()">Submit</button>

            </div>
        </div>


    </div> 
</form>
<div class="bs-example">
    <table class="table table-striped table-center">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>File Path</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                <?php
                foreach ($FileStoragePathList as $inputVal => $input) {
                    $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
                    $content = file_get_contents($path);
                    $contentArr = json_decode($content, true);
                    $RegionName = $contentArr['RegionList'][$input['RegionId']];
                    $url = urlencode($input['ProjectId']);
                    $EdiT = $this->Html->link('Edit', ['action' => 'index', $url]);
                    $Delete='<a style="cursor:pointer" onclick ="deleteAttribute(this)" fieldName = "'.$input['ProjectId'].'">Delete</a>';
                    ?>
            <tr>
                        <?php
                        echo '<td>' . $Projects[$input['ProjectId']] . '</td>';
                        echo '<td>' . $input['FilePath'] . '</td>';
                        echo '<td>' . $EdiT . '</td>';
                        echo '<td>' . $Delete . '</td>';
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