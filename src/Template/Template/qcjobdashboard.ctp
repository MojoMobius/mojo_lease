<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>QC Job Dashboard</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">QC From date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">QC To date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">QC Users list</label>
                <div class="col-sm-4">
                    <select multiple="" class="form-control">
                        <option>User 5</option>
                        <option>User 6</option>
                        <option>User 7</option>
                        <option>User 8</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-4 control-label">Status</label>
                <div class="col-sm-5">
                    <select multiple="" class="form-control">
                        <option>Ready for QC</option>
                        <option>QC Inprogress</option>
                        <option>QC Completed</option>
                        <option>QC Rework Inprogress</option>
                        <option>QC Rework Completed</option>
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
                    <th>QC Start date</th>
                    <th>QC End date</th>
                    <th>Time taken</th>
                    <th>QC User</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>123456</td>
                    <td>QC Completed</td>
                    <td>xxx_financial_report.pdf</td>
                    <td>xxx_financial_report_excel.xls</td>
                    <td>18-09-2017 10:11:12</td>
                    <td>18-09-2017 10:14:12</td>
                    <td>03:00</td>
                    <td>User 5</td>
                    <td>18-09-2017 10:17:12</td>
                    <td>18-09-2017 10:21:12</td>
                    <td>04:00</td>
                    <td>User 6</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>789456</td>
                    <td>QC Inprogress</td>
                    <td>yyy_financial_report.pdf</td>
                    <td>yyy_financial_report_excel.xls</td>
                    <td>18-09-2017 10:11:12</td>
                    <td></td>
                    <td></td>
                    <td>User 5</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>987854</td>
                    <td>Ready for QC</td>
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

