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

class SocialMediaWidget extends WidgetBase
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


        $output .= IconPicker::get([
            'name' => 'facebook_icon',
            'label' => __('Facebook Icon'),
            'value' => $widget_saved_values['facebook_icon'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'facebook_url',
            'label' => __('Facebook URL'),
            'value' => $widget_saved_values['facebook_url'] ?? null,
        ]);

        $output .= IconPicker::get([
            'name' => 'twitter_icon',
            'label' => __('Twitter Icon'),
            'value' => $widget_saved_values['twitter_icon'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'twitter_url',
            'label' => __('Twitter URL'),
            'value' => $widget_saved_values['twitter_url'] ?? null,
        ]);

        $output .= IconPicker::get([
            'name' => 'youtube_icon',
            'label' => __('Youtube Icon'),
            'value' => $widget_saved_values['youtube_icon'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'youtube_url',
            'label' => __('Youtube URL'),
            'value' => $widget_saved_values['youtube_url'] ?? null,
        ]);

        $output .= IconPicker::get([
            'name' => 'instagram_icon',
            'label' => __('Instagram Icon'),
            'value' => $widget_saved_values['instagram_icon'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'instagram_url',
            'label' => __('Instagram URL'),
            'value' => $widget_saved_values['instagram_url'] ?? null,
        ]);



        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_title = purify_html($settings['widget_title_' . $user_selected_language] ?? '');

        $facebook_icon = $settings['facebook_icon'] ?? '';
        $facebook_url =  purify_html( $settings['facebook_url']) ?? '';
        $twitter_icon = $settings['twitter_icon'] ?? '';
        $twitter_url =  purify_html($settings['twitter_url']) ?? '';
        $instagram_icon = $settings['instagram_icon'] ?? '';
        $instagram_url =  purify_html($settings['instagram_url']) ?? '';
        $youtube_icon = $settings['youtube_icon'] ?? '';
        $youtube_url =  purify_html($settings['youtube_url']) ?? '';

        $social_data = [
            $facebook_icon => $facebook_url,
            $twitter_icon => $twitter_url,
            $instagram_icon => $instagram_url,
            $youtube_icon => $youtube_url,
        ];

        $classes = ['icon facebook','icon twitter','icon instagram','icon youtube'] ?? [];
        $number = 0;
       $social_markup = '';
        foreach($social_data as $key => $value){
            $social_markup.= '
             <li class="single-item">
                        <a href="'.$value.'" class="left-content">
                            <span class="'.$classes[$number].'">
                                <i class="'.$key.'"></i>
                            </span>
                        </a>
                    </li>';

             $number == 4 ? $number = 0 : $number++;
        }



return <<<HTML
    <div class="widget">
        <div class="social-link style-04 border-round">
            <h4 class="widget-title style-03"> {$widget_title}</h4>
            <ul class="widget-social-link-list">
                  {$social_markup}
            </ul>
        </div>
    </div>

HTML;
}

    public function widget_title()
    {
        return __('Social Media : 01');
    }

}