<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>User Management</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Email Id</label>
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
                <label for="inputEmail3" class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-2">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Role</label>
                <div class="col-sm-2">
                    <select class="form-control">
                        <option>Admin</option>
                    </select>
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
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>test@test.com</td>
                    <td>Vlad</td>
                    <td>Stankovic</td>
                    <td>Production TL</td>
                    <td>
                        <a>Edit</a>
                         - 
                        <a>Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>test2@test.com</td>
                    <td>Celina</td>
                    <td>Benon</td>
                    <td>Production User</td>
                    <td>
                        <a>Edit</a>
                         - 
                        <a>Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>test3@test.com</td>
                    <td>Edburg</td>
                    <td>Woodberry</td>
                    <td>QC TL</td>
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

