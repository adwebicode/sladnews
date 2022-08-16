<?php

namespace App\PageBuilder\Addons\ContactArea;

use App\Facades\GlobalLanguage;
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
use App\TopbarInfo;

class ContactArea extends PageBuilderBase
{
    use RepeaterHelper, LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'contact-page/contact.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings($this->generateCacheKeyForSettings());

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = LanguageHelper::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'contact_page_contact_info_01',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'description',
                    'label' => __('Details'),
                    'info' => __('new line count as a separate text')
                ],

                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'icon',
                    'label' => __('Icon'),
                ],

            ]
        ]);


        $output .= Select::get([
           'name' => 'custom_form_id',
           'label' => __('Custom Form'),
           'placeholder' => __('Select form'),
           'options' => FormBuilder::all()->pluck('title','id')->toArray(),
           'value' =>   $widget_saved_values['custom_form_id'] ?? []
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
        $all_settings = $this->get_settings($this->generateCacheKeyForSettings());

        $current_lang = GlobalLanguage::user_lang_slug();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $custom_form_id = SanitizeInput::esc_html($this->setting_item('custom_form_id'));
        $title = SanitizeInput::esc_html($this->setting_item('title_' . $current_lang));
        $icon = SanitizeInput::esc_html($this->setting_item('icon_' . $current_lang));


        $output = '<div class="contact-us-area-wrapper" data-padding-top="'.$padding_top.'" data-padding-bottom="'.$padding_bottom.'">';

        $output .='<div class="container">';
        $output .= '<div class="row">';

        $output .= '<div class="col-md-5 col-lg-4">
                        <div class="address-wrapper"> <ul class="address-list">';
        $this->args['settings'] = RepeaterField::remove_default_fields($all_settings);

        foreach (($this->args['settings'] ?? []) as $key => $setting){
            if (is_array($setting)){
                $this->args['repeater'] = $setting;
                $array_lang_item = $setting[array_key_last($setting)];
                if (!empty($array_lang_item) && is_array($array_lang_item) && count($array_lang_item) > 0) {
                    foreach ($array_lang_item as $index => $value) {
                        $output .= $this->render_slider_markup($index); // for multiple array index
                    }
                } else {

                    $output .= $this->render_slider_markup(); // for only one index of array
                }
            }
        }



        $output .= '</ul></div></div>'; //contact info column wrap

        if (!empty($custom_form_id)){
            $output .= '<div class="col-md-7 col-lg-8"> <div class="get-in-touch-wrapper" ><h3 class="title">'.$title.'</h3>';
            $form_details = FormBuilder::find($custom_form_id);
            $output .= FormBuilderCustom::render_form(optional($form_details)->id,null,null,'btn-default');
            $output .= '</div></div>';
        }

        $output .= ' </div> </div></div>';
        return $output;
    }

    public function addon_title()
    {
        return __('Contact Area');
    }

    private function render_slider_markup(int $index = null): string
    {
        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $icon = $this->get_repeater_field_value('icon', $index, LanguageHelper::user_lang_slug());

        $description = $this->get_repeater_field_value('description', $index, LanguageHelper::user_lang_slug());
        $description = explode("\n",$description);
        $description_markup = '';
        if (!empty($description)){
            foreach ($description as $item){
                $description_markup .= '<span class="details">'.$item.'</span>';
            }
        }


   return <<<HTML
        <li class="single-address-item">
            <div class="icon-box">
                <i class="{$icon} icon"></i>
            </div>
            <div class="content">
                <h5 class="title">{$title}</h5>
                <p class="info">{$description_markup}</p>
            </div>
        </li>

HTML;

}




}