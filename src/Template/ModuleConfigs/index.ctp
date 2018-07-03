<?php ?>
    <?php
     use Cake\Routing\Router
     ?>
<div class="container-fluid mt15">
    <div class="formcontent">
        <h4>Module Configs</h4>
                <?php echo $this->Form->create('',array('class'=>'form-horizontal','id'=>'projectforms')); ?>
        <div class="col-md-4" >
            <div class="form-group">
                <label for="inputEmail3" style="margin-top: 5px;" class="col-sm-6 control-label">Project</label>
                <div class="col-sm-6" style="line-height: 0px;">
                        <?php echo $this->Form->input('', array('options' => $Projects,'id' => 'ProjectId' ,'name' => 'ProjectId','class'=>'form-control', 'onchange'=>'getModule(this.value);' )); ?>
                </div>
            </div>
        </div>
                <?php
                $Module=array(0=>'--Select--');
                $TypeArr=array('0'=>'No',1=>'Yes',2=>'No',''=>'No');
                  echo '<div id="LoadModule">';
                  echo $this->Form->input('Module Name', array('options' => $Module,'type'=>'hidden', 'id' => 'ModuleId', 'name' => 'ModuleId', 'class'=>'form-control')); 
                  echo '</div>';
                ?>
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12">
                <button type="submit" id= "check_submit" name="check_submit" class="btn btn-primary btn-sm" onclick="return ValidateForm()">Submit</button>
                <button type="Cancel" id= "Clear" name="Clear" class="btn btn-primary btn-sm" onclick="return CancelForm()">Cancel</button>
            </div>
        </div>
    </div> 
</div>

<div class="bs-example container-fluid">
    <table style='width:100%;' class='table table-striped table-center' id='example'>
            <?php echo $this->Html->tableHeaders(array('S.No','Project Name','Module Name','Level Id','Is History Track','Is Input Mandatory','Is Visibility','Is Module','Is Url Monitoring','Is Hygine Check','Action'),array('class' => 'Heading'),array('class' => 'Cell'));
            $i = 0;
            foreach ($query as $query):

                $Edit=$this->Html->link('Edit', ['action' => 'edit', $query->Id]);
                $Type=array(1=>'Yes',''=>'No',0=>'No');
                $ModuleType=array(1=>'Production',2=>'QC Validation');
                echo $this->Html->tableCells(array(
                    array(
                        array($i+1,array('class' => 'Cell')),
                        array($ProjectsList[$query->Project],array('class' => 'Cell')),
                        array($query->ModuleName,array('class' => 'Cell')),
                        array($query->LevelId,array('class' => 'Cell')),
                        array($TypeArr[$query->IsHistoryTrack],array('class' => 'Cell')),
                        array($TypeArr[$query->IsInputMandatory],array('class' => 'Cell')),
                        array($Type[$query->IsAllowedToDisplay],array('class' => 'Cell')),
                        array($ModuleType[$query->modulegroup],array('class' => 'Cell')),
                        array($Type[$query->IsUrlMonitoring],array('class' => 'Cell')),
                        array($TypeArr[$query->IsHygineCheck],array('class' => 'Cell')),
                        array($Edit,array('class' => 'Cell')),
                        )
                    ),array('class' => 'Row','style'=>'overflow: hidden;'),array('class' => 'Row1','style'=>'overflow: hidden;'));
                $i++;
            endforeach;
            ?>
    </table>
</div>

