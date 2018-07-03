
<?php 
 use Cake\Routing\Router
?>

<div class="container-fluid">
    <div class="formcontent">
        <h4>Module Configs Edit</h4>
        <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <input type="hidden" name="modulegroup" id="modulegroup"> 
        <input type="hidden" name="IsAllowedToDisplay" id="IsAllowedToDisplay"> 
        <input type="hidden" name="IsUrlMonitoring" id="IsUrlMonitoring"> 
        <input type="hidden" name="IsHygineCheck" id="IsHygineCheck"> 
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-6 control-label"><b>Project:</b></label>
                <div class="col-sm-6" style="margin-top:3px;" >
                <?php 
                    echo $Projects[$ProjectId];
                ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Module:</b></label>
                <div class="col-sm-6" style="margin-top:3px;">
                <?php
                echo $Module[$ModuleId];
                ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Level Id:</b></label>
                <div class="col-sm-6">
                    <?php
                        echo $this->Form->select('LevelId', $temp, ['default' => $LevelId,'Id' => 'level','class'=>'form-control' ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is History Track:</b></label>
                <div class="col-sm-6">
                    <?php
                        echo $this->Form->select('IsHistoryTrack', $TypeArr, ['default' => $IsHistoryTrackValue,'Id' => 'history','class'=>'form-control' ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is Input Mandatory:</b></label>
                <div class="col-sm-6">
                    <?php
                        echo $this->Form->select('IsInputMandatory', $TypeArr, ['default' => $IsInputMandatoryValue,'Id' => 'mandatory','class'=>'form-control' ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is Visibility:</b></label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedyes; ?> type="checkbox" class="form-control" id="visibility" name="IsAllowedToDisplay" onclick="checkAll(this)"  value="1"></div>
                    <?php
//                        echo $this->Form->select('IsAllowedToDisplay', $Type, ['default' => $IsVisibilityValue,'Id' => 'visibility','class'=>'form-control', 'onchange' => 'onSelectChange()' ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is Module:</b></label>
                <div class="col-sm-6">
                    <?php
                        echo $this->Form->select('modulegroup', $ModuleType, ['default' => $IsModuleValue, 'Id' => 'IsModule','onchange' => 'onSelectChange()','class'=>'form-control' ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is Url Monitoring:</b></label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedbulkyes; ?> type="checkbox" class="form-control" id="urlMonitoring" name="IsUrlMonitoring" value="1"></div>
                    <?php
//                       echo $this->Form->select('IsUrlMonitoring', $Type, ['default' => $IsUrlMonitoringValue,'Id' => 'urlMonitoring','class'=>'form-control' ]);
                    ?>
                </div>
            </div>
        </div>
        <?php if($HygineCheckCnt == '1') { ?>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-6 control-label"><b>Is Hygine Check:</b></label>
                <div class="col-sm-6">
                    <div class="col-sm-2" style="margin-top: -5px;"><input <?php echo $selectedhyginecheckyes; ?> type="checkbox" class="form-control" id="HygineCheck" name="IsHygineCheck" value="1"></div>
                        <?php
//                            echo $this->Form->select('IsHygineCheck', $TypeArr, ['default' => $IsHygineCheckValue,'Id' => 'HygineCheck','class'=>'form-control' ]);
                        ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="Save" class="btn btn-primary btn-sm" onclick="return ValidateForm()">Save</button>
                <button type="Cancel" id= "ClearEdit" name="ClearEdit" class="btn btn-primary btn-sm" onclick="return CancelEditForm()">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

//    var Ismodule = document.getElementById("IsModule");
//    if ($('#visibility').val() == 0) {
//        $('#IsModule').val('0');
//        Ismodule.disabled = true;
//    }
    var Ismodule = document.getElementById("IsModule");
    var Ishygenic = document.getElementById('HygineCheck');
    if ($('#IsModule').val() == 0) {
        Ismodule.disabled = true;
        Ishygenic.disabled = true;
    }


    var visibility = document.getElementById('IsModule');
    var Ishygenic = document.getElementById('HygineCheck');

    var strUser = visibility.options[visibility.selectedIndex].text;
    if (strUser == "Production")
    {
        Ishygenic.disabled = false;

    } else
    {
        Ishygenic.selectedIndex = 0;
        Ishygenic.disabled = true;
    }

    function checkAll(chkPassport) {
        var Ishygenic = document.getElementById('HygineCheck');
        var txtIsModule = document.getElementById('IsModule');
        txtIsModule.disabled = chkPassport.checked ? false : true;
        if (!txtIsModule.disabled) {
            txtIsModule.focus();
        } else {
            $('#IsModule').val('0');
            Ishygenic.selectedIndex = 0;
            Ishygenic.disabled = true;
        }

    }

    function onSelectChange(val) {
        var visibility = document.getElementById('IsModule');
        var Ishygenic = document.getElementById('HygineCheck');

        var strUser = visibility.options[visibility.selectedIndex].text;
        if (strUser == "Production")
        {
            Ishygenic.disabled = false;

        } else
        {
            Ishygenic.selectedIndex = 0;
            Ishygenic.disabled = true;
        }
    }
    function ValidateForm() {

        if ($('#history').val() == 0) {
            alert('Select IsHistoryTrack Value');
            $('#history').focus();
            return false;
        }
        if ($('#mandatory').val() == 0) {
            alert('Select IsInputMandatory Value');
            $('#mandatory').focus();
            return false;
        }
        var txtcheckbox = document.getElementById('visibility');
        if ((txtcheckbox.checked) && ($.trim($('#IsModule').val()) == 0)) {
            alert('Select Is Module Value');
            $('#IsModule').focus();
            return false;
        }
    }
</script>