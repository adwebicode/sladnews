<?php


namespace App\PageBuilder\Addons\Common;
use App\Facades\GlobalLanguage;
use App\Facades\InstagramFeed;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Partner;
use App\Testimonial;


class BrandsPartners extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'common/brands-partners.png';
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
                'name' => 'title_text_'.$lang->slug,
                'label' => __('Title Text'),
                'value' => $widget_saved_values['title_text_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

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


        $output .= Text::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 60,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 60,
            'max' => 200,
        ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $title_text = SanitizeInput::esc_html($this->setting_item('title_text_'.$current_lang) ?? '');
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $partners = Partner::select('id','url','image')->orderBy($order_by,$order);

        if(!empty($item_show)){
            $partners = $partners->take($item_show)->get();
        }else{
            $partners = $partners->get();
        }

        $output= '';
        foreach($partners as $item){
            $url = $item->url ?? '';
            $image = render_image_markup_by_attachment_id($item->image);


 $output.= <<<CONTENT

        <div class="slick-item">
            <div class="single-partner-item">
               <a href="{$url}">{$image}</a> 
            </div>
        </div>

CONTENT;

 }


  return <<<HTML
      <div class="container" >
           <div class="partners-with-wrapper" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="partner-title text-center">{$title_text}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="partner-with-slider slick-main">
                            {$output}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
           

HTML;

    }

    public function addon_title()
    {
        return __('Brands/ Partners');
    }
}