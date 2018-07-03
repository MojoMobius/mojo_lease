<!--Form : Project Config
  Developer: Sivaraj K
  Created On: Sep 20 2016 -->
<?php

use Cake\Routing\Router; ?>

<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>PU Query</h4>
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
        
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-7">
                <button type="button" class="btn btn-primary btn-sm" value="Add" id="testbut" name="testbut" onclick="return validateForm()">Search</button>
            </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    <div class="bs-example mt15">
        <table class="table table-striped table-center">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Document ID</th>
                    <th>PDF filename</th>
                    <th>Financial report excel filename</th>
                    <th>PU Query</th>
                    <th>TL Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>123456</td>
                    <td>xxx_financial_report.pdf</td>
                    <td>xxx_financial_report_excel.xls</td>
                    <td>Bookmark not available</td>
                    <td>Ok check</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>789456</td>
                    <td>yyy_financial_report.pdf</td>
                    <td>yyy_financial_report_excel.xls</td>
                    <td>Bookmark not available</td>
                    <td>Ok check</td>
                </tr>
                
            </tbody>
        </table>
    </div>
    <div>&nbsp;</div>
</div>

