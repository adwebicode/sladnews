<div class="nav-right-content d-flex justify-content-between">
    <ul>
        <li class="account">
            <form class="search-form-small navbar-search">
                @csrf
                <div class="form-group">
                    <input type="text" name="search" autocomplete="off" id="search" class="form-control"
                           placeholder="Search...">
                    <input type="hidden" name="get_style_search_type_id" id="get_style_search_type_id" value="">
                </div>


                <button type="button" id="search_icon_up">
                    <i class="las la-search icon"></i>
                </button>


                <div class="ajax-preloader-wrap"></div>

            </form>
        </li>

        <a href="{{ route('frontend.blog.get.search') }}" data-url="{{ route('frontend.blog.get.search') }}"
           id="tag_view_all"><i class="las la-external-link-alt"></i> </a>
        <li class="account">
            <div id="show-autocomplete" style="display:none;">
                <ul class="autocomplete-warp"></ul>
            </div>
        </li>

    </ul>
</div>