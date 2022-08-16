<?php


namespace App\PageBuilder\Addons\Blog;

use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\Image;
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

class BlogMiscThree extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-misc-03.png';
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
                'name' => 'heading_text_one_' . $lang->slug,
                'label' => __('Heading Text One'),
                'value' => $widget_saved_values['heading_text_one_' . $lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'heading_text_two_' . $lang->slug,
                'label' => __('Heading Text Two'),
                'value' => $widget_saved_values['heading_text_two_' . $lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'heading_text_three_' . $lang->slug,
                'label' => __('Heading Text Three'),
                'value' => $widget_saved_values['heading_text_three_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $top_categories_blogs = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $trendy_categories_blogs = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $right_blog = Blog::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

        $output .= NiceSelect::get([
            'name' => 'top_categories',
            'label' => __('Select First Story Blogs Category'),
            'placeholder' => __('Select Blog'),
            'options' => $top_categories_blogs,
            'value' => $widget_saved_values['top_categories'] ?? null,
            'info' => __('you can select your top categories blogs or leave it empty')
        ]);

        $output .= Text::get([
            'name' => 'first_items',
            'label' => __('First Story Item'),
            'value' => $widget_saved_values['first_items'] ?? null,
            'info' => __('select how many item you want to show')
        ]);

        $output .= NiceSelect::get([

            'name' => 'trendy_categories',
            'label' => __('Select Second Story Blogs Category'),
            'placeholder' => __('Select Category'),
            'options' => $trendy_categories_blogs,
            'value' => $widget_saved_values['trendy_categories'] ?? null,
            'info' => __('you can select your top categories blogs or leave it empty')
        ]);

        $output .= Text::get([
            'name' => 'second_items',
            'label' => __('Second Story Item'),
            'value' => $widget_saved_values['second_items'] ?? null,
            'info' => __('select how many item you want to show')
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

        $output .= NiceSelect::get([
            'name' => 'right_blog',
            'label' => __('Select Second Story Blogs Category'),
            'placeholder' => __('Right Blog'),
            'options' => $right_blog,
            'value' => $widget_saved_values['right_blog'] ?? null,
            'info' => __('select right side single post')
        ]);

        $output .= Image::get([
            'name' => 'banner_image',
            'label' => __('Banner Image'),
            'value' => $widget_saved_values['banner_image'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'banner_url',
            'label' => __('Banner URL'),
            'value' => $widget_saved_values['banner_url'] ?? null,
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
        $heading_text_one = SanitizeInput::esc_html($this->setting_item('heading_text_one_' . $current_lang));
        $heading_text_two = SanitizeInput::esc_html($this->setting_item('heading_text_two_' . $current_lang));
        $heading_text_three = SanitizeInput::esc_html($this->setting_item('heading_text_three_' . $current_lang));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $first_items = SanitizeInput::esc_html($this->setting_item('first_items'));
        $second_items = SanitizeInput::esc_html($this->setting_item('second_items'));

        $rb = SanitizeInput::esc_html($this->setting_item('right_blog'));
        $banner_image = SanitizeInput::esc_html($this->setting_item('banner_image'));
        $banner_url = SanitizeInput::esc_html($this->setting_item('banner_url'));

        $right_blog = self::right_blog($rb);
        $banner = self::banner($banner_image,$banner_url);


        // ============================== Top Stories ====================================
        
  $TopStories = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$top_categories,$order_by,$order,$second_items) {        
        $TopStories = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author','status');

        if (!empty($top_categories)){
            $TopStories->whereJsonContains('category_id', $top_categories);
        }
        $TopStories =$TopStories->orderBy($order_by,$order);
        if(!empty($second_items)){
            $TopStories = $TopStories->take($second_items)->get();
        }else{
            $TopStories = $TopStories->take(5)->get();
        }
        
        return $TopStories;
        
  });        



        $topList = '';
        $top_single_item = '';

        foreach ($TopStories as $key => $item) {
            $image = render_image_markup_by_attachment_id($item->image,'border-radius-none','thumb');
            $image_2 = render_image_markup_by_attachment_id($item->image,'border-radius-none');
            $route = route('frontend.blog.single', $item->slug);
            $title = SanitizeInput::esc_html($item->getTranslation('title', $current_lang));
            $date = date('M d, Y', strtotime($item->created_at));
            $created_by = $item->author ?? __('Anonymous');
            $created_by_url = get_blog_created_user($item);


            if (!is_null($item) && $key == 2) {
                $top_single_item .= self::top_single_item($image_2,$route, $title, $created_by, $created_by_url,$date);
            } else {


  $topList .= <<<TOPLIST
       <li class="single-blog-post-item slick-item">
            <div class="thumb">
             <a href="$route">{$image}</a>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list style-02 light">
                        <li class="post-meta-item">
                            <a href="{$created_by_url}">
                                <span class="text author">{$created_by}</span>
                            </a>
                        </li>
                        <li class="post-meta-item date">
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
                <h4 class="title">
                    <a href="{$route}">{$title} </a>
                </h4>
            </div>
        </li>

TOPLIST;

  }
}


// ==================================================================== Trendy ========================================================================

 $TrendyStories = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$trendy_categories,$order_by,$order,$first_items) {        
        $TrendyStories = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author','status');

        if (!empty($trendy_categories)){
            $TrendyStories->whereJsonContains('category_id', $trendy_categories);
        }
        $TrendyStories =$TrendyStories->orderBy($order_by,$order);
        if(!empty($first_items)){
            $TrendyStories = $TrendyStories->take($first_items)->get();
        }else{
            $TrendyStories = $TrendyStories->take(5)->get();
        }
        
        return $TrendyStories;
        
 });

        $trendyList = '';
        $trendy_single_item = '';


        foreach ($TrendyStories as $Tkey => $Titem) {

            $Timage = render_image_markup_by_attachment_id($Titem->image, 'border-radius-none','thumb');
            $Timage2 = render_image_markup_by_attachment_id($Titem->image, 'border-radius-none');
            $Troute = route('frontend.blog.single', $Titem->slug);
            $Ttitle = SanitizeInput::esc_html($Titem->getTranslation('title', $current_lang));
            $Tdate = date('M d, Y', strtotime($Titem->created_at));
            $Tcreated_by = $Titem->author ?? __('Anonymous');
            $Tcreated_by_url = get_blog_created_user($Titem);

            if (!is_null($Titem) && $Tkey == 0) {
                $trendy_single_item .= self::trendy_single_item($Timage2,$route, $title, $created_by, $created_by_url,$date);
            } else {


$trendyList .= <<<TRENDYLIST
   <li class="single-blog-post-item slick-item">
            <div class="thumb">
              <a href="$Troute">{$Timage}</a>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list style-02 light">
                        <li class="post-meta-item">
                            <a href="{$Tcreated_by_url}">
                                <span class="text author">{$Tcreated_by}</span>
                            </a>
                        </li>
                        <li class="post-meta-item date">
                            <span class="text">{$Tdate}</span>
                        </li>
                    </ul>
                </div>
                <h4 class="title">
                    <a href="{$Troute}">{$Ttitle}</a>
                </h4>
            </div>
        </li>
TRENDYLIST;
    }
}


return <<<PARENT

    <div class="top-and-trendy-stories-area-wrapper index-05" data-padding-top="{$padding_top}" data-padding-bottom="$padding_bottom">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-lg-12 col-xl-8">
                    <div class="row">
                        <!-- trendy stories start -->
                        <div class="col-lg-6">
                            <div class="section-title-style-04">
                                <h3 class="title">{$heading_text_one}</h3>
                            </div>

                                {$trendy_single_item}
                            <!-- trendy stories list start -->
                            <div class="top-stories-and-trendy-stories-list ex">
                                <ul class="recent-blog-post-style-02 light">
                        
                                    {$trendyList}
                                </ul>
                            
                            </div>
                            <!-- trendy stories list start -->
                        </div>
                        <!-- trendy stories start -->

                        <!-- top stories start -->
                        <div class="col-lg-6">
                            <div class="section-title-style-04">
                                <h3 class="title">{$heading_text_two}</h3>
                            </div>

                                {$top_single_item}
                            <!-- top stories list start -->
                            <div class="top-stories-and-trendy-stories-list ex">
                                <ul class="recent-blog-post-style-02 light">  
                                    {$topList}
                                </ul>
                                
                            </div>
                            <!-- top stories list end -->
                        </div>
                        <!-- top stories start -->
                    </div>
                </div>
                <div class="col-sm-7 col-md-7 col-lg-6 col-xl-4">
                    <div class="widget-area-wrapper">
                        <div class="widget">
                            <h4 class="widget-title style-04">{$heading_text_three}</h4>
                            {$right_blog}
                        </div>
                        <div class="widget">
                            <div class="adds style-01">
                                {$banner}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

PARENT;

}

    private function top_single_item( $image2,$route, $title, $created_by, $created_by_url,$date){
   return <<<TOPSINGLEITEM
        <div class="blog-grid-style-03 small">
            <div class="img-box">
               <a href="$route">{$image2}</a>
            </div>
            <div class="content">
                <h4 class="title">
                    <a href="{$route}" tabindex="0">{$title}</a>
                </h4>
                <div class="post-meta">
                    <ul class="post-meta-list style-02">
                        <li class="post-meta-item">
                            <a href="{$created_by_url}" tabindex="0">
                                <span class="text">{$created_by}</span>
                            </a>
                        </li>
                        <li class="post-meta-item date">
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

TOPSINGLEITEM;

    }

    private function trendy_single_item( $Timage2,$route, $title, $created_by, $created_by_url,$date)
    {

   return <<<TRENDYSINGLEITEM
        <div class="blog-grid-style-03 small">
                <div class="img-box">
                 <a href="$route">{$Timage2}</a>
                </div>
                <div class="content">
                    <h4 class="title">
                        <a href="{$route}" tabindex="0">{$title}</a>
                    </h4>
                    <div class="post-meta">
                        <ul class="post-meta-list style-02">
                            <li class="post-meta-item">
                                <a href="{$created_by_url}" tabindex="0">
                                    <span class="text">{$created_by}</span>
                                </a>
                            </li>
                            <li class="post-meta-item date">
                                <span class="text">{$date}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

TRENDYSINGLEITEM;


    }

    private function right_blog($rb){

        if(!empty($rb)){
            $blog = Blog::select('id','title','image','slug','created_at','category_id','author','status')->where('id',$rb)->where('status','publish')->first();
        }else{
            $blog = Blog::select('id','title','image','slug','created_at','category_id','author','status')->where('status','publish')->first();
        }

        $author = $blog->author ?? __('Anonymouse');
        $author_url = get_blog_created_user($blog);
        $date = date('M d, Y', strtotime($blog->created_at));
        $current_lang = LanguageHelper::user_lang_slug();
        $title = SanitizeInput::esc_html($blog->getTranslation('title', $current_lang));
        $route = route('frontend.blog.single',$blog->slug);
        $description = Str::words(SanitizeInput::esc_html($blog->getTranslation('blog_content', $current_lang)),38);
        $read_more_text = __('read more');

        $category_markup = '';
        foreach ($blog->category_id as $cat){
            $category = $cat->getTranslation('title',$current_lang);
            $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
            $category_markup.=' <li class="post-meta-item"><a href="'.$category_route.'"><span class="text">'.$category.'</span></a></li>';
        }

 return  <<<RIGHTBLOG
 <div class="blog-grid-style-03 large slick-item">
        <div class="content padding-top-0">
            <div class="post-meta">
                <ul class="post-meta-list style-02">
                    <li class="post-meta-item">
                        <a href="{$author_url}">
                            <span class="text author">{$author}</span>
                        </a>
                    </li>
                    <li class="post-meta-item date">
                        <span class="text">{$date}</span>
                    </li>
                  {$category_markup}
                </ul>
            </div>
            <h4 class="title font-size-32">
                <a href="{$route}">{$title}</a>
            </h4>
            <p class="info">{$description} </p>
            <div class="btn-wrapper mt-3">
                <a href="{$route}" class="btn-default ul-btn">{$read_more_text}</a>
            </div>
        </div>
    </div>

RIGHTBLOG;

    }

    private function banner($banner_image,$banner_url){

        $image = render_image_markup_by_attachment_id($banner_image);
        return  <<<RIGHTBANNER
     <a href="{$banner_url}">
       {$image}
    </a>
RIGHTBANNER;

    }

    public function addon_title()
    {
        return __('Blog Misc: 03');
    }
}