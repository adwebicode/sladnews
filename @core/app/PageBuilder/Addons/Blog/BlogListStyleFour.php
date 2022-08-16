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

class BlogListStyleFour extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-list-04.png';
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
            $blogs = $blogs->paginate($items);
        }else{
            $blogs = $blogs->get();
        }
        
        return $blogs;
        
  });        


        $list_markup = '';
        $single_big_markup = '';
        $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
        foreach ($blogs as $key=> $item) {

            $image = render_image_markup_by_attachment_id($item->image,'','thumb');
            $bg_image = render_background_image_markup_by_attachment_id($item->image);
            $route = route('frontend.blog.single', $item->slug);
            $title = Str::words($item->getTranslation('title', $current_lang), 12);

            $category_markup = '';

            foreach ($item->category_id as $cat) {
                $category = $cat->getTranslation('title', $current_lang);
                $category_route = route('frontend.blog.category', ['id' => $cat->id, 'any' => Str::slug($cat->title)]);
                $category_markup .= '<a class="category-style-01 v-02 -02 ' . $colors[$key % count($colors)] . '" href="' . $category_route . '">' . $category . '</a>';
            }

            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single', $item->slug);

            if ($key == 0) {
                $single_big_markup.= self::SingleBigItem($bg_image, $route, $title);
            } else {

$list_markup .= <<<LISTITEM

  <li class="single-blog-post-item">
        <div class="thumb video-blog">
        <a href="{$route}">{$image}</a>
        </div>
        <div class="content">
           {$category_markup}
            <h4 class="title">
                <a href="{$route}">{$title} </a>
            </h4>
        </div>
    </li>
LISTITEM;

    }
}

 return <<<HTML


 <div class="monthly-highlights index-03" data-padding-top="{$padding_top}" data-padding-bottom="$padding_bottom">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-title-style-02">
                <h3 class="title">{$heading_text}</h3>
            </div>
        </div>
    </div>
    <div class="row">
       {$single_big_markup}
        <div class="col-sm-9 col-md-8 col-lg-6">
            <ul class="recent-blog-post-style-01 v-02">
                {$list_markup}
            </ul>
        </div>
    </div>
</div>


  
       
HTML;

    }


 private function SingleBigItem($bg_image, $route, $title){

return  <<<SINGLE
 <div class="col-lg-6">
        <div class="image-blog-large three">
            <div class="image-blog-style-01 v-02 small">
                <div class="img-box">
                    <div class="background-img lazy" {$bg_image} data-height="656"></div>
                    <span class="overlay"></span>
                </div>
                <div class="content">
                    <h3 class="title v-02">
                        <a href="{$route}">{$title}</a>
                    </h3>
                </div>
            </div>
        </div>
   </div>

SINGLE;

}

    public function addon_title()
    {
        return __('Blog List : 04');
    }
}