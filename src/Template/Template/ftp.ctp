<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>FTP Manage</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">FTP Host</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Folder path</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-7">
                <button type="button" class="btn btn-primary btn-sm" value="Add" id="testbut" name="testbut" onclick="return validateForm()">Add</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example mt15">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>FTP Host</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Folder path</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Initial Import</td>
                    <td>192.168.1.1</td>
                    <td>testuser</td>
                    <td>password</td>
                    <td>inputfiles\Initial_import</td>
                    <td>
                        <a>Edit</a>
                         - 
                        <a>Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Title extract input</td>
                    <td>192.168.1.1</td>
                    <td>testuser</td>
                    <td>password</td>
                    <td>inputfiles\Title_extract_input</td>
                    <td>
                        <a>Edit</a>
                         - 
                        <a>Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
</div>

