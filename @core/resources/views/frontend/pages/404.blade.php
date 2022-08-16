@php $user_select_lang_slug = isset($user_select_lang_slug ) ?: get_user_lang(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{get_static_option('site_'.get_user_lang().'_title')}} - {{get_static_option('site_'.get_user_lang().'_tag_line')}}</title>
           {!! render_site_meta() !!}

    <!-- favicon -->
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/frontend/css/main-style.css')}}">
     <link rel="stylesheet" href="{{asset('assets/frontend/css/helpers.css')}}">

</head>

<body>

<div class="error-area-wrapper" data-padding-top="100" data-padding-bottom="100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="img-box">
                    <h4 class="title">{{get_static_option('error_404_page_'.get_user_lang().'_subtitle')}}</h4>
                  {!! render_image_markup_by_attachment_id(get_static_option('error_image'),'','',false) !!}
                </div>
                <div class="content">
                    <div class="btn-wrapper">
                        <a href="{{route('homepage')}}" class="btn-default">{{get_static_option('error_404_page_'.get_user_lang().'_button_text')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>