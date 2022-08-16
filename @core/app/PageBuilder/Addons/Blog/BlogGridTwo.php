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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class BlogGridTwo extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/blog-grid-02.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

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

        $output .= Select::get([
            'name' => 'columns',
            'label' => __('Column'),
            'options' => [
                'col-lg-3' => __('04 Column'),
                'col-lg-4' => __('03 Column'),
                'col-lg-6' => __('02 Column'),
            ],
            'value' => $widget_saved_values['columns'] ?? null,
            'info' => __('set column')
        ]);
        $output .= Notice::get([
            'type' => 'secondary',
            'text' => __('Pagination Settings')
        ]);
        $output .= Switcher::get([
            'name' => 'pagination_status',
            'label' => __('Enable/Disable Pagination'),
            'value' => $widget_saved_values['pagination_status'] ?? null,
            'info' => __('your can show/hide pagination'),
        ]);
        $output .= Select::get([
            'name' => 'pagination_alignment',
            'label' => __('Pagination Alignment'),
            'options' => [
                'justify-content-left' => __('Left'),
                'justify-content-center' => __('Center'),
                'justify-content-right' => __('Right'),
            ],
            'value' => $widget_saved_values['pagination_alignment'] ?? null,
            'info' => __('set pagination alignment'),
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
        $category = $this->setting_item('categories') ?? null;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $columns = SanitizeInput::esc_html($this->setting_item('columns'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $pagination_alignment = $this->setting_item('pagination_alignment');
        $pagination_status = $this->setting_item('pagination_status') ?? '';

  $blogs = Cache::remember($this->generateCacheKey(), 600 ,function () use($current_lang,$category,$order_by,$order,$items) {
        $blogs = Blog::usingLocale($current_lang)->query()->select('id','title','image','slug','created_at','category_id','author','status');

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

        $pagination_markup = '';
        if (!empty($pagination_status) && !empty($items)){
            $pagination_markup = '<div class="pagination-wrapper">'.$blogs->links().'</div>';
        }

        $blog_markup = '';
        foreach ($blogs as $item){

            $image = render_background_image_markup_by_attachment_id($item->image);
            $route = route('frontend.blog.single',$item->slug);
            $title = Str::words(SanitizeInput::esc_html($item->getTranslation('title',$current_lang)),7);
            $date = date('M d, Y',strtotime($item->created_at));

            $category_markup = '';
            foreach ($item->category_id as $cat){
                $category = $cat->getTranslation('title',$current_lang);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup.=' <li class="post-meta-item"><a href="'.$category_route.'"><i class="las la-tag icon"></i><span class="text">'.$category.'</span></a></li>';
            }


 $blog_markup .= <<<HTML
       <div class="col-md-6 {$columns}">
        <div class="blog-grid-style-01">
            <div class="img-box border-radius-5">
               <a href="{$route}"><div class="background-img lazy"{$image} data-height="282"></div></a>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list">
                       
                           {$category_markup}
                        
                        <li class="post-meta-item date">
                            <i class="lar la-clock icon"></i>
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
                <h4 class="title">
                    <a href="{$route}">{$title}</a>
                </h4>
            </div>
        </div>
    </div>
HTML;

}

 return <<<HTML
    <div class="blog-grid-wrapper three-column blog-grid-fashion" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="container">
            <div class="row">
              {$blog_markup}
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="pagination {$pagination_alignment}" data-padding-top="50">
                       {$pagination_markup}
                    </div>
                </div>
            </div>
        </div>
    </div>
HTML;

}

    public function addon_title()
    {
        return __('Blog Grid : 02');
    }
}