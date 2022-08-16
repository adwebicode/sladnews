<?php


namespace App\PageBuilder\Addons\Common;


use App\FormBuilder;
use App\Helpers\FormBuilderCustom;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;

class GoogleMap extends PageBuilderBase
{
    use RepeaterHelper, LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'common/map.jpg';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'location',
            'label' => __('Location'),
            'value' => $widget_saved_values['location'] ?? null,
        ]);

        $output .= Slider::get([
            'name' => 'map_height',
            'label' => __('Map Height'),
            'value' => $widget_saved_values['map_height'] ?? 500,
            'max' => 2000,
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 120,
            'max' => 500,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 120,
            'max' => 500,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render(): string
    {
        $map_height = SanitizeInput::esc_html($this->setting_item('map_height'));
        $location = SanitizeInput::esc_html($this->setting_item('location'));

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $location =  sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" height="'.$map_height.'px" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($location),
            10,
            $location
        );

        return <<<HTML
     <div class="container" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="row">
            <div class="col-lg-12">
                 <div class="google-map">
                    {$location}
                 </div>
            </div>
        </div>
     </div>
 
HTML;
    }

    public function addon_title()
    {
        return __('Google Map: 01');
    }
}