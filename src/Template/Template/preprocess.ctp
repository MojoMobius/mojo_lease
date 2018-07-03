<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Pre Process dashboard</h4>
        <?php echo $this->Form->create($Projectconfig, array('class' => 'form-horizontal', 'id' => 'projectforms','name' => 'projectforms')); ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">From date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">To date</label>
                <div class="col-sm-4">
                    <input type="text" name="ProjectId" id="ProjectId" value="<?php echo $ProjectIdEdit; ?>" class="form-control" onblur="checkprojectid(this.value);">
                </div>
            </div>
        </div>
        
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-7">
                <button type="button" class="btn btn-primary btn-sm" value="Add" id="testbut" name="testbut" onclick="return validateForm()">Search</button>
            </div>
        </div>

        <table border="1" width="300" style="margin-top: -85px;margin-bottom: 20px;">
            <tr>
                <th>Process name</th>
                <th align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Count</th>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;Title extract</td>
                <td align="center">5</td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;Financial data extract&nbsp;</td>
                <td align="center">5</td>
            </tr>
        </table>
        
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example mt15">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Document ID</th>
                    <th>PDF filename</th>
                    <th>Financial report filename</th>
                    <th>Download Date & Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>123456</td>
                    <td>xxx_financial_report.pdf</td>
                    <td>xxx_financial_report_excel.xls</td>
                    <td>08/21/2017 10:23:23</td>
                    <td>Financial data extract Inprogress</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>789456</td>
                    <td>yyy_financial_report.pdf</td>
                    <td>yyy_financial_report_excel.xls</td>
                    <td>08/21/2017 10:23:23</td>
                    <td>Title extract process Inprogress</td>
                </tr>
                
            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
</div>

