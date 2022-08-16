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

class BlogVideoListTwo extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'blog-page/blog-video-list-02.png';
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
        $videos = Blog::select('id','title','image','slug','created_at','category_id','author','video_url','status')->whereIn('id',$blog)->orderBy($order_by,$order)->where('status','publish');

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
            $image = render_image_markup_by_attachment_id($item->image);
            $title = Str::words($item->getTranslation('title',$current_lang),9);
            $blog_url = route('frontend.blog.single',$item->slug);
            $video_url = $item->video_url ?? '';
            $date = date('M d, Y',strtotime($item->created_at));


            $bg_image = render_background_image_markup_by_attachment_id($item->image);
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

            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }
            $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single',$item->slug);
            $comment_count = BlogComment::where('blog_id',$item->id)->count();
            $comment_condition_check = $comment_count == 0 ? 0 : $comment_count;

            $left_static_icon = asset('assets/frontend/img/videos/play-icon/04.svg');
            $right_static_icon = asset('assets/frontend/img/videos/play-icon/05.svg');

            $category_markup = '';

            foreach ($item->category_id as $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<a class="category-style-01 v-02 -02 ' . $colors[$key % count($colors)] . '" href="' . $category_route . '">' . $category . '</a>';
            }

            if( !is_null($item->video_url) && $key == 4 ) {
                $big_portion_single_blog_video.= self::bigSingleBlog($bg_image,$video_url,$right_static_icon,$category_markup,$blog_url,$title,$created_by_url,$created_by_image,$created_by,$date,$comment_condition_check);
            }else {


   $list_item .= <<<LISTITEM

    <li class="single-blog-post-item">
        <div class="thumb video-blog">
           <a href="$blog_url"> {$image}</a> 
            <a href="{$video_url}"
                class="play-icon icon-style-01 magnific-inst mfp-iframe">
                <img src="{$left_static_icon}" alt="">
            </a>
        </div>
        <div class="content">
          {$category_markup}
            <h4 class="title">
                <a href="{$blog_url}">{$title} </a>
            </h4>
        </div>
    </li>


LISTITEM;
  }
}


 return <<<HTML

    <!-- most viewed videos area start -->
    <div class="latest-videos-area-wrapper index-03" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-style-02">
                        <h3 class="title">{$heading_text}</h3>
                        <a href="{$viewmore_text_url}" class="view-more">{$viewmore_text} <i class="las la-arrow-right icon"></i></a>
                    </div>

                    <div class="row reverse">
                        <div class="col-sm-9 col-md-8 col-lg-6 col-xl-4">
                            <div class="videos-blog-list style-01">
                                <ul class="recent-blog-post-style-01 v-02">
                                    {$list_item}
                                </ul>
                            </div>
                        </div>
                            {$big_portion_single_blog_video}
                    </div>
                </div>
            </div>
        </div>
    </div>



HTML;
}

private function bigSingleBlog ($bg_image,$video_url,$right_static_icon,$category_markup,$blog_url,$title,$created_by_url,$created_by_image,$created_by,$date,$comment_condition_check)
{
  return <<<BIGSINGLE

   <div class="col-lg-12 col-xl-8">
    <div class="image-blog-style-01">
        <div class="img-box video-blog">
           <a href="$blog_url"> <div class="background-img lazy" {$bg_image} data-height="656"></div></a>
            <span class="overlay"></span>
            <a href="{$video_url}"
                class="right-top magnific-inst mfp-iframe">
                <img src="{$right_static_icon}" alt="">
            </a>
        </div>
        <div class="content">
           {$category_markup}
            <h3 class="title">
                <a href="{$blog_url}">{$title}</a>
            </h3>
            <div class="post-meta color-white">
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
        </div>
    </div>
</div>



BIGSINGLE;

}


    public function addon_title()
    {
        return __('Video List : 02');
    }
}