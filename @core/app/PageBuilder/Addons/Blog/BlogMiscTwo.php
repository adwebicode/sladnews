<?php


namespace App\PageBuilder\Addons\Blog;
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
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;
use PHPUnit\Util\Test;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Cache;

class BlogMiscTwo extends \App\PageBuilder\PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-misc-02.png';
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
                'name' => 'right_heading_'.$lang->slug,
                'label' => __('Left Side Blog Heading'),
                'value' => $widget_saved_values['right_heading_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $blogCategories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $blogRightVideos = Blog::usingLocale(LanguageHelper::default_slug())->where('video_url', '!=', NULL)->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();



        $output .= NiceSelect::get([
            'name' => 'right_categories',
            'label' => __('Left Blog Categories'),
            'placeholder' => __('Select Left Blog Categories'),
            'options' => $blogCategories,
            'value' => $widget_saved_values['right_categories'] ?? null,
            'info' => __('you can select categories or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'right_blog_items',
            'label' => __('Left Blog Items'),
            'value' => $widget_saved_values['right_blog_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);
        
        
        $output .= NiceSelect::get([
            'name' => 'categories',
            'label' => __('Middle Blog Categories'),
            'placeholder' => __('Select Left Blog Categories'),
            'options' => $blogCategories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select categories or leave it empty')
        ]);


        $output .= Number::get([
            'name' => 'left_blog_items',
            'label' => __('Middle Blog Items'),
            'value' => $widget_saved_values['left_blog_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);
        
        
        $output .= NiceSelect::get([
            'name' => 'right_blog_video',
            'label' => __('Right Blog Video'),
            'placeholder' => __('Select Right Blog Video'),
            'options' => $blogRightVideos,
            'value' => $widget_saved_values['right_blog_video'] ?? null,
            'info' => __('you can select right side blog video here')
        ]);



        $output .= Image::get([
            'name' => 'banner_image',
            'label' => __('Banner Image'),
            'value' => $widget_saved_values['banner_image'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'banner_url',
            'label' => __('Banner Url'),
            'value' => $widget_saved_values['banner_url'] ?? null,
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
            'value' => $widget_saved_values['padding_top'] ?? 100,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 100,
            'max' => 200,
        ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render(): string
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $heading_text = SanitizeInput::esc_html($this->setting_item('right_heading_'.$current_lang));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $left_blog_items = SanitizeInput::esc_html($this->setting_item('left_blog_items'));

        $banner_image = SanitizeInput::esc_html($this->setting_item('banner_image'));
        $banner_url = SanitizeInput::esc_html($this->setting_item('banner_url'));

        $right_blog_video = $this->setting_item('right_blog_video') ?? [];
        $blogsbyCategories = $this->setting_item('categories') ?? [];

        $right_blogs = $this->setting_item('right_categories') ?? [];
        $right_blog_items = $this->setting_item('right_blog_items') ?? [];


        $leftContent = self::LeftBlogs($blogsbyCategories,$left_blog_items,$order_by,$order);
        $rightVideoContent = self::RightVideo($right_blog_video);
        $bannerContent = self::Banner($banner_image,$banner_url);
        $rightContent = self::RightBlogs($right_blogs,$right_blog_items,$order_by,$order);
        

     

return <<<PARENT
    <div class="header-area-wrapper index-05" data-padding-top="{$padding_top}" data-padding-bottom="$padding_bottom">
        <div class="header-area index-04">
            <div class="container custom-container-01">
                <div class="row">
                   <div class="order-xl-1 order-md-2 col-md-6 order-lg-3 col-lg-12 col-xl-3">
                        <div class="section-title-style-04">
                            <h3 class="title">{$heading_text}</h3>
                        </div>
                        <div class="news-headline-wrapper one light">
                            <ul class="news-headline-list style-01">
                                {$rightContent}
                            </ul>
                        </div>
                    </div>
                    {$leftContent}
                    <div class="col-sm-6 order-3 order-md-3 col-md-6 order-lg-2 col-lg-4 col-xl-3 ex">
                        <div class="index-05-heading-sidebar">
                              {$rightVideoContent}
                            <div class="ads-banner-box">
                               {$bannerContent}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
PARENT;
}




private function LeftBlogs($blogsbyCategories,$left_blog_items,$order_by,$order){

    $current_lang = GlobalLanguage::user_lang_slug();
   
    
   $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$blogsbyCategories,$order_by,$order,$left_blog_items) {    
        $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');
        
        if (!empty($blogsbyCategories)){
            $blogs->whereJsonContains('category_id', $blogsbyCategories);
        }
        $blogs =$blogs->orderBy($order_by,$order);
        if(!empty($left_blog_items)){
            $blogs = $blogs->take($left_blog_items)->get();
        }else{
            $blogs = $blogs->take(2)->get();
        }
        
        return $blogs;
    
  });    
  
  


    $list_markup = '';
    $single_item = '';
    foreach ($blogs as $data=> $item) {
        $image = render_image_markup_by_attachment_id($item->image);
        $bg_image = render_background_image_markup_by_attachment_id($item->image);
        $title = Str::words($item->title ?? __('No Title'), 8);
        $route = route('frontend.blog.single', $item->slug) ?? '';
        $date = date('M d, Y', strtotime($item->created_at));
        $created_by = $item->author ?? __('Anonymous');
        $created_by_url = get_blog_created_user($item);

        $category_markup = '';
        $colors = ['bg-color-e', 'bg-color-a', 'bg-color-b', 'bg-color-g', 'bg-color-c'];
        foreach ($item->category_id as $key => $cat) {
            $category = $cat->getTranslation('title', $current_lang);
            $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
            $category_markup .= '<a class="category-style-01 v-02 ' . $colors[$key % count($colors)] . '" href="' . $category_route . '">' . $category . '</a>';
        }


        if ($data == 0) {
            $single_item .= self::LeftBlogSingleItem($category_markup, $bg_image, $title, $route, $created_by_url, $created_by, $date);
        } else {


  $list_markup .= <<<LEFTBLOGSLIST
  <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
    <div class="image-blog-large three index-05-two">
        <div class="image-blog-style-01 v-02">
            <div class="img-box">
                <div class="tag-box position-top-left">
                  {$category_markup}
                </div>
             <a href="$route">{$image}</a>
                <span class="overlay"></span>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list style-02">
                        <li class="post-meta-item date">
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
                <h3 class="title font-size-20">
                    <a href="{$route}">{$title}</a>
                </h3>
            </div>
        </div>
    </div>
</div>
LEFTBLOGSLIST;
        }
    }

return <<<LEFT

    <div class="order-1 order-md-1 order-lg-1 col-lg-8 col-xl-6">
         {$single_item}
        <div class="row internal">
            {$list_markup}
        </div>
    </div>

LEFT;
}
private function LeftBlogSingleItem($category_markup,$bg_image,$title,$route,$created_by_url,$created_by,$date)
{

return<<<SINGLE
    <div class="image-blog-large three index-05-one">
        <div class="image-blog-style-01 v-02 light">
            <div class="img-box">
                <div class="tag-box position-top-left">
                   {$category_markup}
                </div>
                <div class="background-img lazy" {$bg_image} data-height="575"></div>
                <span class="overlay"></span>
            </div>
            <div class="content">
                <h3 class="title font-size-42">
                    <a href="{$route}">{$title}</a>
                </h3>
                <div class="post-meta">
                    <ul class="post-meta-list style-02">
                        <li class="post-meta-item">
                            <a href="{$created_by_url}">
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
    </div>

SINGLE;


}
private function RightVideo($right_blog_video){

    $current_lang = GlobalLanguage::user_lang_slug();
    
 
    
    if(!empty($right_blog_video)){
        $video = Blog::select('id','title','image','slug','created_at','category_id','video_url')->where('id',$right_blog_video)->where('status','publish')->first();
    }else{
        $video = Blog::where('status','publish')->where('video_url', '!=', NULL)->first();
    }
     if(is_null($video)){
        return '';
    }
    
    $title = Str::words($video->getTranslation('title',$current_lang),9);
    $blog_url = route('frontend.blog.single',$video->slug);
    $video_url = $video->video_url ?? '';
    $date = date('M d, Y',strtotime($video->created_at));
    $mage = render_image_markup_by_attachment_id($video->image);

    $static_icon = asset('assets/frontend/img/videos/play-icon/06.svg');

    $category_markup = '';
    $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
    foreach ($video->category_id as $key=> $cat) {
        $category = $cat->getTranslation('title', $current_lang);
        $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
        $category_markup .= '<a class="category-style-01 v-02  '.$colors[$key % count($colors)].'" href="' . $category_route . '"><span class="text">' . $category . '</span></a>';
    }


return  <<<VIDEO

  <div class="image-blog-large three index-05-two">
        <div class="image-blog-style-01 v-02 video-blog">
            <div class="img-box">
                <div class="tag-box position-top-left">
                  {$category_markup}
                </div>
                     {$mage}
                <a href="{$video_url}"
                    class="play-icon icon-style-01 magnific-inst mfp-iframe hide-x">
                    <img src="{$static_icon}" alt="">
                </a>
                <span class="overlay"></span>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list style-02">
                        <li class="post-meta-item date">
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
                <h3 class="title font-size-20">
                    <a href="{$blog_url}">{$title}</a>
                </h3>
            </div>
        </div>
    </div>

VIDEO;


 }
private function Banner($banner_image,$banner_url){

$image = render_image_markup_by_attachment_id($banner_image);
   return  <<<RIGHTBANNER
     <a href="{$banner_url}">
       {$image}
    </a>
RIGHTBANNER;

}

private function RightBlogs($right_blogs,$right_blog_items,$order_by,$order){

    $current_lang = GlobalLanguage::user_lang_slug();

    $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');;

    if (!empty($right_blogs)){
      $blogs = $blogs->whereJsonContains('category_id', $right_blogs);
    }
    $blogs =$blogs->orderBy($order_by,$order);
    if(!empty($right_blog_items)){
        $blogs = $blogs->take($right_blog_items)->get();
    }else{
        $blogs = $blogs->take(4)->get();
    }
    

    $markup = '';
    foreach ($blogs as $item) {

        $title = Str::words($item->getTranslation('title',$current_lang) ?? __('No Title'), 15);
        $route = route('frontend.blog.single', $item->slug) ?? '';
        $date = date('M d, Y', strtotime($item->created_at));
        $created_by = $item->author ?? __('Anonymous');
        $created_by_url = get_blog_created_user($item);


$markup.= <<<VIDEO
     <li class="news-heading-item">
        <h3 class="title">
            <a href="{$route}">{$title}</a>
        </h3>
        <div class="post-meta">
            <ul class="post-meta-list style-02 light">
                <li class="post-meta-item">
                    <a href="{$created_by_url}">
                        <span class="text author ">{$created_by}</span>
                    </a>
                </li>
                <li class="post-meta-item date">
                    <span class="text">{$date}</span>
                </li>
            </ul>
        </div>
    </li>
VIDEO;
 }

    return $markup;

}

public function addon_title()
{
    return __('Blog Misc : 02');
}


}