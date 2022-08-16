<?php

namespace App\WidgetsBuilder\Widgets;
use App\Facades\GlobalLanguage;
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

class SocialMediaWidgetThree extends WidgetBase
{

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //render language tab
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();

        $all_languages = Language::all();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $widget_title = $widget_saved_values['widget_title_' . $lang->slug] ?? '';
            $output .= '<div class="form-group"><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'social_02',
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


                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'follower_text',
                    'label' => __('Follower Text')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'text_two',
                    'label' => __('Text Two')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'follower_number',
                    'label' => __('Follower Number')
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

        $title =  purify_html($settings['widget_title_'.$current_lang] ?? '');

        $repeater_data = $settings['social_02'];
        $social_icon_markup = '';
        $colors = ['facebook','twitter', 'youtube','instagram','linkedin','pinterest'];

        if (!isset($repeater_data['icon_'.$current_lang])){
            return '';
        }

        foreach ($repeater_data['icon_'.$current_lang] as $key => $icon) {
            $icon = $icon;
            $url = $repeater_data['url_' . $current_lang][$key] ?? '#';
            $follower_text = $repeater_data['follower_text_' . $current_lang][$key] ?? '';
            $text_two = $repeater_data['text_two_' . $current_lang][$key] ?? '';
            $follower_number = $repeater_data['follower_number_' . $current_lang][$key] ?? '';
            $condition_color_and_bg = $colors[$key % count($colors)];


 $social_icon_markup.= <<<LIST

  <li class="single-item">
        <span class="left-content">
            <a href="{$url}" class="icon {$condition_color_and_bg}">
                <i class="{$icon}"></i>
            </a>
            <span class="followers-numb">
                <span class="count">{$follower_number}</span>
                {$follower_text}
            </span>
        </span>
        <a href="{$url}" class="link facebook">{$text_two}</a>
    </li>

LIST;
 }

return <<<HTML
        <div class="widget">
            <div class="social-link style-01">
                <h4 class="widget-title style-01">{$title}</h4>
                <ul class="widget-social-link-list">
                    {$social_icon_markup}
                </ul>
            </div>
        </div>

HTML;


}

    public function widget_title()
    {
        return __('Social Media : 03');
    }

}