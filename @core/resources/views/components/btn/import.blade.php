$(document).on('click','.import',function () {
    $(this).addClass("disabled")
    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Importing..")}}');
});