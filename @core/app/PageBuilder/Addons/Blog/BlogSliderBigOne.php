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

class BlogSliderBigOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-slider-big-01.png';
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

            $categories = Blog::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

            $output .= NiceSelect::get([
                'multiple'=>true,
                'name' => 'blogs',
                'label' => __('Blogs'),
                'placeholder' => __('Select Blogs'),
                'options' => $categories,
                'value' => $widget_saved_values['categories'] ?? null,
                'info' => __('you can select categories for blog, if you want to show all event leave it empty')
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

        $output .= Select::get([
            'name' => 'custom_class',
            'label' => __('Apply Title Background Color'),
            'options' => [
                'v-02' => __('None'),
                '' => __('Apply Background Color'),
            ],
            'value' => $widget_saved_values['custom_class'] ?? null,
            'info' => __('you can set title color or leave this blank')
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
        $blog_qty = $this->setting_item('blogs') ?? null;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $heading_text= SanitizeInput::esc_html($this->setting_item('heading_text_'.$current_lang));
        $custom_class = SanitizeInput::esc_html($this->setting_item('custom_class'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


   $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$blog_qty,$order_by,$order,$items) {
        $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');

        if (!empty($blog_qty)){
            $blogs->whereIn('id', $blog_qty)->where('status','publish');
        }
        $blogs =$blogs->orderBy($order_by,$order);
        if(!empty($items)){
            $blogs = $blogs->take($items)->get();
        }else{
            $blogs = $blogs->take(3)->get();
        }
        
        return $blogs;

});

        $blog_markup = '';

        foreach ($blogs as $item){

            $bg_image = render_background_image_markup_by_attachment_id($item->image);
            $route = route('frontend.blog.single',$item->slug);
            $title = SanitizeInput::esc_html($item->getTranslation('title',$current_lang));
            $date = date('M d, Y',strtotime($item->created_at));
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
            foreach ($item->category_id as $key=> $cat){
                $category = $cat->getTranslation('title',$current_lang);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup.='<a class="category-style-01 '.$colors[$key % count($colors)].'" href="'.$category_route.'"> '.$category.'</a>';
            }

            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single',$item->slug);
            $comment_count = BlogComment::where('blog_id',$item->id)->count();
            $comment_condition_check = $comment_count == 0 ? 0 : $comment_count;




 $blog_markup .= <<<HTML
      <div class="image-blog-style-01 slick-item">
            <div class="img-box border-radious">
               <a href="$route"> <div class="background-img lazy"{$bg_image}data-height="645"></div></a>
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
HTML;

}


 return <<<HTML
    <div class="weekly-highlights-area-wrapper index-01" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-title-style-01 {$custom_class}">
                    <h3 class="title">{$heading_text}</h3>
                </div>

                <div class="weekly-highlights-index-01-slider-inst slick-main">
                    {$blog_markup}
                </div>
            </div>
        </div>
    </div>
HTML;

    }



    public function addon_title()
    {
        return __('Blog Slider Big : 01');
    }
}