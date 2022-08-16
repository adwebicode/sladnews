<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\ColorPicker;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
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

class BlogVideoListFour extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'blog-page/blog-video-list-04.png';
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

        $output .= Image::get([
            'name' => 'icon',
            'label' => __('Icon Image'),
            'value' => $widget_saved_values['icon'] ?? null,
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
        $icon = render_image_markup_by_attachment_id($this->setting_item('icon'),'max-wh-132');

   $videos = Cache::remember($this->generateCacheKey(), 600 ,function () use($blog,$order_by,$order,$items) {
        $videos = Blog::select('id','title','image','slug','created_at','category_id','author','status','video_url')->whereIn('id',$blog)->orderBy($order_by,$order)->where('status','publish');

        if(!empty($items) && $items < 9){
            $videos = $videos->take($items)->get();
        }else if(!empty($items) && $items > 9) {
            $videos =  $videos->take(8)->get();
        }else{
            $videos =  $videos->take(8)->get();
        }
        
        return $videos;
  });


        $list_item = '';
        $big_portion_single_blog_video = '';

        foreach ($videos as $key=> $item){
            $title = $item->getTranslation('title',$current_lang);
            $title_2 = Str::words($item->getTranslation('title',$current_lang),12);
            $blog_url = route('frontend.blog.single',$item->slug);
            $description = Str::words(strip_tags($item->getTranslation('blog_content',$current_lang)),115);
            $video_url = $item->video_url ?? '';
            $date = date('M d, Y',strtotime($item->created_at));
            $bg_image = render_background_image_markup_by_attachment_id($item->image);
            $created_by = $item->author ?? __('Anonymous');
            $created_by_url = get_blog_created_user($item);

            $category_markup = '';
            foreach ($item->category_id as $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<a href="' . $category_route . '"><span>' . $category . '</span></a>';

            }

            if( !is_null($item->video_url) && $key == 0 ) {
                $big_portion_single_blog_video.= self::bigSingleBlog($bg_image,$video_url,$icon,$created_by_url,$created_by,$date,$category_markup,$blog_url,$title_2,$description);
            }else {


$list_item .= <<<LISTITEM
     <li class="news-heading-item">
        <h3 class="title">
            <a href="{$blog_url}">{$title}</a>
        </h3>
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
    </li>

LISTITEM;
  }
}


 return <<<HTML
    <div class="popular-news-area-wrapper index-05" data-padding-top="{$padding_top}" data-padding-bottom="$padding_bottom">
        <div class="container custom-container-01">
            <div class="row">
                   <div class="col-lg-12">
                        <div class="section-title-style-04">
                            <h3 class="title">{$heading_text}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                           {$big_portion_single_blog_video}
                        </div>
                        <div class="col-lg-4">
                            <div class="news-headline-wrapper one v-02">
                                <ul class="news-headline-list style-01">
                                    {$list_item}
                                </ul>
                            </div>
                        </div>
                    </div>          
            </div>
        </div>
    </div>

HTML;
}

private function bigSingleBlog ($bg_image,$video_url,$icon,$created_by_url,$created_by,$date,$category_markup,$blog_url,$title_2,$description)
{
    return <<<BIGSINGLE
 <div class="blog-grid-style-03 large">
        <div class="img-box video-blog">
           <a href="$blog_url"> <div class="background-img lazy" $bg_image data-height="590"></div></a>
            <a href="{$video_url}"
                class="play-icon icon-style-01 magnific-inst mfp-iframe">
               {$icon}
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
                    <li class="post-meta-item">
                       {$category_markup}
                    </li>
                </ul>
            </div>
            <h4 class="title">
                <a href="{$blog_url}">{$title_2}</a>
            </h4>
            <p class="info">{$description}</p>
             <div class="btn-wrapper mt-3">
                <a href="{$blog_url}" class="btn-default ul-btn">read more</a>
            </div>
     
        </div>
    </div>

BIGSINGLE;

}


    public function addon_title()
    {
        return __('Video List : 04');
    }
}