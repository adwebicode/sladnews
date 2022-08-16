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

class BlogMiscOne extends \App\PageBuilder\PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-misc-01.png';
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
                'name' => 'video_heading_title_'.$lang->slug,
                'label' => __('Video Heading Title'),
                'value' => $widget_saved_values['video_heading_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'blog_heading_title_'.$lang->slug,
                'label' => __('Blog Heading Title'),
                'value' => $widget_saved_values['blog_heading_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'social_heading_title_'.$lang->slug,
                'label' => __('Social Heading Title'),
                'value' => $widget_saved_values['social_heading_title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'category_heading_title_'.$lang->slug,
                'label' => __('Category Heading Text'),
                'value' => $widget_saved_values['category_heading_title_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $MiddleblogCategories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();
        $blogLeftVideos = Blog::usingLocale(LanguageHelper::default_slug())->where('video_url', '!=', NULL)->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();


        $output .= NiceSelect::get([
            'multiple'=> true,
            'name' => 'left_blog_videos',
            'label' => __('Left Blog Video'),
            'placeholder' => __('Select Left Blog Video'),
            'options' => $blogLeftVideos,
            'value' => $widget_saved_values['left_blog_videos'] ?? null,
            'info' => __('you can select left side blog videos here')
        ]);

        $output .= Number::get([
            'name' => 'left_video_items',
            'label' => __('Left Video Items'),
            'value' => $widget_saved_values['left_video_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);

        $output .= NiceSelect::get([
            'name' => 'categories',
            'label' => __('Middle Blog Categories'),
            'placeholder' => __('Select Middle Blog Categories'),
            'options' => $MiddleblogCategories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select categories or leave it empty')
        ]);

        $output .= Number::get([
            'name' => 'middle_blog_items',
            'label' => __('Middle Blog Items'),
            'value' => $widget_saved_values['middle_blog_items'] ?? null,
            'info' => __('enter how many item you want to show '),
        ]);

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
            'info' => __('enter how many category item you want to show '),
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

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'social_section',
            'fields' => [
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'icon',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Url')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'follower_text',
                    'label' => __('Follower Text')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'follower_number',
                    'label' => __('Follower Number')
                ],
            ]
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

        $video_heading_title = SanitizeInput::esc_html($this->setting_item('video_heading_title_'.$current_lang));
        $blog_heading_title = SanitizeInput::esc_html($this->setting_item('blog_heading_title_'.$current_lang));
        $social_heading_title = SanitizeInput::esc_html($this->setting_item('social_heading_title_'.$current_lang));
        $category_heading_title = SanitizeInput::esc_html($this->setting_item('category_heading_title_'.$current_lang));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $video_items = SanitizeInput::esc_html($this->setting_item('left_video_items'));
        $blog_items = SanitizeInput::esc_html($this->setting_item('middle_blog_items'));

        $banner_image = SanitizeInput::esc_html($this->setting_item('banner_image'));
        $banner_url = SanitizeInput::esc_html($this->setting_item('banner_url'));

        $left_blog_videos = $this->setting_item('left_blog_videos') ?? [];
        $blogsbyCategories = $this->setting_item('categories') ?? [];
        $onlyCategoryShowItems = $this->setting_item('category_items') ?? [];

        $repeater_data = $this->setting_item('social_section');


        $leftContent = self::LeftVideos($left_blog_videos,$video_items);
        $centerBlogContent = self::CenterBlogs($blogsbyCategories,$blog_items,$order_by,$order);
        $bannerContent = self::Banner($banner_image,$banner_url);
        $socialContent = self::SocialMedia($repeater_data);
        $categoryContent = self::Categories($onlyCategoryShowItems);


    return <<<PARENT
 <div class="mixed-area-wrapper-0 index-04">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-7 col-xl-8">
                            <!-- popular videos area start -->
                            <div class="popular-videos-area-wrapper index-04" data-padding-top="{$padding_top}"
                                data-padding-bottom="$padding_bottom">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="section-title-style-03">
                                            <h3 class="title">{$video_heading_title}</h3>
                                        </div>

                                        <div class="popular-video-index-04-slider-inst slick-main">
                                            {$leftContent}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- popular videos area end -->
                        </div>
                        <div class="col-lg-5 col-xl-4">
                            <div class="inner-widget" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
                                <div class="section-title-style-03">
                                    <h3 class="title">{$blog_heading_title}</h3>
                                </div>

                                <div class="header-part-recent-post-wrapper">
                                    <ul class="recent-blog-post-style-02">
                                        {$centerBlogContent}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ads banner area start -->
                    <div class="ads-banner-area-wrapper leaderboard" data-padding-top="100" data-padding-bottom="0">
                        <div class="container custom-container-01">
                            <div class="rwo">
                                <div class="col-lg-12">
                                    <div class="ads-banner-box">
                                       {$bannerContent}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ads banner area end -->
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
                    <div class="widget-area-wrapper">
                        <div class="widget">
                            <div class="social-link style-01 v-02">
                                <h4 class="widget-title style-01">{$social_heading_title}</h4>
                                <ul class="widget-social-link-list">
                                    {$socialContent}
                                </ul>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="category style-01">
                                <h4 class="widget-title style-01">{$category_heading_title}</h4>
                                <ul class="widget-category-list">
                                    {$categoryContent}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
PARENT;
}


private function LeftVideos($left_blogs,$left_items){

    $current_lang = GlobalLanguage::user_lang_slug();
    
 $videos = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$left_blogs,$left_items) {    
    
    $videos = Blog::select('id','title','image','slug','created_at','category_id','author','video_url')->whereIn('id',$left_blogs)->where('status','publish');

    if(!empty($left_items) ){
        $videos = $videos->take($left_items)->get();
    }else{
        $videos =  $videos->take(3)->get();
    }
    
    return $videos;

});

    $markup = '';

    foreach ($videos as $key=> $item){
        $title = Str::words($item->getTranslation('title',$current_lang),9);
        $blog_url = route('frontend.blog.single',$item->slug);
        $video_url = $item->video_url ?? '';
        $date = date('M d, Y',strtotime($item->created_at));

        $bg_image = render_background_image_markup_by_attachment_id($item->image);
        $created_by = $item->author ?? __('Anonymous');

        if ($item->created_by === 'user') {
            $user_id = $item->user_id;
        } else {
            $user_id = $item->admin_id;
        }
        $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single',$item->slug);


        $static_icon = asset('assets/frontend/img/videos/play-icon/03.svg');

        $category_markup = '';
        foreach ($item->category_id as $cat) {
            $category = $cat->getTranslation('title', $current_lang);
            $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
            $category_markup .= ' <li class="post-meta-item"><a href="' . $category_route . '"><span class="text">' . $category . '</span></a> </li>';
        }


  $markup.= <<<LEFT

 <div class="blog-grid-style-03 large slick-item">
    <div class="img-box video-blog">
       <a href="$blog_url"><div class="background-img lazy" {$bg_image} data-height="590"></div></a>
        <a href="{$video_url}" class="play-icon icon-style-01 magnific-inst mfp-iframe">
            <img src="{$static_icon}" alt=""class="max-wh-132">
        </a>
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
               
                     {$category_markup}    
              
            </ul>
        </div>
        <h4 class="title">
            <a href="{$blog_url}">{$title}</a>
        </h4>
    </div>
</div>

LEFT;

  }

    return $markup;
}

private function CenterBlogs($blogsbyCategories,$blog_items,$order_by,$order){
     $current_lang = GlobalLanguage::user_lang_slug();
     
     
 $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($blogsbyCategories,$order_by,$order,$blog_items,$current_lang) {
        $blogs = Blog::select('id','title','image','slug','created_at','category_id','author')->usingLocale($current_lang)->query();
    
        if (!empty($blogsbyCategories)){
            $blogs->whereJsonContains('category_id', $blogsbyCategories);
        }
        $blogs =$blogs->orderBy($order_by,$order);
        if(!empty($blog_items)){
            $blogs = $blogs->take($blog_items)->get();
        }else{
            $blogs = $blogs->take(5)->get();
        }
        
        return $blogs;
        
 });    

    $markup = '';
    foreach ($blogs as $item) {
        $image = render_image_markup_by_attachment_id($item->image,'','thumb');
        $title = Str::words($item->title ?? __('No Title'),8);
        $route = route('frontend.blog.single', $item->slug) ?? '';

        $category_markup = '';
        foreach ($item->category_id as $cat) {
            $category = $cat->getTranslation('title', $current_lang);
            $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
            $category_markup .= '<a class="category-style-01" href="' . $category_route . '">' . $category . '</a>';
        }


 $markup .= <<<CENTERBLOGS

    <li class="single-blog-post-item">
        <div class="thumb video-blog">
          {$image}
        </div>
        <div class="content">
          {$category_markup}
            <h4 class="title font-size-20">
                <a href="{$route}">{$title}</a>
            </h4>
        </div>
    </li>

CENTERBLOGS;
}

return $markup;

}

private function SocialMedia($repeater_data){

    $current_lang = GlobalLanguage::user_lang_slug();
    $social_icon_markup = '';
    $colors = ['facebook','twitter', 'youtube','instagram','linkedin','pinterest'];
    $repeater_data['icon_'.$current_lang] = $repeater_data['icon_'.$current_lang] ?? [];
    if(!isset($repeater_data['icon_'.$current_lang])){
        return '';
    }
    foreach ($repeater_data['icon_'.$current_lang] as $key => $icon) {
        $icon = $icon;
        $url = $repeater_data['url_' . $current_lang][$key] ?? '#';
        $follower_text = $repeater_data['follower_text_' . $current_lang][$key] ?? [];
        $follower_number = $repeater_data['follower_number_' . $current_lang][$key] ?? [];

        $condition_color_and_bg = $colors[$key % count($colors)];


   $social_icon_markup .= <<<SOCIALICON
          <li class="single-item">
                <span class="left-content">
                    <a href="{$url}" class="icon {$condition_color_and_bg}">
                        <i class="{$icon}"></i>
                    </a>
                    <span class="followers-numb">
                        <span class="count">{$follower_number}</span>
                        {$follower_text}
                    </span>
                </span>
                <a href="{$url}" class="link facebook">like</a>
            </li>
SOCIALICON;
    }

    return $social_icon_markup;

}

private function Categories($onlyCategoryShowItems){
    $user_lang = GlobalLanguage::user_lang_slug();
    
        
    $blog_categories = BlogCategory::select('id','title')->where('status','publish')->orderBy('id', 'desc');

    if(!empty($onlyCategoryShowItems)){
        $blog_categories = $blog_categories->take($onlyCategoryShowItems)->get();
    }else{
        $blog_categories = $blog_categories->take(6)->get();
    }
    

    $markup = '';
    foreach ($blog_categories as $item) {
        $title = $item->getTranslation('title', $user_lang);
        $url = route('frontend.blog.category', ['id' => $item->id, 'any' => Str::slug($item->title)]);
        $bol = Blog::whereJsonContains('category_id', (string) $item->id)->count();

$markup .= <<<CATEGORY
    <li class="single-item">
        <a href="{$url}" class="wrap">
            <span class="left">{$title}</span>
            <span class="right">{$bol}</span>
        </a>
    </li>
CATEGORY;
    }
    return $markup;
 }

private function Banner($banner_image,$banner_url){

$image = render_image_markup_by_attachment_id($banner_image);
   return  <<<RIGHTBANNER
     <a href="{$banner_url}">
       {$image}
    </a>
RIGHTBANNER;

}

public function addon_title()
{
    return __('Blog Misc : 01');
}


}