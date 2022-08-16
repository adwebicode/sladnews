<?php

namespace App\WidgetsBuilder\Widgets;

use App\Advertisement;
use App\Blog;
use App\BlogCategory;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Language;
use App\PageBuilder\Fields\DatePicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class Advertise extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $advertisements = Advertisement::where(['status'=> 1])->get()->pluck('title','id')->toArray();

        $output .= Select::get([
            'name' => 'advertisement',
            'label' => __('Add Advertisement'),
            'options' => $advertisements,
            'value' => $widget_saved_values['advertisement'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();
        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $add = Advertisement::where('id',$settings['advertisement'])->first();

        if(is_null($add)){
            return '';
        }
            $image_markup = render_image_markup_by_attachment_id($add->image,null,'full');
            $redirect_url = SanitizeInput::esc_url($add->redirect_url);
            $slot = $add->slot;
            $embed_code = $add->embed_code;

            $add_markup = '';
            if ($add->type === 'image'){
                $add_markup = '<a href="'.$redirect_url.'">'.$image_markup.'</a>';
            }elseif($add->type === 'google_adsense'){
                $google_adsense_publisher_id = get_static_option('google_adsense_publisher_id');

      $add_markup = <<<HTML
        <div>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{$google_adsense_publisher_id}"
             data-ad-slot="{$slot}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div>
HTML;
            }else{
                $add_markup = '<div>'.$embed_code.'</div>';
            }


   return <<<HTML
<div class="widget">
 <div class="single-sidebar-item padding-top-30">
        <div class="banner-ads-slider">
            <div class="single-banner-ads">
              {$add_markup }
            </div>
           
        </div>
    </div>
</div>
HTML;

    }

    public function widget_title(){
        return __('Advertisement');
    }

}