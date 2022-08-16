

    $('#frontend_darkmode').on('click', function(){
        var el = $(this)
        var mode = el.data('mode');

        $.ajax({
            type:'GET',
            url:  '{{ route("frontend.dark.mode.toggle") }}',
            data:{mode:mode},
            success: function(data){
                location.reload();
            },error: function(){
            }
        });
    });
