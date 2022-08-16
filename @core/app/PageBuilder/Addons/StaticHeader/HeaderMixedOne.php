<?php


namespace App\PageBuilder\Addons\StaticHeader;
use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Cache;

class HeaderMixedOne extends \App\PageBuilder\PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'header/header-mixed-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $blogs = Blog::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $right_blogCategories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $blogLeftVideo = Blog::usingLocale(LanguageHelper::default_slug())->where('video_url', '!=', NULL)->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

        $output .= NiceSelect::get([
            'name' => 'left_blogs',
            'multiple' => true,
            'label' => __('Left Bar Blogs'),
            'placeholder' => __('Select Leftbar Blogs'),
            'options' => $blogs,
            'value' => $widget_saved_values['left_blogs'] ?? null,
            'info' => __('you can select leftbar blogs or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'left_items',
            'label' => __('Left Items'),
            'value' => $widget_saved_values['left_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);

        $output .= NiceSelect::get([
            'name' => 'left_blog_video',
            'label' => __('Left Blog Video'),
            'placeholder' => __('Select Left Blog Video'),
            'options' => $blogLeftVideo,
            'value' => $widget_saved_values['left_blog_video'] ?? null,
            'info' => __('you can select leftbar blog video')
        ]);


        $output .= NiceSelect::get([
            'name' => 'center_single_blog',
            'label' => __('Centre Single Blog'),
            'placeholder' => __('Centre Single Blog'),
            'options' => $blogs,
            'value' => $widget_saved_values['center_single_blog'] ?? null,
            'info' => __('you can select center blog ')
        ]);


        $output .= NiceSelect::get([
            'name' => 'right_blog_categories',
            'multiple' => true,
            'label' => __('Right Bar Blog Category'),
            'placeholder' => __('Right Bar Blog Category'),
            'options' => $right_blogCategories,
            'value' => $widget_saved_values['right_blog_categories'] ?? null,
            'info' => __('you can select right bar blog category or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'right_items',
            'label' => __('Right Items'),
            'value' => $widget_saved_values['right_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);

        $output .= Image::get([
            'name' => 'rightbar_banner',
            'label' => __('Right Bar Banner'),
            'value' => $widget_saved_values['rightbar_banner'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'rightbar_banner_url',
            'label' => __('Right Bar Banner URL'),
            'value' => $widget_saved_values['rightbar_banner_url'] ?? null,
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

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render(): string
    {
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $left_items = SanitizeInput::esc_html($this->setting_item('left_items'));
        $right_items = SanitizeInput::esc_html($this->setting_item('right_items'));

        $left_blogs = $this->setting_item('left_blogs') ?? [];
        $left_blog_video = $this->setting_item('left_blog_video') ?? [];
        $center_single_blog = $this->setting_item('center_single_blog') ?? [];
        $right_blog_categories = $this->setting_item('right_blog_categories') ?? [];
        $rightbar_banner = $this->setting_item('rightbar_banner') ?? [];
        $rightbar_banner_url = $this->setting_item('rightbar_banner_url') ?? [];

        $leftContent = self::leftBarBlogs($left_blogs,$order_by,$order,$left_items);
        $leftVideoContent = self::leftBarBlogVideo($left_blog_video);
        $centerBlogContent = self::centerBlog($center_single_blog);
        $rightContent = self::rightBarBlogs($right_blog_categories,$order_by,$order,$right_items);
        $rightBannerContent = self::rightBarBanner($rightbar_banner,$rightbar_banner_url);




return <<<PARENT
    <div class="header-area-wrapper" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="header-area index-04">
            <div class="container custom-container-01">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="news-headline-wrapper one index-04 left">
                            <ul class="news-headline-list style-01">
                                {$leftContent}
                            </ul>

                                {$leftVideoContent}
                        </div>
                    </div>
                    <div class="col-lg-6">
                       {$centerBlogContent}
                    </div>
                    <div class="col-lg-3">
                        <div class="index-04-heading-sidebar">
                            <div class="header-part-recent-post-wrapper">
                                <ul class="recent-blog-post-style-02">
                                    {$rightContent}
                                </ul>
                            </div>
                            <div class="ads-banner-box">
                               {$rightBannerContent}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
PARENT;
}


private function leftBarBlogs($left_blogs,$order_by,$order,$left_items){
    $current_lang = GlobalLanguage::user_lang_slug();
    
    
 $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($left_blogs,$order_by,$order,$left_items) {    
    $blogs = Blog::select('id','title','image','slug','created_at','category_id','author')->whereIn('id',$left_blogs)->where('status','publish')->orderBy($order_by,$order);

    if(!empty($left_items)){
        $blogs = $blogs->take($left_items)->get();
    }else{
        $blogs = $blogs->take(3)->get();
    }
    
    return $blogs;
    
 });

    $markup = '';
    foreach ($blogs as $item) {

        $route = route('frontend.blog.single', $item->slug);
        $title = $item->getTranslation('title', $current_lang);
        $created_by = SanitizeInput::esc_html($item->author ?? __('Anonymous'));
        $date = date('M d, Y', strtotime($item->created_at));

        if ($item->created_by === 'user') {
            $user_id = $item->user_id;
        } else {
            $user_id = $item->admin_id;
        }

        $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single', $item->slug);


        $markup.= <<<LEFT

     <li class="news-heading-item">
        <h3 class="title">
            <a href="{$route}">{$title}</a>
        </h3>
        <div class="post-meta">
            <ul class="post-meta-list style-02">
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
    </li>

LEFT;

  }

    return $markup;
}
private function leftBarBlogVideo($left_blog_video){
    

    if(!empty($left_blog_video)){
        $blogVideo = Blog::select('id','title','image','slug','created_at','category_id','author','video_url')->where('id',$left_blog_video)->where('status','publish')->first();
        $image = render_background_image_markup_by_attachment_id($blogVideo->image);
        $video_url = $blogVideo->video_url;
        $static_icon = 'assets/frontend/img/videos/play-icon/02.svg';
    }

    return <<<LEFTVIDEO

       <div class="image-blog-style-01">
            <div class="img-box video-blog">
              <div class="background-img lazy" {$image} data-height="370">
               
            </div>
                <a href="{$video_url}"
                    class="play-icon icon-style-01 magnific-inst mfp-iframe">
                    <img src="{$static_icon}" alt="">
                </a>
            </div>
        </div>

LEFTVIDEO;


}
private function centerBlog($center_single_blog){
    
    $current_lang = LanguageHelper::user_lang_slug();
    $centerBlog = Blog::select('id','title','image','slug','created_at','category_id','author')->where('id',$center_single_blog)->where('status','publish')->first();

    $bg_image = render_background_image_markup_by_attachment_id($centerBlog->image);
    $route = route('frontend.blog.single', $centerBlog->slug);
    $title = Str::words($centerBlog->getTranslation('title', $current_lang), 15);
    $description = Str::words(SanitizeInput::esc_html($centerBlog->getTranslation('blog_content', $current_lang)),65);
    $created_by = SanitizeInput::esc_html($centerBlog->author ?? __('Anonymous'));
    $date = date('M d, Y', strtotime($centerBlog->created_at));

    if ($centerBlog->created_by === 'user') {
        $user_id = $centerBlog->user_id;
    } else {
        $user_id = $centerBlog->admin_id;
    }

    $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $centerBlog->created_by, 'id' => $user_id]) : route('frontend.blog.single', $centerBlog->slug);
    $category_markup = '';
    foreach ($centerBlog->category_id as $cat) {
        $category = $cat->getTranslation('title', $current_lang);
        $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
        $category_markup .= ' <a href="' . $category_route . '"><span class="text">' . $category . '</span></a>';
    }


return <<<CENTERBLOG
   <div class="blog-grid-style-03 large">
        <div class="img-box">
           <a href="$route"> <div class="background-img lazy"{$bg_image} data-height="580"></div></a>
        </div>
        <div class="content">
            <div class="post-meta">
                <ul class="post-meta-list style-02">
                    <li class="post-meta-item">
                        <a href="{$created_by_url}">
                            <span class="text author">{$created_by}</span>
                        </a>
                    </li>
                    <li class="post-meta-item date">
                        <span class="text">{$date}</span>
                    </li>
                    <li class="post-meta-item">
                       {$category_markup}
                    </li>
                </ul>
            </div>
            <h4 class="title">
                <a href="{$route}">{$title}</a>
                <p class="info">{$description}</p>
            </h4>
        </div>
    </div>

CENTERBLOG;


}
private function rightBarBlogs($right_blog_categories,$order_by,$order,$right_items){

    $current_lang = GlobalLanguage::user_lang_slug();
    
    
$blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($right_blog_categories,$order_by,$order,$right_items,$current_lang) {

    $blogs = Blog::select('id','title','image','slug','created_at','category_id','author')->usingLocale($current_lang)->query();

    if (!empty($right_blog_categories)){
        $blogs->whereJsonContains('category_id', $right_blog_categories);
    }
    $blogs =$blogs->orderBy($order_by,$order);
    if(!empty($right_items)){
        $blogs = $blogs->take($right_items)->get();
    }else{
        $blogs = $blogs->take(3)->get();
    }
    
    return $blogs;
    
});

    $markup = '';
    foreach ($blogs as $item) {

        $route = route('frontend.blog.single', $item->slug);
        $image = render_image_markup_by_attachment_id($item->image,'','thumb');
        $title = Str::words($item->getTranslation('title', $current_lang), 9);

        $category_markup = '';
        foreach ($item->category_id as $cat) {
            $category = $cat->getTranslation('title', $current_lang);
            $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
            $category_markup .= ' <a href="' . $category_route . '" class="category-style-01">' . $category . '</a>';
        }

$markup.= <<<RIGHBLOGS

      <li class="single-blog-post-item">
        <div class="thumb video-blog">
           <a href="$route">{$image}</a>
        </div>
        <div class="content">
            {$category_markup}
            <h4 class="title">
                <a href="{$route}">{$title} </a>
            </h4>
        </div>
    </li>

RIGHBLOGS;
 }

    return $markup;
}
private function rightBarBanner($rightbar_banner,$rightbar_banner_url){
        $banner_image = render_image_markup_by_attachment_id($rightbar_banner);
    return <<<RIGHTBANNER
         <a href="{$rightbar_banner_url}">
           {$banner_image}
        </a>
RIGHTBANNER;


}

public function addon_title()
{
    return __('Header Mixed: 01');
}


}