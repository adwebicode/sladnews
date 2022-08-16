<?php


namespace App\PageBuilder\Addons\AboutArea;


use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Illuminate\Support\Str;

class AboutSectionStyleOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'about-section/about-01.png';
    }

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
                'name' => 'subtitle_'.$lang->slug,
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle_' . $lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'designation_'.$lang->slug,
                'label' => __('Designation'),
                'value' => $widget_saved_values['designation_' . $lang->slug] ?? null,
            ]);

            $output .= Summernote::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_' . $lang->slug] ?? null,
            ]);
            $output .= Image::get([
                'name' => 'image_'.$lang->slug,
                'label' => __('Image'),
                'value' => $widget_saved_values['image_' . $lang->slug] ?? null,
                'dimensions' => '480 x 634px'
            ]);
            $output .= $this->admin_language_tab_content_end();

        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Text::get([
            'name' => 'facebook_url',
            'label' => __('Facebook URL'),
            'value' => $widget_saved_values['facebook_url'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'twitter_url',
            'label' => __('Twitter URL'),
            'value' => $widget_saved_values['twitter_url'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'linkedin_url',
            'label' => __('Linkedin URL'),
            'value' => $widget_saved_values['linkedin_url'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'instagram_url',
            'label' => __('Instagram URL'),
            'value' => $widget_saved_values['instagram_url'] ?? null,
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
        $current_lang = GlobalLanguage::user_lang_slug();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top') ?? '');
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom') ?? '');

        $title = SanitizeInput::esc_html($this->setting_item('title_'.$current_lang));
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle_'.$current_lang) ?? '');
        $designation = SanitizeInput::esc_html($this->setting_item('designation_'.$current_lang) ?? '');
        $description = Str::words(strip_tags($this->setting_item('description_'.$current_lang),55));

        $facebook_url = SanitizeInput::kses_basic($this->setting_item('facebook_url') ?? '');
        $twitter_url = SanitizeInput::kses_basic($this->setting_item('twitter_url') ?? '');
        $linkedin_url = SanitizeInput::kses_basic($this->setting_item('linkedin_url') ?? '');
        $instagram_url = SanitizeInput::kses_basic($this->setting_item('instagram_url') ?? '');
        $bg_image = render_background_image_markup_by_attachment_id($this->setting_item('image_'.$current_lang),'','full');


        $right_content_markup = '';
        if (!empty($right_image)){
            $right_content_markup = <<<HTML
 <div class="right-content-area">
        {$right_image}
</div>
HTML;
        }

return <<<HTML
<div class="about-us-wrapper" >
        <div class="about-ceo-wrapper" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <div class="img-box">
                            <div class="ceo-bg lazy" {$bg_image}>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content">
                            <h3 class="title">{$title}</h3>

                            <p class="info">{$description}</p>

                            <div class="other">
                                <h4 class="owner-title">{$subtitle} </h4>
                                <p class="post">{$designation}</p>
                                <ul class="author-social-link">
                                    <li class="link-item">
                                        <a href="{$facebook_url}" class="facebook">
                                            <i class="lab la-facebook-f icon"></i>
                                        </a>
                                    </li>
                                    <li class="link-item">
                                        <a href="{$twitter_url}" class="twitter">
                                            <i class="lab la-twitter icon"></i>
                                        </a>
                                    </li>
                                    <li class="link-item">
                                        <a href="{$linkedin_url}" class="linkedin">
                                            <i class="lab la-linkedin-in icon"></i>
                                        </a>
                                    </li>
                                    <li class="link-item">
                                        <a href="{$instagram_url}" class="instgram">
                                            <i class="lab la-instagram icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
HTML;

    }

    public function addon_title()
    {
        return __('About Area: 01');
    }

}