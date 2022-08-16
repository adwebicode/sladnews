
$(document).on('submit', '.custom-form-builder-form', function (e) {
e.preventDefault();
var btn = $('#contact_form_btn');
var form = $(this);
var formID = form.attr('id');
var msgContainer =  form.find('.error-message');
var formSelector = document.getElementById(formID);
var formData = new FormData(formSelector);
msgContainer.html('');
$.ajax({
url: "{{route('frontend.form.builder.custom.submit')}}",
type: "POST",
headers: {
'X-CSRF-TOKEN': "{{csrf_token()}}",
},
beforeSend:function (){
// form.find('.ajax-loading-wrap').addClass('show').removeClass('hide');
btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Submitting..")}}');
},
processData: false,
contentType: false,
data:formData,
success: function (data) {
form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
msgContainer.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
btn.text('{{__("Submit Message")}}');
},
error: function (data) {
form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
var errors = data.responseJSON.errors;
var markup = '<ul class="alert alert-danger">';
    $.each(errors,function (index,value){
    markup += '<li>'+value+'</li>';
    })
    markup += '</ul>';
msgContainer.html(markup);
btn.text('{{__("Submit Message")}}');
}
});
});