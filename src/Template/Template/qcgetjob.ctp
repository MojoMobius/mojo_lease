<link data-jsfiddle="common" rel="stylesheet" media="screen" href="webroot/dist/handsontable.css">
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="webroot/dist/pikaday/pikaday.css">
<script data-jsfiddle="common" src="webroot/dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="webroot/dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="webroot/dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="webroot/dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="webroot/dist/numbro/languages.js"></script>
<script data-jsfiddle="common" src="webroot/dist/handsontable.js"></script>
<script src="webroot/js/samples.js"></script>
<script src="webroot/js/highlight/highlight.pack.js"></script>
<link rel="stylesheet" media="screen" href="webroot/js/highlight/styles/github.css">
<link rel="stylesheet" href="webroot/css/font-awesome/css/font-awesome.min.css">
    <?php
        use Cake\Routing\Router;
        echo $this->Form->create('', array('class' => 'form-horizontal', 'id' => 'projectforms', 'name' => 'getjob'));
    ?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-top:10px;">
        <div class="container-fluid">
            <div class="panel panel-default formcontent">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h3 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration:none;">
                            <i class="more-less glyphicon glyphicon-plus"></i>
                            QC Validation
                        </a> 
                        <span class="buttongrp">    
                            <button type="submit" id="SubmitForm" style="margin-right:3px;" name='Submit' value="Submit" class="btn btn-primary btn-xs pull-right" >Reject</button> 
                            <button type="submit" id="SubmitForm" style="" name='Submit' value="Submit" class="btn btn-primary btn-xs pull-right" >Accept</button> 
                        </span>
                    </h3>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <span  class="form-horizontal" id="">        
                        <div class="col-md-4">
                            <div class="form-group" >
                                    <label for="inputEmail3" class="col-sm-6 control-label"><b><?php echo $moduleName; ?> Process:</b></label>
                                    <div class="col-sm-6 " style="padding-top: 3px;">
                                            Document ID: 789456
                                    </div>
                                    <div class="col-sm-6 " style="padding-top: 3px;">
                                            PDF Filename: yyy_financial_report.pdf
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"style="margin: 0px;" >
                                <label for="inputEmail3" class="col-sm-4 control-label"><b>Timer:</b></label>
                                <div class="col-sm-8" style="padding-top: 3px;padding-left: 15px;">
                                        <a href="#">
                                            <span class="badge" id='countdowns'>00:01:26</span>
                                        </a> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-6 control-label"><b>Status:</b></label>
                                <div class="col-sm-6" style="padding-top: 3px;">
                                         QC in Progress
                                </div>
                            </div>
                        </div>
                    </span>				
                    <div class="form-group">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>

    <div id="example" class="container-fluid" style="margin-bottom:-10px;">
        <div id="vertical">
            <div id="top-pane">
                <div id="horizontal" style="height: 100%; width: 100%;">
                    <div id="left-pane">
                        <div class="pane-content" >
                            <!-- Load pdf file starts -->
                            <div class="col-md-12">
                                <a>Click here to download Financial report excel</a>
                                <div class="pull-right" style="cursor:pointer;padding: 0 5px 5px;">
                                    <button class="btn btn-primary btn-xs " name='pdfPopUP' id='pdfPopUpa' onclick="PdfPopups();" type="button">Undock</button>
                                </div>
                            </div>
                            <div style="margin-top:10px;">
                                <embed id="frame" src="http://www.pdf995.com/samples/pdf.pdf" >
                            </div> 
                            <!-- Load pdf file ends-->
                        </div>
                    </div>
                    <div id="right-pane">
                        <div id="example111" style="padding: 10px">
                            
                            <div style="float: right;">
                                <img src="../webroot/img/back.png">
                                &nbsp;&nbsp;&nbsp;
                                <b>1</b>
                                &nbsp;&nbsp;&nbsp;
                                2
                                &nbsp;&nbsp;&nbsp;
                                3
                                &nbsp;&nbsp;&nbsp;
                                4
                                &nbsp;&nbsp;&nbsp;
                                <img src="../webroot/img/frd.png">
                            </div>
                            
                            <table>
                                <tr>
                                    <td>Page 1</td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Create PDF files quickly and easily!"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 2"></td>
                                    <td><input placeholder="Title" disabled value="Pdf995"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>                              
                                
                                
       
                                
                                <tr>
                                    <td>Page 2</td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>   
                                
                                
 
                                
                                
                                
                                <tr>
                                    <td>Page 3</td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>   
                                
                                

                                
                                
                                <tr>
                                    <td>Page 4</td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input placeholder="Level" disabled value="Level 1"></td>
                                    <td><input placeholder="Title" disabled value="Title 1"></td>
                                    <td>&nbsp;&nbsp;<img src="../webroot/img/comment.png"></td>
                                </tr>   
                                
                            </table>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
