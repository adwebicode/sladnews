<?php


namespace App\WidgetsBuilder\Widgets;


use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Language;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;

class NewsletterWidget extends WidgetBase
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



        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_title = purify_html( $settings['title_' . $user_selected_language] ?? '');
        $widget_description = $settings['description_' . $user_selected_language] ?? '';
        $form_action = route('frontend.subscribe.newsletter');
        $csrf = csrf_token();


     return <<<HTML
        <div class="col-sm-8 col-md-7 col-lg-6 col-xl-3">
           <div class="footer-widget">
                <h4 class="widget-title">{$widget_title}</h4>
                <div class="footer-content">
                    <p class="info">{$widget_description}</p>
                    <div class="search-form style-01">
                        <form action="{$form_action}" method="post">
                         <input type="hidden" name="_token" value="{$csrf}">
                            <div class="form-row">
                                <div class="newsletter-footer">
                                    <div class="custom-input-wrapper">
                                      <div class="form-message-show"></div>
                                    <div class="btn-wrapper">
                                       <input type="text" class="form-control email" name="email"placeholder="enter email address">
                                        <button class="btn-default btn-rounded newsletter-submit-btn-footer" type="submit">subscribe</button>
                                    </div>
                                </div>
                                </div>
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
        return __('Newsletter : 01');
    }


}