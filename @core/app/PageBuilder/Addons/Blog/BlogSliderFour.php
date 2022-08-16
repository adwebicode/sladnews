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

class BlogSliderFour extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-slider-04.png';
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

            $categories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

            $output .= NiceSelect::get([
                'name' => 'categories',
                'label' => __('Category'),
                'placeholder' => __('Select Category'),
                'options' => $categories,
                'value' => $widget_saved_values['categories'] ?? null,
                'info' => __('you can select category for blog, if you want to show all event leave it empty')
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
            'name' => 'header_style',
            'label' => __('Header Style'),
            'options' => [
                '1' => __('Style One'),
                '2' => __('Style Two'),
                '3' => __('Style Three'),
                '4' => __('Style Four'),
            ],
            'value' => $widget_saved_values['header_style'] ?? null,
            'info' => __('you can set header style from here')
        ]);

        $output .= Select::get([
            'name' => 'layout_style',
            'label' => __('Layout Style'),
            'options' => [
                'popular-news-index-04-inst' => __('Style One'),
                'feature-news-index-04-inst' => __('Style Two'),
            ],
            'value' => $widget_saved_values['header_style'] ?? null,
            'info' => __('you can set header style from here')
        ]);

        $output .= Select::get([
            'name' => 'image_style',
            'label' => __('Image Style'),
            'options' => [
                'medium' => __('Medium Size'),
                'extra_medium' => __('Extra Medium'),
            ],
            'value' => $widget_saved_values['image_style'] ?? null,
            'info' => __('you can set image style from here')
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
        $category = $this->setting_item('categories') ?? null;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $header_style = SanitizeInput::esc_html($this->setting_item('header_style'));
        $layout_style = SanitizeInput::esc_html($this->setting_item('layout_style'));
        $image_style = SanitizeInput::esc_html($this->setting_item('image_style'));
        $heading_text= SanitizeInput::esc_html($this->setting_item('heading_text_'.$current_lang));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

  $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$category,$order_by,$order,$items) {
        $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author');

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

        $blog_markup = '';
        foreach ($blogs as $item){

            $image = render_image_markup_by_attachment_id($item->image);
            $bg = render_background_image_markup_by_attachment_id($item->image);
            $bg_markup = '  <div class="background-img lazy" '.$bg.' data-height="350"></div>';

            $image_condition = $image_style == 'medium' ? $image : $bg_markup;


            $route = route('frontend.blog.single',$item->slug);
            $title = Str::words(SanitizeInput::esc_html($item->getTranslation('title',$current_lang)),12);
            $date = date('M d, Y',strtotime($item->created_at));

            $category_markup = '';
            foreach ($item->category_id as $key=> $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<li class="post-meta-item"><a href="' . $category_route . '"><span class="text">' . $category . '</span></a> </li>';
            }


            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by = $item->author ?? __('Anonymous');
            $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single',$item->slug);



$blog_markup .= <<<ITEM
  <div class="slick-item">
        <div class="blog-grid-style-03 small">
            <div class="img-box">
            <a href="$route"> {$image_condition}</a> 
            </div>
            <div class="content">
                <h4 class="title">
                    <a href="{$route}">{$title}</a>
                </h4>
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
            </div>
        </div>
    </div>


ITEM;

}


 return <<<HTML
    <div class="popular-news-area-wrapper index-04" data-padding-top="{$padding_top}" data-padding-bottom="$padding_bottom">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-style-0{$header_style}">
                        <h3 class="title">{$heading_text}</h3>
                        <div class="appendarow"></div>
                    </div>
                </div>
            </div>

            <div class="slick-main {$layout_style}">
                {$blog_markup}
            </div>
        </div>
    </div>

HTML;

}



    public function addon_title()
    {
        return __('Blog Slider : 04');
    }
}