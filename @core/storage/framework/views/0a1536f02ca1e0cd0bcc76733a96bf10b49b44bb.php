//Showing Progressbar
$(document).on('click','.view_results_btn',function(ev){
ev.preventDefault();
$('#total_vote').show();
$('.vote_item').hide();
$('.vote_progress_content').show();
$(this).hide();
$('.view_options_btn').show();
})


$(document).on('click','.view_options_btn',function(ev){
ev.preventDefault();
$('#total_vote').hide();
$('.vote_item').show();
$('.vote_progress_content').hide();
$(this).hide();
$('.view_results_btn').show();
})<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/components/frontend/showing-progressbar-and-details.blade.php ENDPATH**/ ?>