<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Job Dashboard</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Production From date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Production To date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Users list</label>
                <div class="col-sm-4">
                    <select multiple="" class="form-control">
                        <option>User 1</option>
                        <option>User 2</option>
                        <option>User 3</option>
                        <option>User 4</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                <div class="col-sm-5">
                    <select multiple="" class="form-control">
                        <option>Ready for Production</option>
                        <option>Production Inprogress</option>
                        <option>Production Completed</option>
                        <option>Rework Inprogress</option>
                        <option>Rework Completed</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-7">
                <button type="button" class="btn btn-primary btn-sm" value="Add" id="testbut" name="testbut" onclick="return validateForm()">Search</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary btn-sm" value="Add" id="testbut" name="testbut" onclick="return validateForm()">Export</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example mt15">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th width="160">Title validation</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th width="160">Bookmark validation</th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>S.No</th>
                    <th>Document ID</th>
                    <th>Status</th>
                    <th>PDF filename</th>
                    <th>Financial report filename</th>
                    <th>Production Start date</th>
                    <th>Production End date</th>
                    <th>Time taken</th>
                    <th>Production User</th>
                    <th>Production Start date</th>
                    <th>Production End date</th>
                    <th>Time taken</th>
                    <th>Production User</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>123456</td>
                    <td>Production Completed</td>
                    <td>xxx_financial_report.pdf</td>
                    <td>xxx_financial_report_excel.xls</td>
                    <td>18-09-2017 10:11:12</td>
                    <td>18-09-2017 10:14:12</td>
                    <td>03:00</td>
                    <td>User 1</td>
                    <td>18-09-2017 10:31:12</td>
                    <td>18-09-2017 10:37:12</td>
                    <td>06:00</td>
                    <td>User 2</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>789456</td>
                    <td>Production Inprogress</td>
                    <td>yyy_financial_report.pdf</td>
                    <td>yyy_financial_report_excel.xls</td>
                    <td>18-09-2017 10:11:12</td>
                    <td></td>
                    <td></td>
                    <td>User 1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>987854</td>
                    <td>Ready for Production</td>
                    <td>zzz_financial_report.pdf</td>
                    <td>zzz_financial_report_excel.xls</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
</div>

