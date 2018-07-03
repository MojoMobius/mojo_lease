<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!--<link rel="stylesheet" href="/webroot/css/font-awesome.min.css">-->
<?php
use Cake\Routing\Router;
?>
<style>
    .panel{
        height:425;
        width:100%;
        margin:10px 0px;
    }
    .setting-popup .widget-item {
        display: flex;
        align-items: center;
        padding: 5px 0px;
        justify-content: space-between;
        border-bottom: 1px solid rgb(231, 231, 231);
        margin-bottom:10px;
    }
    .widget-item > span {
        font-weight: bold;
        color: #666;
        margin-right: 20px;
    }
    .setting-popup .widget-item .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 28px;
        margin-bottom: 0;
    }
    .setting-popup.widget-item label {
        color: #555555;
        font-size: 12pt;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 28px;
        margin-bottom: 0;
    }

    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 21px;
        width: 21px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 28px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .set-filter{
        padding-top:15px;
    }
    .set-border {
        border-bottom: 1px dashed #ADADAD;
    }
    .set-border p{
        font-size:20px;
        padding:5px;
        font-weight:600;
    }
    .setting-ico{
        padding-top:10px;
    }
    .setting-ico i {font-size: 20pt; cursor: pointer;color:#6b6d70;}
    .setting-ico i:hover {color: #4397e6;}
</style>
<div class="container-fluid">
    <div class=" jumbotron formcontent">
        <div class="col-md-12 set-border"><div class="col-md-11"> <p>Quality Dashboard </p></div> <div class="col-md-1 setting-ico"> <i class="fa fa-cog pull-right" data-toggle="modal" data-target="#widget-modal"></i></div></div>
        <?php echo $this->Form->create('', array('class' => 'form-horizontal', 'id' => 'projectforms')); ?>
        <div class="col-md-12 set-filter"> 
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Project</label>
                    <div class="col-sm-6">
                    <?php
                    echo $this->Form->input('', array('options' => $Projects, 'id' => 'ProjectId', 'name' => 'ProjectId', 'class' => 'form-control prodash-txt', 'value' => $ProjectId));
                    ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">

                    <label for="inputPassword3" class="col-sm-6 control-label">From</label>
                    <div class="col-sm-6">
                        <input readonly='readonly' placeholder='MM-YYYY' type='text' name='month_from' id='month_from'>

                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">

                    <div class="col-sm-6">
                        <label for="inputPassword3" class="col-sm-6 control-label">To</label>
                        <div class="col-sm-6">
                            <input readonly='readonly' placeholder='MM-YYYY' type='text' name='month_to' id='month_to'>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12"><input type="hidden" name="resultcnt" id="resultcnt">
                <?php
            echo $this->Form->button('QC Reports', array('id' => 'check_submit', 'name' => 'check_submit', 'value' => 'Search',  'class' => 'btn btn-primary btn-sm', 'onclick' => 'return Mandatory()'));

            echo $this->Form->button('Clear', array('id' => 'Clear', 'name' => 'Clear', 'value' => 'Clear', 'style' => 'margin-left:5px;', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return ClearFields()', 'type' => 'button'));

        echo $this->Form->end();
        ?>
            </div>
        </div>
        

    </div>
</div>
        <?php 
if (count($Chartreports) >= 0) {
?>
<div class="validationloader" style="display:none;"></div>


<div class="container-fluid">


    <div class="bs-example">
        <div id="vertical">
            <div id="top-pane">

                <div class="row" id="dashbhord-report" style="display:none">
                    <div class="col-md-12">
                        <div class="col-md-6" id="parent_linechartContainer">

                            <div class="col-md-12 panel" style="min-height: 445px;width: 100%;margin-top:10px;" >
                                <div class="dash-header">Over all</div>
                                <div id="linechartContainer"></div>
                                <div id="err_linechartContainer" class="no-results-found" style="display:none;" >
                                    No Results found
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6" id="parent_errorpiechartContainer">
                            <div class="col-md-12 panel" style="min-height: 445px;width: 100%;margin-top:10px;">
                                 <div class="dash-header">Error Distribution</div>
                                <div id="errorpiechartContainer"></div>
                                <div id="err_errorpiechartContainer" class="no-results-found" style="display:none;" >
                                    No Results found
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12" id="parent_errorbarchartContainer">

                            <div class="col-md-12 panel" style="height: auto;width: 100%;min-height: 445px;margin-top:10px;">
                                <div class="dash-header">Issues</div>

                                <div id="errorbarchartContainer"></div>

                                <div id="err_errorbarchartContainer" class="no-results-found" style="display:none;" >
                                    No Results found
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12" id="parent_errorcampaignlevelContainer">
                            <div class="col-md-12 panel" style="height: auto;width: 100%;min-height: 400px;margin-top:10px;">
                                <div class="dash-header"> Right First Time - Campaign Level</div>
                                <div id="errorcampaignlevelContainer" class="bs-example">

                                </div>
                                <div id="err_errorcampaignlevelContainer" class="no-results-found" style="display:none;" >
                                    No Results found
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
<!-- Widget Modal -->
<div class="modal fade setting-popup" id="widget-modal" aria-hidden="true" aria-labelledby="widget-modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="exampleModalTitle">Widget Settings</h4>
            </div>
            <div class="modal-body">
                <div class="widget-item" >
                    <span>Over All</span>
                    <label class="switch">
                        <input name="overall" id="overall" value="1" type="checkbox" <?php if($setting_overall > 0){ echo "checked"; } ?>>
                        <span class="slider round"></span>


                    </label>
                </div>
                <div class="widget-item" >
                    <span>Error Distribution</span>
                    <label class="switch">
                        <input type="checkbox"  name="error_dist" id="error_dist" value="1" <?php if($setting_error > 0){ echo "checked"; } ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="widget-item" >
                    <span>Issues</span>
                    <label class="switch">
                        <input type="checkbox"  name="issue" id="issue" value="1" <?php if($setting_issue > 0){ echo "checked"; } ?> >
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="widget-item">
                    <span>Right First Time</span>
                    <label class="switch">
                        <input type="checkbox"  name="rft" id="rft" value="1" <?php if($setting_rft > 0){ echo "checked"; } ?> >
                        <span class="slider round"></span>
                    </label>
                </div>
                <div>
                    <span id="saved_status"></span>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="Ajaxsetting();">Save</button>

            </div>
        </div>
    </div>
</div>
<!-- Widget Modal -->

<?php
}
echo $this->Form->end();

?>
<?php echo $this->Html->script('reportchart/canvasjs.min.js'); ?>



<style>
    #vertical {
        height: 750px;
        margin: 0 auto;
    }
    #top-pane  { background-color: rgba(60, 70, 80, 0.05); }
    #left-pane{padding-top:12px !important; background-color: #fff !important;}
    .pane-content {
        padding: 0 10px;
    }
    .lastrow label{position:relative !important;}
    .validationloader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 60px;
        height: 60px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin: 59px 0px 6px 630px;
        z-index: 9999;
        position: absolute;
    }
    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>

    function LineChartreports(chartres) {

        var chart = new CanvasJS.Chart("linechartContainer", {
            title: {
                text: ""
            },
            axisY: {
                title: "Data"
            },
            data: chartres

        });
        chart.render();

    }


    function pieErrorchartreports(chartres) {

        var chart = new CanvasJS.Chart("errorpiechartContainer", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: ""
            },
            subtitles: [{
                    text: "",
                    fontSize: 16
                }],
            data: [{
                    type: "pie",
                    indexLabelFontSize: 15,
                    radius: 170,
                    indexLabel: "{label} - {y}",
                    yValueFormatString: "###0.0'%'",
                    click: explodePie,
                    dataPoints: chartres

                }]
        });
        chart.render();
    }

    function explodePie(e) {
        for (var i = 0; i < e.dataSeries.dataPoints.length; i++) {
            //if(i !== e.dataPointIndex)
            //e.dataSeries.dataPoints[i].exploded = false;
        }
    }


    function Errorbarchart(chartres) {

        var chart = new CanvasJS.Chart("errorbarchartContainer", {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: ""
            },
            legend: {
                dockInsidePlotArea: true,
                verticalAlign: "center",
                horizontalAlign: "right",
            },
            dataPointWidth: 3,
            data: chartres
//            data: [{
//                    type: "column",
//                    showInLegend: true,
//                    yValueFormatString: "#,##0.## tonnes",
//                    name: "Target",
//                    dataPoints: chartres
//                }, {
//                    type: "column",
//                    name: "Achieved",
//                    showInLegend: true,
//                    yValueFormatString: "#,##0.## tonnes",
//                    dataPoints: chartres
//                }
//            ]
        });
        chart.render();

    }

