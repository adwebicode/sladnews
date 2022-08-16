@include('frontend.partials.support')
<div class="supportbar-area-wrapper style-02">
    <div class="container custom-container-01">
        <div class="row">
            <div class="col-12 col-sm-5 col-md-4 col-lg-2">
                <div class="logo-wrapper">
                    @if(get_static_option('site_frontend_dark_mode') == 'on')
                    <a href="{{url('/')}}">
                     {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                    </a>
                     @else
                        <a href="{{url('/')}}">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-12 col-sm-7 col-md-8 col-lg-10">
                <div class="content">
                    <div class="add-box">
                        <a href="{{get_static_option('home_page_one_banner_url')}}">
                               {!! render_image_markup_by_attachment_id(get_static_option('home_page_one_banner')) !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="nav-main-wrap-for-custom-style-01-v-02">
    <nav class="navbar navbar-area navbar-expand-lg has-topbar nav-style-01 custom-style-01 dark-bg-01 v-02">
        <div class="container nav-container custom-container-01">
            <div class="responsive-mobile-menu">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">

                <ul class="navbar-nav">
                    {!! render_frontend_menu($primary_menu) !!}
                </ul>
            </div>
            <div class="nav-right-content v-02">
                <div class="support-bar-search-box style-02">
                    <form action="{{ route('frontend.blog.search') }}">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                            <button type="submit" class="search-btn">
                                <i class="las la-search icon"></i>
                            </button>
                        </div>
                            @include('frontend.partials.pages-portion.navbars.autocomplete-markup')
                    </form>
                </div>

                <div class="hamburger-menu-wrapper right-side">
                    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
                </div>
            </div>
        </div>
    </nav>
</div>

