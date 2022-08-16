<?php


namespace App\WidgetsBuilder\Widgets;

use App\Blog;
use App\BlogCategory;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Language;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;

class NewsletterWidgetTwo extends WidgetBase
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
                'value' => $widget_saved_values['title_' . $lang->slug] ?? null,
            ]);

            $output .= Summernote::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= IconPicker::get([
            'name' => 'icon',
            'label' => __('Select Icon'),
            'value' => $widget_saved_values['category_items'] ?? null,
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
        $widget_title = purify_html($settings['title_' . $user_selected_language] ?? '');
        $widget_description = purify_html($settings['description_' . $user_selected_language] ?? '');
        $widget_icon = $settings['icon'] ?? '';
        $form_action = route('frontend.subscribe.newsletter');
        $csrf = csrf_token();


return <<<HTML
    <div class="widget">
        <div class="newsletter style-01">
            <div class="top-content">
                <span class="icon-box">
                    <i class="{$widget_icon} icon"></i>
                </span>
                <span class="text-box">
                    <p class="info">{$widget_title}</p>
                </span>
            </div>
           
            <div class="bottom-content">
                <p class="info">{$widget_description}</p>
                <div class="form-box ">
                    <form action="{$form_action}" method="post" class="newsletter-submit-form">
                     <input type="hidden" name="_token" value="{$csrf}">
                       <div class="form-message-show"></div>
                        <div class="form-group ">
                            <input type="email" class="form-control email" name="email" placeholder="Enter Email Address">
                            <i class="news-icon"></i><button type="submit" class="news-letter-btn newsletter-submit-btn-sidebar">subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


HTML;


 }

    public function widget_title()
    {
        return __('Newsletter : 02');
    }
}