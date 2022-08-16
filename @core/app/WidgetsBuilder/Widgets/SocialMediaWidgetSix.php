<?php

namespace App\WidgetsBuilder\Widgets;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Language;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;

class SocialMediaWidgetSix extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

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
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $this->setting_item('title_' . $lang->slug) ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'social_06',
            'fields' => [
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'icon',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Url')
                ],

            ]
        ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $current_lang = GlobalLanguage::user_lang_slug();
        $title =  purify_html($settings['title_'.$current_lang] ?? '');

        $repeater_data = $settings['social_06'];
        $social_icon_markup = '';
        $colors = ['facebook','twitter', 'youtube','instagram','linkedin','pinterest'];

        if (!isset($repeater_data['icon_'.$current_lang])){
            return '';
        }

        foreach ($repeater_data['icon_'.$current_lang] as $key => $icon) {
            $icon = $icon;
            $url = $repeater_data['url_'.$current_lang][$key] ?? '#';
            $condition_color_and_bg = $colors[$key % count($colors)] ;


$social_icon_markup.= <<<SOCIALICON
<li class="single-item">
    <a href="{$url}" class="left-content">
        <span class="icon {$condition_color_and_bg}">
            <i class="{$icon}"></i>
        </span>
    </a>
</li>
SOCIALICON;
}


return <<<PARENT
<div class="widget">
    <div class="social-link style-04 v-02">
        <h4 class="widget-title style-04">{$title}</h4>
        <ul class="widget-social-link-list">
           {$social_icon_markup}
        </ul>
    </div>
</div>
PARENT;
 }

    public function widget_title()
    {
        return __('Social Media : 06');
    }
}