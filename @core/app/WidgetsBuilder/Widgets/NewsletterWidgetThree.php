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
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;

class NewsletterWidgetThree extends WidgetBase
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

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Subscribe Button Text'),
                'value' => $widget_saved_values['button_text_' . $lang->slug] ?? null,
            ]);


            $output .= Summernote::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Select::get([
            'name' => 'background',
            'label' => __('Background Style'),
            'options' => [
                '' => __('None'),
                'v-02' => __('Dark'),
            ],
            'value' => $widget_saved_values['background'] ?? null,
            'info' => __('You can change background from here')
        ]);


        $output .= Image::get([
            'name' => 'image',
            'label' => __('Select Image'),
            'value' => $widget_saved_values['image'] ?? null,
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
        $background = $settings['background'] ?? '';
        $widget_description =purify_html( $settings['description_' . $user_selected_language] ?? '');
        $widget_image = render_image_markup_by_attachment_id( $settings['image']);
        $form_action = route('frontend.subscribe.newsletter');
        $csrf = csrf_token();
        $subscribe_text =  $settings['button_text_' . $user_selected_language] ?? '';


return <<<HTML
    <div class="widget">
        <div class="newsletter style-02 {$background}">
            <div class="icon-box">
              {$widget_image}
            </div>
            <div class="text-box">
                <p class="info">{$widget_title}</p>
            </div>
            <p class="info">{$widget_description}</p>
            <div class="form-box">
                <form action="{$form_action}" method="post" class="newsletter-submit-form">
                  <input type="hidden" name="_token" value="{$csrf}">
                    <div class="form-message-show"></div>
                    <div class="form-group">
                        <input type="email" class="form-control email" aria-describedby="emailHelp"
                            placeholder="Enter Email Address">
                        <button type="submit" class="news-letter-btn newsletter-submit-btn-sidebar">{$subscribe_text}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

HTML;


 }

    public function widget_title()
    {
        return __('Newsletter : 03');
    }
}