    $('.load-ajax-data').hide();
    $(document).on('click','.category-btn',function(e){
    
        e.preventDefault();
        let el = $(this);
        var id = $(this).data('id');

        $(this).addClass('active').siblings().removeClass('active');

        $.ajax({
        url: "{{ route('frontend.get.blogs.by.ajax') }}",
        type: 'get',
        data:{id:id},

            beforeSend: function (){
              $('.load-ajax-data').show();
            },
              success: function(data){
                 $('.load-ajax-data').hide();
                 $('.home-page-ajax-news-show').html(data.markup);
            }

      });


});
