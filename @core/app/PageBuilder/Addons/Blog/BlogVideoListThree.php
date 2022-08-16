<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Cache;

class BlogVideoListThree extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'blog-page/blog-video-list-03.png';
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
                'name' => 'heading_text_'.$lang->slug,
                'label' => __('Heading Text'),
                'value' => $widget_saved_values['heading_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'view_more_text_'.$lang->slug,
                'label' => __('View More Text'),
                'value' => $widget_saved_values['view_more_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'view_more_text_url_'.$lang->slug,
                'label' => __('View More Text URL'),
                'value' => $widget_saved_values['view_more_text_url_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $blogs = Blog::usingLocale(LanguageHelper::default_slug())->where('video_url', '!=', NULL)->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();


        $output .= NiceSelect::get([
            'multiple'=>true,
            'name' => 'blogs',
            'label' => __('Select Blogs'),
            'placeholder' => __('Select Blogs'),
            'options' => $blogs,
            'value' => $widget_saved_values['blogs'] ?? null,
            'info' => __('you can select your desired blogs or leave it empty')
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

        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend (set 1 to 5)'),
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
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $blog = $this->setting_item('blogs');
        $items = SanitizeInput::esc_html($this->setting_item('items'));

        $heading_text= SanitizeInput::esc_html($this->setting_item('heading_text_'.$current_lang));
        $viewmore_text= SanitizeInput::esc_html($this->setting_item('view_more_text_'.$current_lang));
        $viewmore_text_url= SanitizeInput::esc_html($this->setting_item('view_more_text_url_'.$current_lang));
        
   $videos = Cache::remember($this->generateCacheKey(), 600 ,function () use($blog,$order_by,$order,$items) {        

        $videos = Blog::select('id','title','image','slug','created_at','category_id','author','status','video_url')->whereIn('id',$blog)->orderBy($order_by,$order)->where('status','publish');

        if(!empty($items) && $items < 5){
            $videos = $videos->take($items)->get();
        }else if(!empty($items) && $items > 5) {
            $videos =  $videos->take(5)->get();
        }else{
            $videos =  $videos->take(5)->get();
        }
        
        return $videos;
});

        $list_item = '';
        $big_portion_single_blog_video = '';
        $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
        foreach ($videos as $key=> $item){
            $image = render_background_image_markup_by_attachment_id($item->image);
            $title = Str::words($item->getTranslation('title',$current_lang),9);
            $blog_url = route('frontend.blog.single',$item->slug);
            $video_url = $item->video_url ?? '';
            $date = date('M d, Y',strtotime($item->created_at));
            $bg_image = render_background_image_markup_by_attachment_id($item->image);

            $left_static_icon = asset('assets/frontend/img/videos/play-icon/07.svg');
            $right_static_icon = asset('assets/frontend/img/videos/play-icon/01.svg');

            $category_markup = '';

            foreach ($item->category_id as $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<a class="category-style-01 v-02 ' . $colors[$key % count($colors)] . '" href="' . $category_route . '">' . $category . '</a>';
            }

            if( !is_null($item->video_url) && $key == 2 ) {
                $big_portion_single_blog_video.= self::bigSingleBlog($category_markup,$bg_image,$video_url,$right_static_icon,$blog_url,$title);
            }else {


   $list_item .= <<<LISTITEM
  <div class="col-sm-6 col-md-6 col-lg-12">
        <div class="image-blog-style-01 v-02 video-blog">
            <div class="img-box">
                <div class="tag-box position-top-left">
                   {$category_markup}
                </div>
               <a href="$blog_url"> <div class="background-img lazy"{$image} data-height="268"> </div></a>
                <a href="{$video_url}"
                    class="play-icon icon-style-01 magnific-inst mfp-iframe hide-x">
                    <img src="{$left_static_icon}" alt="">
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

LISTITEM;
  }
}


 return <<<HTML
        <div class="latest-videos-area-wrapper-index-05" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-style-04">
                        <h3 class="title">{$heading_text}</h3>
                        <a href="{$viewmore_text_url}" class="view-more">{$viewmore_text} <i class="las la-arrow-right icon"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class=" col-lg-4 left">
                    <div class="latest-video-short">
                        <div class="image-blog-large three index-05-two">
                            <div class="row">
                              {$list_item}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="image-blog-large three main">
                      {$big_portion_single_blog_video}
                    </div>
                </div>
            </div>
        </div>



HTML;
}

private function bigSingleBlog ($category_markup,$bg_image,$video_url,$right_static_icon,$blog_url,$title)
{
  return <<<BIGSINGLE

  <div class="image-blog-style-01 v-02 video-blog">
            <div class="img-box">
                <div class="tag-box position-top-left">
                   {$category_markup}
                </div>
                <div class="background-img lazy" $bg_image data-height="575"></div>
                <a href="{$video_url}"
                    class="play-icon icon-style-01 magnific-inst mfp-iframe">
                    <img src="{$right_static_icon}" alt="" class="max-wh-132">
                </a>
                <span class="overlay"></span>
            </div>
            <div class="content">
                <h3 class="title font-size-42">
                    <a href="{$blog_url}">{$title}</a>
                </h3>
            </div>
        </div>


BIGSINGLE;

}


    public function addon_title()
    {
        return __('Video List : 03');
    }
}