</script>


<script type="text/javascript">

    function ClearFields()
    {
        $('#ProjectId').val('0');
        $('#month_from').val('');
        $('#month_to').val('');
    }


    function Mandatory()
    {
        $("#chart-results").hide();
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();

        var hour = today.getHours();
        var minute = today.getMinutes();
        var seconds = today.getSeconds();

        var todaydate = new Date();
        var dd = todaydate.getDate();
        var mm = todaydate.getMonth() + 1; //January is 0!
        var yyyy = todaydate.getFullYear();

        var hour = todaydate.getHours();
        var minute = todaydate.getMinutes();
        var seconds = todaydate.getSeconds();

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }
        if (hour < 10) {
            hour = '0' + hour
        }

        if (minute < 10) {
            minute = '0' + minute
        }

        if (seconds < 10) {
            seconds = '0' + seconds
        }
        today = dd + '-' + mm + '-' + yyyy;
        todaydate = yyyy + '-' + mm + '-' + dd;
        var time = hour + ':' + minute + ':' + seconds;

        if ($('#ProjectId').val() == 0) {
            alert('Select Project Name');
            $('#ProjectId').focus();
            return false;
        }


        if (($('#month_from').val() == ''))
        {
            alert('Select From date!');
            $('#month_from').focus();
            return false;
        }

        if (($('#month_from').val() == '') && ($('#month_to').val() != ''))
        {
            alert('Select From date!');
            $('#month_from').focus();
            return false;
        }

        var date = $('#month_from').val();
        var datearray = date.split("-");
        var month_from = datearray[2] + '-' + datearray[1] + '-' + datearray[0];

        $("#left-pane").show();
        $(".validationloader").show();
        $(".container-fluid").css("opacity", 0.5);

        setTimeout(function () {
            AjaxValidationstart();
        }, 500);


        return false;
    }

    function AjaxValidationstart() {

        var ProjectId = $('#ProjectId').val();
        var month_from = $('#month_from').val();
        var month_to = $('#month_to').val();
        var txt_td = "";


        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'productiondashboard', 'action' => 'getdashboardchartreports')); ?>",
            data: ({ProjectId: ProjectId, month_from: month_from, month_to: month_to}),
            dataType: 'text',
            async: false,
            success: function (result) {

                var results = JSON.parse(result);
                $("#dashbhord-report").show();

                // line chart
                if (results.linechart.status > 0) {
                    if (results.linechart.total > 0) {
                        $("#parent_linechartContainer").show();
                          $("#err_linechartContainer").hide();
                        LineChartreports(results.linechart.chartres);
                    } else {
                        $("#err_linechartContainer").show();
                    }
                } else {
                    $("#parent_linechartContainer").hide();
                }


                // pie-chart
                if (results.piechart.status > 0) {
                    if (results.piechart.total > 0) {
                        $("#parent_errorpiechartContainer").show();
                         $("#err_errorpiechartContainer").hide();
                        pieErrorchartreports(results.piechart.chartres);
                    } else {
                        $("#err_errorpiechartContainer").show();
                    }
                } else {
                    $("#parent_errorpiechartContainer").hide();
                }


                //bar chart 
                if (results.barchart.status > 0) {
                    if (results.barchart.total > 0) {
                        $("#parent_errorbarchartContainer").show();
                           $("#err_errorbarchartContainer").hide();
                        Errorbarchart(results.barchart.chartres);
                    } else {
                        $("#err_errorbarchartContainer").show();
                    }
                } else {
                    $("#parent_errorbarchartContainer").hide();
                }

                // campaign table
                if (results.campaigntab.status > 0) {
                    if (results.campaigntab.total > 0) {
                        $("#parent_errorcampaignlevelContainer").show();
                        $("#err_errorcampaignlevelContainer").hide();
                        $("#errorcampaignlevelContainer").html(results.campaigntab.table);
                    } else {
                        $("#err_errorcampaignlevelContainer").show();
                    }
                } else {
                    $("#parent_errorcampaignlevelContainer").hide();
                }


                $(".validationloader").hide();
                $(".container-fluid").css("opacity", '');
            }
        });


        return 1;

    }

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 58 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function isFromDate(str)
    {

        var fromTime = $("#" + str + "FromTime").val();
        var pieces = fromTime.split(':');
        var hour = pieces[0];
        var minute = pieces[1];
        var seconds = pieces[2];

        setDate = 'true';
        //Checks for mm/dd/yyyy format.
        if (hour != '' && minute != '') {

            if (hour < 0 || hour > 24)
                setDate = 'wrong';
            else if (minute < 0 || minute > 59)
                setDate = 'wrong';
            else if (seconds < 0 || seconds > 59)
                setDate = 'wrong';

            if (setDate == 'wrong')
            {
                $("#" + str + "FromTime").val('');
                alert('wrong time');
                $('#FromTime').focus();
            } else
            {
                actDate = hour + ':' + minute + ':' + seconds;
                if (str == '')
                    $("#FromTime").val(actDate);
                else
                    $("#" + str).val(actDate);
            }
        }

    }

    function isToDate(str)
    {
        var toTime = $("#" + str + "ToTime").val();
        var pieces = toTime.split(':');
        var hour = pieces[0];
        var minute = pieces[1];
        var seconds = pieces[2];

        setDate = 'true';
        //Checks for mm/dd/yyyy format.
        if (hour != '' && minute != '') {
            if (hour < 0 || hour > 24)
                setDate = 'wrong';
            else if (minute < 0 || minute > 59)
                setDate = 'wrong';
            else if (seconds < 0 || seconds > 59)
                setDate = 'wrong';

            if (setDate == 'wrong')
            {
                $("#" + str + "ToTime").val('');
                alert('wrong time');
                $('#ToTime').focus();
            } else
            {
                actDate = hour + ':' + minute + ':' + seconds;
                if (str == '')
                    $("#ToTime").val(actDate);
                else
                    $("#" + str).val(actDate);
            }
        }

    }
