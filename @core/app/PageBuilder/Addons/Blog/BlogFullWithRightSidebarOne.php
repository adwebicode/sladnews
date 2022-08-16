<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogCategory;
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
use Carbon\Carbon;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Cache;

class BlogFullWithRightSidebarOne extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'blog-page/blog-full-with-right-sidebar-01.png';
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
                'name' => 'right_side_heading_text_'.$lang->slug,
                'label' => __('Right Side Heading Text'),
                'value' => $widget_saved_values['right_side_heading_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $categories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

        $output .= NiceSelect::get([
            'name' => 'categories',
            'label' => __('Select Categories'),
            'placeholder' => __('Select Categories'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired category or leave it empty')
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
            'info' => __('enter how many item you want to show in frontend'),
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
        $category = SanitizeInput::esc_html($this->setting_item('categories'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $right_side_heading_text = SanitizeInput::esc_html($this->setting_item('right_side_heading_text_'.$current_lang));
        
          $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$category,$order_by,$order,$items) {
                $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id');

                if (!empty($category)){
                    $blogs->whereJsonContains('category_id', $category);
                }
                $blogs =$blogs->orderBy($order_by,$order);
                if(!empty($items)){
                    $blogs = $blogs->take($items)->get();
                }else{
                    $blogs = $blogs->take(5)->get();
                }
                
                 return $blogs;
              
          });

        $blog_List_markup = '';
        $big_portion_single_blog_video = '';
        foreach ($blogs as $key=> $item){
            $image = render_image_markup_by_attachment_id($item->image,'','thumb');
            $title = $item->title ?? __('No Title');
            $route = route('frontend.blog.single',$item->slug) ?? '';
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

            $category_markup = '';
            $category_markup_without_colors = '';
            $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
            foreach ($item->category_id as $cat){
                $category = $cat->getTranslation('title',$current_lang);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup_without_colors.='<a  href="'.$category_route.'"><i class="las la-tag icon"></i><span class="text">'.$category.'</span></a>';
            }

            foreach ($item->category_id as $cat){
                $category = $cat->getTranslation('title',$current_lang);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup.='<a class="category-style-01 '.$colors[$key % count($colors)].'" href="'.$category_route.'">'.$category.'</a>';
            }

            if(!empty($item) && $key == 0) {
            $big_portion_single_blog_video.= self::bigSingleBlog($bg_image,$category_markup,$title,$route,$created_by_image,$created_by,$created_by_url,$date,$comment_condition_check);
         }else {


   $blog_List_markup .= <<<LIST

  <li class="single-blog-post-item">
        <div class="thumb">
         <div class="background-img lazy border-radius-10" data-height="150" data-width="150" {$bg_image}></div>
        </div>
            <div class="content">
                <h4 class="title font-size-20">
                    <a href="{$route}">{$title}</a>
                </h4>
                <div class="post-meta">
                    <ul class="post-meta-list">
                        <li class="post-meta-item date">
                           {$category_markup_without_colors}
                        </li>
                        <li class="post-meta-item date">
                            <i class="lar la-clock icon"></i>
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>


LIST;
  }
}


 return <<<HTML
    <div class="header-area-wrapper" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="header-area index-02">
            <div class="container custom-container-01">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-8">
                       {$big_portion_single_blog_video}
                    </div>
                    <div class="col-sm-9 col-md-8 col-lg-6 col-xl-4">
                        <div class="widget-area-wrapper">
                            <div class="widget">
                                <h4 class="widget-title style-02">{$right_side_heading_text}</h4>
                                <ul class="recent-blog-post-style-01 index-02 one">
                                    {$blog_List_markup}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
HTML;
}



private function bigSingleBlog ($bg_image,$category_markup,$title,$route,$created_by_image,$created_by,$created_by_url,$date,$comment_condition_check){

return <<<BIGSINGLE
     <div class="image-blog-large two">
        <div class="image-blog-style-01">
            <div class="img-box border-radious">
                <div class="background-img lazy" {$bg_image} data-height="750"></div>
                <span class="overlay"></span>
            </div>
            <div class="content">
              {$category_markup}
                <h3 class="title">
                    <a href="{$route}">{$title}</a>
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
        return __('Blog Full with Right Sidebar : 01');
    }
}