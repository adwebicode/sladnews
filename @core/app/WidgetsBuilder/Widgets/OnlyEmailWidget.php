<?php

namespace App\WidgetsBuilder\Widgets;


use App\Blog;
use App\BlogCategory;
use App\Facades\GlobalLanguage;
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
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class OnlyEmailWidget extends WidgetBase
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
                'name' => 'email_title_'.$lang->slug,
                'label' => __('Email Title'),
                'value' => $widget_saved_values['email_title_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Text::get([
            'name' => 'email',
            'label' => __('Email'),
            'value' => $widget_saved_values['email'] ?? null,
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
        $email_title = SanitizeInput::esc_html($this->setting_item('email_title_'.$current_lang));
        $email = SanitizeInput::esc_html($this->setting_item('email'));


  return <<<HTML
      <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="footer-widget widget footer-two-email">
                    <div class="content">
                        <h4 class="title">{$email_title}</h4>
                        <p class="email">{$email}</p>
                    </div>
                </div>
            </div>
    
HTML;
}


    public function widget_title(){
        return __('Email');
    }

}