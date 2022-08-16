//Home Addvertisement Click Store
$(document).on('click','.home_advertisement',function(){
let id = $('#add_id').val();
$.ajax({
url : "<?php echo e(route('frontend.home.advertisement.click.store')); ?>",
type: "GET",
data:{
'id':id
},
success:function (data){
console.log(data);
}
})
});

//Home Addvertisement Click Store
$(document).on('mouseover','.home_advertisement',function(){
let id = $('#add_id').val();
$.ajax({
url : "<?php echo e(route('frontend.home.advertisement.impression.store')); ?>",
type: "GET",
data:{
'id':id
},
success:function (data){
console.log(data);
}
})
});<?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/components/frontend/advertisement-script.blade.php ENDPATH**/ ?>