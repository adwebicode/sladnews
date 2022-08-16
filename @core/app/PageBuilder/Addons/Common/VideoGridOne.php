<?php


namespace App\PageBuilder\Addons\Common;
use App\Blog;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class VideoGridOne extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'common/video-grid-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

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
                'justify-content-start' => __('Left'),
                'text-center' => __('Center'),
                'justify-content-end' => __('Right'),
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
        $current_lang = GlobalLanguage::user_lang_slug();
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $pagination_alignment = $this->setting_item('pagination_alignment');
        $pagination_status = $this->setting_item('pagination_status') ?? '';
        
 $videos = Cache::remember($this->generateCacheKey(), 600 ,function () use($order_by, $order, $items) {

        $videos = Blog::select('id','title','image','slug','created_at','category_id','author','video_url')->orderBy($order_by,$order)->where('video_url', '!=', NULL);

        if(!empty($items)){
            $videos = $videos->paginate($items);
        }else{
            $videos =  $videos->get();
        }
        
        return $videos;
        
 });

        $pagination_markup = '';
        if (!empty($pagination_status) && !empty($items)){
            $pagination_markup = '<div class="col-lg-12 mt-5"><div class="pagination-wrapper '.$pagination_alignment .'">'.$videos->links().'</div></div>';
        }

        if(!empty($items)){
            $videos = $videos->take($items);
        }
        


        $video_markup = '';
        foreach ($videos as $item){
            $image = render_background_image_markup_by_attachment_id($item->image);
            $title = Str::words(SanitizeInput::esc_html($item->getTranslation('title',$current_lang)),10);
            $blog_url = route('frontend.blog.single',$item->slug);
            $video_url = $item->video_url ?? '';
            $video_duration = $item->video_duration ?? '';

    $video_markup .= <<<HTML

      <div class="col-md-6 col-lg-6">
        <div class="video-grid-single-item">
            <a href="{$video_url }"
                class="category-style-01 position-top-left v-02 bg-color-a video-timing">{$video_duration}</a>
            <a href="{$video_url }"class="play-icon icon-style-01 magnific-inst mfp-iframe"></a>
            
            <div class="img-box">
                <div class="background-img lazy"{$image} data-height="275"></div>
            </div>
            
            <div class="content">
                <h3 class="title">
                    <a href="{$blog_url}">{$title}</a>
                </h3>
            </div>
            
            <span class="overlay"></span>
            
        </div>
    </div>
HTML;
}


 return <<<HTML

         <div class="video-grid two-column" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
            <div class="row">
                 {$video_markup}
                   {$pagination_markup}
            </div>
         </div>
   
HTML;
}


    public function addon_title()
    {
        return __('Video Grid: 01');
    }
}