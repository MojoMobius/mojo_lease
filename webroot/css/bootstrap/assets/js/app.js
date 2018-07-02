$(document).ready(function () {
    baseinfodivcount = 1;
  $("#MySplitter").splitter();
    $("#MySplitter").trigger("resize", [ 320 ]);
     //$(".spliticon").click("resize", [ 0 ]);
  $('#myCarousel').carousel({
     interval: 40000
  });


});
// Script for bottom side flip canvas starts
$(document).ready(function() {
   $('#document-tag, #page-tag').iptOffCanvas({
     baseClass: 'offcanvas',
     type: 'bottom' // top, right, bottom, left.
   });
 });  
// Script for bottom side flip canvas ends

// Script for tooltip
$(document).ready(function(){
   //$('[data-toggle="tooltip"]').tooltip();   
});
// Script Ends for tooltip


$('.edit').click(function(){
 $(this).hide();
 $('.box').addClass('editable');
 $('.text').attr('contenteditable', 'true');  
 $('.save').show();
});

$('.save').click(function(){
 $(this).hide();
 $('.box').removeClass('editable');
 $('.text').removeAttr('contenteditable');
 $('.edit').show();
});
$('.edit1').click(function(){
 $(this).hide();
 $('.box1').addClass('editable');
 $('.text1').attr('contenteditable', 'true');  
 $('.save1').show();
});

$('.save1').click(function(){
 $(this).hide();
 $('.box1').removeClass('editable');
 $('.text1').removeAttr('contenteditable');
 $('.edit1').show();
});


// Script for Add/Remove Starts
$('.multi-field-wrapper').each(function() {
   var $wrapper = $('.multi-fields', this);
   $(".add-field", $(this)).click(function(e) {
       $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
   });
   $('.multi-field .remove-field', $wrapper).click(function() {
       if ($('.multi-field', $wrapper).length > 1)
           $(this).parent('.multi-field').remove();
   });
});
// Script for Add/Remove Ends

// Script for enhsplitter Starts
jQuery(function ($) {           
           //$('#splitter-block').enhsplitter();
           $('#splitter-block').enhsplitter({
             handle: 'bar', 
             position: 400, 
             leftMinSize: 0, 
             fixed: false,
             onDrag: function() {
               // triggering bootstrap media queires manually
               let contWidth = $('.form-responsive').width();
               
               if(contWidth < 900 && contWidth > 640) {
                 $('.form-responsive .form-title, .form-responsive .form-status').addClass('col-md-12');
                 $('.form-responsive .form-text').addClass('col-md-6').removeClass('col-md-12');
               }
               else if(contWidth < 640 ) {
                $('.form-responsive .form-title, .form-responsive .form-status').addClass('col-md-12');
                $('.form-responsive .form-text').addClass('col-md-12').removeClass('col-md-6');
               }
               else if(contWidth > 900) {
                $('.form-responsive .form-title, .form-responsive .form-status').removeClass('col-md-12');
                $('.form-responsive .form-text').removeClass('col-md-6 col-md-12');
               }
               
               
             }
            });
       });
       
// Script for enhsplitter Ends