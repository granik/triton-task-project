$(document).ready(function() {
  
  $('table#event-desc').on('click', 'button.btn-edit',  function(e) {
      location.href = $(this).data('eventid') + "/edit/" + $(this).data('target');
  });
  
   $(document.body).on('click', 'button.edit-data',  function(e) {
      location.href = $(this).data('target') + "/edit";
   });
   
   
   $('.gridViewAjaxLink').on('click', function(e) {
       e.preventDefault();
       var _href = $(this).data('href');
       $.ajax({
           url: _href,
           dataType: 'text'
       }).done(function() {
//           $.pjax.reload({container: '#info-fields', timeout : false});
           location.reload();
       });
   });


});