echo $this->Form->end();

?>
    <script>
        var myWindow = null;
        function onMyFrameLoad() {
            //('#loaded').val('loaded');
        }
        $(document).ready(function () {
            
            $("#vertical").kendoSplitter({
                orientation: "vertical",
                panes: [
                    {collapsible: false},
                    {collapsible: false, size: "100px"},
                    {collapsible: false, resizable: false, size: "100px"}
                ]
            });

            $("#horizontal").kendoSplitter({
                orientation: "horizontal",
                panes: [
                    {collapsible: true},
                    {collapsible: true},
                    {collapsible: true}
                ],
                expand: onExpandSplitter,
                resize: onResizeSplitter
            });

        });
        
        function onResizeSplitter(e) {
            
            var leftpaneSize = $('#left-pane').data('pane').size;
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller' => 'Getjobcore', 'action' => 'upddateLeftPaneSizeSession')); ?>",
                data: ({leftpaneSize: leftpaneSize}),
                dataType: 'text',
                async: true,
                success: function (result) {
                    loadHotWidth();
                }
            });
            
            
        }
        
        function onExpandSplitter() {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array('controller' => 'Getjobcore', 'action' => 'upddateUndockSession')); ?>",
                data: ({undocked: 'no'}),
                dataType: 'text',
                async: true,
                success: function (result) {

                }
            });
            if(myWindow)
                myWindow.close();
            
            loadHotWidth();
        }
            
        function displayTimeout() {
            iframe1 = document.getElementById('frame');
            if ($('#loaded').val() === 'loaded') {

            }
            else {

                var p = iframe1.parentNode;
                p.removeChild(iframe1);

                var div = document.createElement("iframe");

                div.setAttribute("id", "frame");
                div.setAttribute("style", 'width:100%; height:800px; overflow:hidden !important;');
                p.appendChild(div);
                var html = '<body>Loading takes longer than usual.<br> Please use Go button!</body>';
                div.src = 'data:text/html;charset=utf-8,' + encodeURI(html);
                p.appendChild(div);
                console.log('div.contentWindow =', div.contentWindow);
            }



        }
        //setTimeout(displayTimeout, 8000);

        function LoadPDF(file)
        {
            document.getElementById('frame').src = file;

        }
        
        function loadHotWidth() {
            hot.updateSettings({
                width: '100%'
            });
        }
    </script>