</script>
<style>
    .tab-tot{
        background-color: #dadada !important;
    }
    #charttable{
        height: 100%;
        width: 33%;
        float: left;
        margin-top: 10px;
    }
    .no-results-found{
        height: 61%;
        width: 89%;
        text-align: center;
        margin-top: 121px;
        color: red;
        font-size: 20px;
        position: absolute;
    }
    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        transition: opacity 500ms;
        visibility: hidden;
        opacity: 0;
    }
    .overlay:target {
        visibility: visible;
        opacity: 1;
    }
    .popup {
        margin: 150px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        width: 40%;
        position: relative;
        transition: all 5s ease-in-out;
    }
    .popup h2 {
        margin-top: 0;
        color: #333;
        font-family: Tahoma, Arial, sans-serif;
    }
    .popup .close {
        position: absolute;
        top: 20px;
        right: 30px;
        transition: all 200ms;
        font-size: 30px;
        font-weight: bold;
        text-decoration: none;
        color: #333;
    }
    .popup .close:hover {
        color: #fdc382;
    }
    .popup .content {
        max-height: 30%;
        overflow: auto;
    }
    .query_outerbdr {
        background: #fff none repeat scroll 0 0;
        border-radius: 5px;
        margin: 3px;
        padding: 6px;
    }
    .allocation_popuphgt {
        font-size: 12px;
        height: 157px;
        overflow: auto;
    }
    .white_content {
        background: #fdfdfd url("../img/popupbg.png") repeat-x scroll left top;
        border: 5px solid #fff;
        display: none;
        height: auto;
        left: 25%;
        padding: 16px;
        position: absolute;
        top: 25%;
        width: 50%;
        z-index: 1002;
    }
    #saved_status{
        color: green;
        font-size: 16px;
        padding-left: 97px;
    }
    .dash-header{
        font-size: 13px;
        margin: 10px 0px 10px 0px;
        font-weight: bold;
    }

</style>
<script>
    function Ajaxsetting() {
        var overall = 0;
        var error_dist = 0;
        var issue = 0;
        var rft = 0;
        if ($('#overall').is(":checked"))
        {
            var overall = 1;
        }
        if ($('#error_dist').is(":checked"))
        {
            var error_dist = 1;
        }
        if ($('#issue').is(":checked"))
        {
            var issue = 1;
        }

        if ($('#rft').is(":checked"))
        {
            var rft = 1;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'productiondashboard', 'action' => 'ajaxsetting')); ?>",
            data: ({overall: overall, error_dist: error_dist, issue: issue, rft: rft}),
            dataType: 'text',
            async: false,
            success: function (result) {
                $("#saved_status").show();
                $("#saved_status").text("Saved");
                $("#saved_status").fadeOut(3000);
            }
        });
    }

</script>

