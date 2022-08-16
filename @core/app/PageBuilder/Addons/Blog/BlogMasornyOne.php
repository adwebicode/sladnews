<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\ColorPicker;
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

class BlogMasornyOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'blog-page/masorny-01.png';
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
                'name' => 'section_title_'.$lang->slug,
                'label' => __('Section Title'),
                'value' => $widget_saved_values['section_title_' . $lang->slug] ?? null,
            ]);
            $categories = BlogCategory::usingLocale(LanguageHelper::default_slug())->where(['status' => 'publish'])->get()->pluck('title', 'id')->toArray();

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= NiceSelect::get([
            'name' => 'categories',
            'multiple'=> true,
            'label' => __('Category'),
            'placeholder' =>  __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select category for blog or leave it empty')
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
            'info' => __('enter how many item you want to show in frontend, leave it empty if you want to show all'),
        ]);

        $output .= Select::get([
            'name' => 'section_title_alignment',
            'label' => __('Section Title Alignment'),
            'options' => [
                'left-align' => __('Left Align'),
                'center-align' => __('Center Align'),
                'right-align' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_alignment'] ?? null,
            'info' => __('set alignment of section title')
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
        $current_lang =  GlobalLanguage::user_lang_slug();
        $section_title = SanitizeInput::esc_html($this->setting_item('section_title_' . $current_lang));
        $categories = $this->setting_item('categories') ?? [];
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $section_title_alignment = SanitizeInput::esc_html($this->setting_item('section_title_alignment'));

   $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($categories,$order_by,$order,$items) {

        $blogs = Blog::query()->select('id','title','image','slug','created_at','category_id','author')
            ->where(['status' => 'publish'])->whereJsonContains('category_id', current($categories));
        $blogs->orderBy($order_by, $order);

        if (!empty($items)) {
            $blogs = $blogs->take($items);
        }
        $blogs = $blogs->get();
        
        return $blogs;
});

        $categoey_headings = BlogCategory::select('id','title','status')->whereIn('id', $categories)->get();
        $category_heading_markup = '';
        foreach ($categoey_headings as $key=> $cate) {
              $category_active_class = $key == 0 ? 'active' : '';
            if (!empty($cate)) {
                $category_heading_markup .= ' <li class="category-btn '.$category_active_class.'" data-id="' . $cate->id . '"><a href="#">' . $cate->getTranslation('title', $current_lang) . '</a></li>';
            }
        }

        $item_markup = '';
        $colors = ['bg-color-e','bg-color-a','bg-color-b','bg-color-g','bg-color-c'];
        foreach ($blogs as $key=> $item) {
            $image_markup = render_image_markup_by_attachment_id($item->image, '');
            $route = route('frontend.blog.single', $item->slug);
            $title = Str::words(SanitizeInput::esc_html($item->getTranslation('title', $current_lang) ?? ''),9);
            $date = date('M d, Y', strtotime($item->created_at));

            $category_markup2 = '';

            foreach ($item->category_id as $catItem) {
                $category2 = $catItem->getTranslation('title', $current_lang);
                $category_route2 = route('frontend.blog.category', ['id' => $catItem->id, 'any' => Str::slug($catItem->title)]);
                $category_markup2 .= '<a class="category-style-01 v-02 '.$colors[$key % count($colors)].'"  href="' . $category_route2 . '">' . $category2 . '</a>';
            }


            if ($item->created_by === 'user') {
                $user_id = $item->user_id;
            } else {
                $user_id = $item->admin_id;
            }

            $created_by_url = !is_null($user_id) ? route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single', $item->slug);

            $comment_count = BlogComment::where('blog_id', $item->id)->count();
            $comment_condition_check = $comment_count == 0 ? 0 : $comment_count;

            $created_by = SanitizeInput::esc_html($item->author ?? __('Anonymous'));


            //author image
            $author = NULL;
            if (!isNull($item->user_id)) {
                $author = optional($item->user);
            } else if (!isNull($item->admin_id)) {
                $author = optional($item->admin);
            } else {
                $author = optional($item->admin);
            }
            $user_image = render_image_markup_by_attachment_id($author->image, 'image');

            $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'), 'image');
            $created_by_image = $user_image ? $user_image : $avatar_image;



$item_markup .= <<<ITEM
    
 <div class="col-sm-6 col-md-6 col-lg-6">
    <div class="blog-grid-style-01 v-02">
        <div class="img-box">
        <a href="$route">{$image_markup}</a>
            <div class="tag-box left">
               {$category_markup2}
            </div>
        </div>
        <div class="content">
            <div class="post-meta color-black">
                <ul class="post-meta-list">
                    <li class="post-meta-item">
                        <a href="{$created_by_url}">
                          {$created_by_image}
                            <span class="text">{$created_by}</span>
                        </a>
                    </li>
                    <li class="post-meta-item date">
                        <span class="text">{$date}</span>
                    </li>
                    <li class="post-meta-item">
                        <a href="#">
                            <span class="text">{$comment_condition_check} Comments</span>
                        </a>
                    </li>
                </ul>
            </div>
            <h4 class="title">
                <a href="{$route}">{$title}</a>
            </h4>
        </div>
    </div>
</div>

ITEM;

}

return <<<HTML

  <div class="whats-new-area-wrapper index-03" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
    <div class="load-ajax-data"></div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                <div class="section-title-style-02">
                    <h3 class="title {$section_title_alignment}">{$section_title}</h3>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                <div class="category-btn-wrapper">
                    <ul class="category-btn-list">
                        {$category_heading_markup}
                    </ul>
                </div>
            </div>
        </div>

        <div class="what-new-index-03-wrap two-column">
            <div class="row home-page-ajax-news-show">
               {$item_markup}
            </div>  
        </div>
    </div>

HTML;

}



    public function addon_title()
    {
        return __('Blog Masorny: 01');
    }
}