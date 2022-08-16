@include('frontend.partials.support')

<!-- nav area start -->
<div class="nav-main-wrap-for-custom-style-02">
    <nav class="navbar navbar-area navbar-expand-lg has-topbar nav-style-01 custom-style-02 dark-bg-01">
        <div class="container nav-container custom-container-01">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="{{url('/')}}" class="logo">
                        {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                    </a>
                </div>
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
                <div class="search position-relative">
                    <div class="search-open">
                        <i class="las la-search icon"></i>
                    </div>

                    <div class="search-bar">
                        <form class="menu-search-form" action="{{ route('frontend.blog.search') }}">
                            <div class="search-close"> <i class="las la-times"></i> </div>
                            <input class="item-search" name="search" id="search" type="text" placeholder="Search Here.....">
                            <button type="submit"> {{__('Search')}}</button>

                            @include('frontend.partials.pages-portion.navbars.autocomplete-markup')

                        </form>
                    </div>
                </div>

                <div class="hamburger-menu-wrapper right-side">
                    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- navbar area end -->