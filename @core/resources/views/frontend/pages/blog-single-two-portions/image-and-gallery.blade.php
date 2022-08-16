<div class="main-img-box">
    @if(!empty($blog_post->image_gallery))
     <div class="main-img-box v-02">
        <div class="global-slick-init slick-space-adjust " data-infinite="true" data-slidesToShow="1"
             data-slidesToScroll="1" data-speed="500" data-cssEase="linear" data-arrows="false" data-dots="false"
             data-prevArrow='<div class="prev-arrow"><i class="las la-arrow-left"></i></div>'
             data-nextArrow='<div class="prev-arrow"><i class="las la-arrow-left"></i></div>'
             data-autoplaySpeed="2000"
             data-responsive='[{"breakpoint": 768,"settings": { "arrows": false,"centerMode": true,"centerPadding": "40px", "slidesToShow": 1}},{"breakpoint": 480, "settings": { "arrows": false, "centerMode": true, "centerPadding": "0px","slidesToShow": 1} }]'
        >
            @php
                $images = explode("|", $blog_post->image_gallery);
                $video_url = $blog_post->video_url;
            @endphp

            @foreach($images as $img)
                <div class="single-gallery-image single-featured">
                    <div class="tag-box">
                        {!! render_image_markup_by_attachment_id($img,'','large') !!}
                    </div>
                </div>
            @endforeach
        </div>
  </div>

    @else
        <div class="img-bg v-02 lazy" {!! render_background_image_markup_by_attachment_id($blog_post->image,'','large') !!}></div>
    @endif

</div>

