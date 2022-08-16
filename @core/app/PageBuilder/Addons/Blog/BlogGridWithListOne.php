<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Cache;

class BlogGridWithListOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-grid-with-list-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = LanguageHelper::all_languages();

        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'heading_text_one_'.$lang->slug,
                'label' => __('Heading Text One'),
                'value' => $widget_saved_values['heading_text_one_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'heading_text_two_'.$lang->slug,
                'label' => __('Heading Text Two'),
                'value' => $widget_saved_values['heading_text_two_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

          $top_categories_blogs = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
          $trendy_categories_blogs = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

            $output .= NiceSelect::get([
                'name' => 'top_categories',
                'label' => __('Select First Story Blogs Category'),
                'placeholder' => __('Select Blog'),
                'options' => $top_categories_blogs,
                'value' => $widget_saved_values['top_categories'] ?? null,
                'info' => __('you can select your top categories blogs or leave it empty')
            ]);

            $output .= NiceSelect::get([

                'name' => 'trendy_categories',
                'label' => __('Select Second Story Blogs Category'),
                'placeholder' => __('Select Category'),
                'options' => $trendy_categories_blogs,
                'value' => $widget_saved_values['trendy_categories'] ?? null,
                'info' => __('you can select your top categories blogs or leave it empty')
            ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);


        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 110,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 110,
            'max' => 200,
        ]);

        // add padding option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $top_categories = $this->setting_item('top_categories') ?? null;
        $trendy_categories = $this->setting_item('trendy_categories') ?? null;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $heading_text_one = SanitizeInput::esc_html($this->setting_item('heading_text_one_'.$current_lang));
        $heading_text_two = SanitizeInput::esc_html($this->setting_item('heading_text_two_'.$current_lang));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


    // ============================== Top Stories ====================================
    
   $TopStories = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$top_categories,$order_by,$order) {
        $TopStories = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');

        if (!empty($top_categories)){
            $TopStories->whereJsonContains('category_id', $top_categories);
        }
         $TopStories =$TopStories->orderBy($order_by,$order)->take(4)->get();
        
        return $TopStories;
    });

        $topList = '';
        $top_single_item = '';


        foreach ($TopStories as $key=> $item) {
            $image = render_image_markup_by_attachment_id($item->image);
            $list_top_image = render_background_image_markup_by_attachment_id($item->image);
            $route = route('frontend.blog.single', $item->slug);
            $title = Str::limit(SanitizeInput::esc_html($item->getTranslation('title', $current_lang)),55);
            $date = date('M d, Y', strtotime($item->created_at));
            $created_by = $item->author ?? __('Anonymous');

            //author image
            $author = NULL;
            if(!isNull($item->user_id)){
                $author = optional($item->user);
            }else if(!isNull($item->admin_id)){
                $author = optional($item->admin);
            }else{
                $author = optional($item->admin);
            }
            $user_image = render_image_markup_by_attachment_id($author->image, 'image');

            $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'),'image');
            $created_by_image = $user_image ? $user_image : $avatar_image;

            $category_markup = '';
            $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
            foreach ($item->category_id as $key2=> $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<a class="category-style-01 ' . $colors[$key2 % count($colors)] . '" href="' . $category_route . '">' . $category . '</a>';
            }

            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single', $item->slug);
            $comment_count = BlogComment::where('blog_id', $item->id)->count();
            $comment_condition_check = $comment_count == 0 ? 0 : $comment_count;


            if (!is_null($item) && $key == 2) {
                $top_single_item .= self::top_single_item($category_markup, $image, $title, $route, $date, $created_by, $created_by_image, $created_by_url, $comment_condition_check);
            } else {


$topList .= <<<TOPLIST
   <li class="single-blog-post-item">
        <div class="thumb">
            <div class="background-img lazy border-radius-10"data-width="150" data-height="150" {$list_top_image}></div>
        </div>
        <div class="content">
            <h4 class="title font-size-20">
                <a href="{$route}">{$title}</a>
            </h4>
            <div class="post-meta">
                <ul class="post-meta-list">
                    <li class="post-meta-item date">
                        <i class="lar la-clock icon"></i>
                        <span class="text">{$date}</span>
                    </li>
                </ul>
            </div>
        </div>
      </li>
TOPLIST;

   }
}


