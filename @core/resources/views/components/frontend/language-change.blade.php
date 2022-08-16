$(document).on('change','#langchange',function(e){
    $.ajax({
        url : "{{route('frontend.langchange')}}",
        type: "GET",
    data:{
     'lang' : $(this).val()
    },
    success:function (data) {
        location.reload();
    }
    })
});