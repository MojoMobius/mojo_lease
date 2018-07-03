<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <title></title>

  <!--
  Loading Handsontable (full distribution that includes all dependencies apart from jQuery)
  -->
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.handsontable.full.js"></script>
  <link rel="stylesheet" media="screen" href="js/jquery.handsontable.full.css">

  <!--
  Loading demo dependencies. They are used here only to enhance the examples on this page
  -->
  <link rel="stylesheet" media="screen" href="css/samples.css">
  <script src="js/samples.js"></script>
  <script src="js/highlight/highlight.pack.js"></script>
  <link rel="stylesheet" media="screen" href="js/highlight/styles/github.css">


</head>

<body>


<div id="container">

  <div class="rowLayout">
    <div class="descLayout">
      <div class="pad">
      </div>
    </div>
  </div>

  <div class="rowLayout">
    <div class="descLayout">
      <div class="pad bottomSpace850">


        <p>Note: this is a mockup..</p>

        <p>
          <button name="load">Load</button>
          <button name="save">Save</button>
          <label><input type="checkbox" name="autosave" checked="checked" autocomplete="off"> Autosave</label>
        </p>

        <pre id="example1console" class="console">Click "Load" to load data from server</pre>

        <div id="example1"></div>

      </div>
    </div>

    <div class="codeLayout">
      <div class="pad">
        <script>
          var $container = $("#example1");
          var $console = $("#example1console");
          var $parent = $container.parent();
          var autosaveNotification;
          $container.handsontable({
            startRows: 3,
            startCols: 3,
            rowHeaders: true,
            colHeaders: true,
            minSpareRows: 1,
            contextMenu: true,
            manualColumnResize: true,
            columnSorting: true,
            onChange: function (change, source) {
              if (source === 'loadData') {
                return; //don't save this change
              }
              if ($parent.find('input[name=autosave]').is(':checked')) {
                clearTimeout(autosaveNotification);
                $.ajax({
                  url: "save.php",
                  dataType: "json",
                  type: "POST",
                  data: change, //contains changed cells' data
                  complete: function (data) {
                    $console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
                    autosaveNotification = setTimeout(function () {
                      $console.text('Changes will be autosaved');
                    }, 1000);
                  }
                });
              }
            }
          });
          var handsontable = $container.data('handsontable');

          $parent.find('button[name=load]').click(function () {
            $.ajax({
              url: "load.php",
              dataType: 'json',
              type: 'GET',
              success: function (res) {
                handsontable.loadData(res.data);
                $console.text('Data loaded');
              }
            });
          });

          $parent.find('button[name=save]').click(function () {
            $.ajax({
              url: "save.json",
              data: {"data": handsontable.getData()}, //returns all cells' data
              dataType: 'json',
              type: 'POST',
              success: function (res) {
                if (res.result === 'ok') {
                  $console.text('Data saved');
                }
                else {
                  $console.text('Save error');
                }
              },
              error: function () {
                $console.text('Save error. POST method is not allowed on GitHub Pages. Run this example on your own server to see the success message.');
              }
            });
          });

          $parent.find('input[name=autosave]').click(function () {
            if ($(this).is(':checked')) {
              $console.text('Changes will be autosaved');
            }
            else {
              $console.text('Changes will not be autosaved');
            }
          });
        </script>
      </div>
    </div>
  </div>

  <div class="rowLayout">
    <div class="descLayout">

    </div>
  </div>
</div>
</body>
</html>