</div>
<script>
    
    function PdfPopup()
    {
        var splitterElement = $("#horizontal"),getPane = function (index) {
            index = Number(index);
            var panes = splitterElement.children(".k-pane");
            if(!isNaN(index) && index < panes.length) {
                return panes[index];
            }
        };

        var splitter = splitterElement.data("kendoSplitter");
        var pane = getPane('0');
        splitter.toggle(pane, $(pane).width() <= 0);
                    
        
        var file = $("#status option:selected").text();
        myWindow = window.open("", "myWindow", "width=500, height=500");
        myWindow.document.write('<iframe id="pdfframe"  src="' + file + '" style="width:100%; height:100%; overflow:hidden !important;"></iframe>');
        
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Getjobcore', 'action' => 'upddateUndockSession')); ?>",
            data: ({undocked: 'yes'}),
            dataType: 'text',
            async: true,
            success: function (result) {
                
            }
        });
        
        loadHotWidth();
    }
    
    function valicateQuery()
    {
        if ($("#query").val() == '')
        {
            alert('Enter Query');
            $("#query").focus();
            return false;
        }
        query = $("#query").val();
        InputEntyId = $("#ProductionEntity").val();

        var result = new Array();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller' => 'Getjobcore', 'action' => 'ajaxqueryposing')); ?>",
            data: ({query: query, InputEntyId: InputEntyId}),
            dataType: 'text',
            async: false,
            success: function (result) {
                document.getElementById('successMessage').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('successMessage').style.display = 'none';
                    $("#query").val(result);
                }, 2000);
            }
        });
    }
    
 
    function LoadPDF(file)
    {
        document.getElementById('frame').src = file;
        $("body", myWindow.document).find('#pdfframe').attr('src', file);
    }

    ipValidatorRegexp = /^(?:\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b|null)$/;
    emailValidator = function (value, callback) {
        setTimeout(function () {
            if (value === '') {
            callback(true);
            }
            else if (/.+@.+/.test(value)) {
                callback(true);
            }
            else {
                callback(false);
            }
        }, 1000);
    };

    UrlRegexp = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    urlValidator = function (value, callback) {
        setTimeout(function () {
            if (UrlRegexp.test(value)) {
                callback(true);
            }
            else {
                callback(false);
            }
        }, 100);
    };

    var AlphbetOnlyReg = /^[a-zA-Z\s]+$/;
    AlphabetOnlyValidator = function (value, callback) {
        setTimeout(function () {
            if (AlphbetOnlyReg.test(value) == false) {
                callback(false);
            }
            else {
                callback(true);
            }
        }, 100);
    };
    var AlphaNumericOnlyReg = /^[a-zA-Z\s]+$/;
    AlphaNumericOnlyValidator = function (value, callback) {
        setTimeout(function () {
            if (AlphaNumericOnlyReg.test(value) == false) {
                callback(false);
            }
            else {
                callback(true);
            }
        }, 100);
    };
    var NumbersOnlyReg = /^[a-zA-Z\s]+$/;
    NumbersOnlyValidator = function (value, callback) {
        setTimeout(function () {
            if (NumbersOnlyReg.test(value) == false) {
                callback(false);
            }
            else {
                callback(true);
            }
        }, 100);
    };
    function getCustomRenderer() {
        return function (instance, td, row, col, prop, value, cellProperties) {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            td.hidden = true;
        }
    }
    function calculateSize() {
        var offset;


        offset = Handsontable.Dom.offset(example1);
        availableWidth = Handsontable.Dom.innerWidth(document.body) - offset.left + window.scrollX;
        availableHeight = Handsontable.Dom.innerHeight(document.body) - offset.top + window.scrollY;

        example1.style.width = availableWidth + 'px';
        example1.style.height = availableHeight + 'px';

    }
    
</script>
<style>
    .clt, .clt ul, .clt li {
     position: relative;
}

.clt ul {
    list-style: none;
    padding-left: 32px;
}

.clt li::before, .clt li::after {
    content: "";
    position: absolute;
    left: -12px;
}

.clt li::before {
    border-top: 1px solid #000;
    top: 9px;
    width: 8px;
    height: 0;
}

.clt li::after {
    border-left: 1px solid #000;
    height: 100%;
    width: 0px;
    top: 2px;
}

.clt ul > li:last-child::after {
    height: 8px;
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

        #vertical {
            height: 750px;
            margin: 0 auto;
        }
        #left-pane,#right-pane  { background-color: rgba(60, 70, 80, 0.05); }
        .pane-content {
            padding: 0 10px;
        }
        
        td {padding: 7px !important;}
    </style>
 