// ==================================================================== Trendy ========================================================================

   $TrendyStories = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$trendy_categories,$order_by,$order) {

            $TrendyStories = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');;

            if (!empty($trendy_categories)) {
                $TrendyStories->whereJsonContains('category_id', $trendy_categories);
            }
            $TrendyStories = $TrendyStories->orderBy($order_by, $order)->take(4)->get();
            
            return $TrendyStories;
});

            $trendyList = '';
            $trendy_single_item = '';


            foreach ($TrendyStories as $Tkey=> $Titem) {

                $Timage = render_image_markup_by_attachment_id($Titem->image);
                $Timage_list_bg = render_background_image_markup_by_attachment_id($Titem->image);
                $Troute = route('frontend.blog.single', $Titem->slug);
                $Ttitle =  Str::limit(SanitizeInput::esc_html($Titem->getTranslation('title', $current_lang)),55);
                $Tdate = date('M d, Y', strtotime($Titem->created_at));
                $Tcreated_by = $Titem->author ?? __('Anonymous');

                //author image
                $Tauthor = NULL;
                if(!isNull($Titem->user_id)){
                    $Tauthor = optional($Titem->user);
                }else if(!isNull($item->admin_id)){
                    $Tauthor = optional($Titem->admin);
                }else{
                    $Tauthor = optional($Titem->admin);
                }
                $Tuser_image = render_image_markup_by_attachment_id($Tauthor->image, 'image');

                $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'),'image');
                $Tcreated_by_image = $Tuser_image ? $Tuser_image : $avatar_image;

                $Tcategory_markup = '';
                $Tcolors = ['bg-color-a', 'bg-color-c', 'bg-color-b', 'bg-color-g', 'bg-color-e'];
                foreach ($Titem->category_id as $Ckey=> $cat) {
                    $Tcategory = $cat->getTranslation('title', $current_lang);
                    $Tcategory_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                    $Tcategory_markup .= '<a class="category-style-01 ' . $Tcolors[$Ckey % count($Tcolors)] . '" href="' . $Tcategory_route . '">' . $Tcategory . '</a>';
                }

                if ($Titem->created_by === 'user') {
                    $user_id = $Titem->user_id;
                } else {
                    $user_id = $Titem->admin_id;
                }

                $Tcreated_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $Titem->created_by, 'id' => $user_id]) : route('frontend.blog.single', $Titem->slug);
                $Tcomment_count = BlogComment::where('blog_id', $Titem->id)->count();
                $Tcomment_condition_check = $Tcomment_count == 0 ? 0 : $Tcomment_count;


                if (!is_null($Titem) && $Tkey ==  0) {
                    $trendy_single_item .= self::trendy_single_item($Tcategory_markup, $Timage, $Ttitle, $Troute, $Tdate, $Tcreated_by, $Tcreated_by_image, $Tcreated_by_url, $Tcomment_condition_check);
                } else {


 $trendyList .= <<<TRENDYLIST

   <li class="single-blog-post-item">
            <div class="thumb">
              <div class="background-img lazy border-radius-10" data-height="150" data-width="150" {$Timage_list_bg}></div>
            </div>
            <div class="content">
                <h4 class="title font-size-20">
                    <a href="{$Troute}">{$Ttitle}</a>
                </h4>
                <div class="post-meta">
                    <ul class="post-meta-list">
                        <li class="post-meta-item date">
                            <i class="lar la-clock icon"></i>
                            <span class="text">{$Tdate}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>

TRENDYLIST;

    }
}


 return <<<HTML
   <div class="top-and-trendy-stories-area-wrapper index-01" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
    <div class="row">
        <!-- top stories start -->
        <div class="col-lg-6">
            <div class="section-title-style-01">
                <h3 class="title">{$heading_text_one}</h3>
            </div>

            {$top_single_item}
            <!-- top stories list start -->
            <div class="top-stories-and-trendy-stories-list">
                <ul class="recent-blog-post-style-01">
                    {$topList}
                </ul>
               
            </div>
            <!-- top stories list end -->
        </div>
        <!-- top stories start -->

        <!-- trendy stories start -->
        <div class="col-lg-6">
            <div class="section-title-style-01">
                <h3 class="title">{$heading_text_two}</h3>
            </div>

            {$trendy_single_item}
            <!-- trendy stories list start -->
            <div class="top-stories-and-trendy-stories-list">
                <ul class="recent-blog-post-style-01">
                    {$trendyList}
                </ul>
              
            </div>
            <!-- trendy stories list start -->
        </div>
        <!-- trendy stories start -->
    </div>
</div>

HTML;


 }

   private function top_single_item($category_markup,$image,$title,$route,$date,$created_by,$created_by_image,$created_by_url,$comment_condition_check)
    {

        return <<<TOPSINGLEITEM

    <div class="blog-grid-style-02">
    <div class="img-box">
        <div class="tag-box right">
           {$category_markup}
        </div>
       {$image}
    </div>
    <div class="content">
        <div class="post-meta">
            <ul class="post-meta-list">
                <li class="post-meta-item">
                    <a href="{$created_by_url}">
                      {$created_by_image}
                        <span class="text">{$created_by}</span>
                    </a>
                </li>
                <li class="post-meta-item date">
                    <i class="lar la-clock icon"></i>
                    <span class="text">{$date}</span>
                </li>
                <li class="post-meta-item">
                    <a href="#">
                        <i class="lar la-comments icon"></i>
                        <span class="text">{$comment_condition_check}</span>
                    </a>
                </li>
            </ul>
        </div>
        <h4 class="title">
            <a href="{$route}">{$title}</a>
        </h4>
    </div>
</div>

TOPSINGLEITEM;

}

   private function trendy_single_item($Tcategory_markup, $Timage, $Ttitle, $Troute, $Tdate, $Tcreated_by, $Tcreated_by_image, $Tcreated_by_url, $Tcomment_condition_check)
    {

return <<<TRENDYSINGLEITEM
    <div class="blog-grid-style-02">
        <div class="img-box">
            <div class="tag-box right">
               {$Tcategory_markup}
            </div>
          {$Timage}
        </div>
        <div class="content">
            <div class="post-meta">
                <ul class="post-meta-list">
                    <li class="post-meta-item">
                        <a href="{$Tcreated_by_url}">
                           {$Tcreated_by_image}
                            <span class="text">{$Tcreated_by}</span>
                        </a>
                    </li>
                    <li class="post-meta-item date">
                        <i class="lar la-clock icon"></i>
                        <span class="text">{$Tdate}</span>
                    </li>
                    <li class="post-meta-item">
                        <a href="#">
                            <i class="lar la-comments icon"></i>
                            <span class="text">{$Tcomment_condition_check}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <h4 class="title">
                <a href="{$Troute}">{$Ttitle}</a>
            </h4>
        </div>
    </div>

TRENDYSINGLEITEM;



 }

    public function addon_title()
    {
        return __('Blog Grid with list : 01');
    }
}