<script type="text/javascript">

    function getModule(projectId) {
        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'ModuleConfigs','action'=>'ajaxModule'));?>",
            data: ({projectId: projectId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('LoadModule').innerHTML = result;
            }
        });
    }

    function ValidateForm() {
        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }

        var counter = $('#AddOptionTable tbody tr').length;
        // alert(counter); 
        for (i = 1; i <= counter; i++)
        {
            if ($.trim($('#level_' + i).val()) == '')
            {
                alert('Select Level Id in Row - ' + i);
                $('#level_' + i).focus();
                return false;
            }

            if ($.trim($('#history_' + i).val()) == 0)
            {
                alert('Select Maintain History in Row - ' + i);
                $('#history_' + i).focus();
                return false;
            }

            if ($.trim($('#mandatory_' + i).val()) == 0)
            {
                alert('Select Input Mandatory in Row - ' + i);
                $('#mandatory_' + i).focus();
                return false;
            }

            var txtcheckbox = document.getElementById('checkbox[' + i + ']');
            if ((txtcheckbox.checked) && ($.trim($('#IsModule_' + i).val()) == 0)) {
                alert('Select Is Module in Row - ' + i);
                $('#IsModule_' + i).focus();
                return false;
            }

        }

        var checkboxChecked = $('input[type="checkbox"]:checked').length;
        if (checkboxChecked == 0) {
            alert('Select atleast one Checkbox to do mapping.');
            return false;
        }

        return true;
    }

    function CancelForm() {

        $('#LoadModule').hide();
        $('#ProjectId').val('0');
        //window.location.reload();
        return true;
    }

    function checkAll(chkPassport) {

        var counter = $('#AddOptionTable tbody tr').length;
        for (i = 1; i <= counter; i++) {
            var txtIsModule = document.getElementById('IsModule_' + i);
            txtIsModule.disabled = chkPassport.checked ? false : true;
            if (!txtIsModule.disabled) {
            } else {
                $('#IsModule_' + i).val('0');
            }
        }
        var select_all = document.getElementById("checkall"); //select all checkbox
        var checkboxes = document.getElementsByClassName("chk-wid"); //checkbox items

        //select all checkboxes
        select_all.addEventListener("change", function (e) {
            for (i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = select_all.checked;
            }
        });

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function (e) { //".checkbox" change 
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) {
                    select_all.checked = false;
                }

                $(".chk-wid").change(function () {
                    //check "select all" if all checkbox item are checked
                    if ($(".chk-wid:checked").length == $(".chk-wid").length) {
                        $("#select_all").prop("checked", true);
                    }
                });
            });
        }
    }
    function checkAllUrl() {

        var select_all_Url = document.getElementById("IsURL"); //select all checkbox
        var checkboxes_Url = document.getElementsByClassName("chk-wid-Url"); //checkbox items

        //select all checkboxes
        select_all_Url.addEventListener("change", function (e) {
            for (i = 0; i < checkboxes_Url.length; i++) {
                checkboxes_Url[i].checked = select_all_Url.checked;
            }
        });


        for (var i = 0; i < checkboxes_Url.length; i++) {
            checkboxes_Url[i].addEventListener('change', function (e) { //".checkbox" change 
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) {
                    select_all_Url.checked = false;
                }

                $(".chk-wid-Url").change(function () {
                    //check "select all" if all checkbox item are checked
                    if ($(".chk-wid-Url:checked").length == $(".chk-wid-Url").length) {
                        $("#select_all_Url").prop("checked", true);
                    }
                });
            });
        }
    }
    function checkAllHygine() {
        var select_all_HygineCheck = document.getElementById("IsHygineCheck"); //select all checkbox
        var checkboxes_HygineCheck = document.getElementsByClassName("chk-wid-HygineCheck"); //checkbox items

        //select all checkboxes
        select_all_HygineCheck.addEventListener("change", function (e) {
            for (i = 0; i < checkboxes_HygineCheck.length; i++) {
                checkboxes_HygineCheck[i].checked = select_all_HygineCheck.checked;
            }
        });


        for (var i = 0; i < checkboxes_HygineCheck.length; i++) {
            checkboxes_HygineCheck[i].addEventListener('change', function (e) { //".checkbox" change 
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) {
                    select_all_HygineCheck.checked = false;
                }

                $(".chk-wid-HygineCheck").change(function () {
                    //check "select all" if all checkbox item are checked
                    if ($(".chk-wid-HygineCheck:checked").length == $(".chk-wid-HygineCheck").length) {
                        $("#select_all_HygineCheck").prop("checked", true);
                    }
                });
            });
        }
    }
    function onSelectChange(val) {
        var visibility = document.getElementById('IsModule_' + val);
        var Ishygenic = document.getElementById('IsHygineCheck[' + val + ']');

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
    function checkAllAtt(chkPassport, val) {
        var Ishygenic = document.getElementById('IsHygineCheck[' + val + ']');
        var txtIsModule = document.getElementById('IsModule_' + val);
        txtIsModule.disabled = chkPassport.checked ? false : true;
        if (!txtIsModule.disabled) {
            txtIsModule.focus();

        } else {
            $('#IsModule_' + val).val('0');
            Ishygenic.selectedIndex = 0;
            Ishygenic.disabled = true;
        }

        var select_all = document.getElementById("checkall"); //select all checkbox
        var checkboxes = document.getElementsByClassName("chk-wid"); //checkbox items


        for (var i = 0; i < checkboxes.length; i++) {
            //uncheck "select all", if one of the listed checkbox item is unchecked
            $(".chk-wid").change(function () {
                if (this.checked == false) {
                    select_all.checked = false;
                }

                //check "select all" if all checkbox item are checked
                if ($(".chk-wid:checked").length == $(".chk-wid").length) {
                    $("#checkall").prop("checked", true);
                }
            });

        }
    }
    function checkAllUrlAtt() {
        var select_all_Url = document.getElementById("IsURL"); //select all checkbox
        var checkboxes_Url = document.getElementsByClassName("chk-wid-Url"); //checkbox items


        for (var i = 0; i < checkboxes_Url.length; i++) {
            //uncheck "select all", if one of the listed checkbox item is unchecked
            $(".chk-wid-Url").change(function () {
                if (this.checked == false) {
                    select_all_Url.checked = false;
                }

                //check "select all" if all checkbox item are checked
                if ($(".chk-wid-Url:checked").length == $(".chk-wid-Url").length) {
                    $("#IsURL").prop("checked", true);
                }
            });

        }
    }

    function checkAllHygineAtt() {
        var select_all_HygineCheck = document.getElementById("IsHygineCheck"); //select all checkbox
        var checkboxes_HygineCheck = document.getElementsByClassName("chk-wid-HygineCheck"); //checkbox items


        for (var i = 0; i < checkboxes_HygineCheck.length; i++) {
            //uncheck "select all", if one of the listed checkbox item is unchecked
            $(".chk-wid-HygineCheck").change(function () {
                if (this.checked == false) {
                    select_all_HygineCheck.checked = false;
                }

                //check "select all" if all checkbox item are checked
                if ($(".chk-wid-HygineCheck:checked").length == $(".chk-wid-HygineCheck").length) {
                    $("#IsHygineCheck").prop("checked", true);
                }
            });

        }
    }


</script>
