<?php

namespace App\WidgetsBuilder\Widgets;


use App\Facades\GlobalLanguage;
use App\Language;
use App\Widgets;
use App\WidgetsBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;

class AboutUsWidget extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $image_val = $widget_saved_values['site_logo'] ?? '';
        $image_preview = '';
        $image_field_label = __('Upload Image');
        if (!empty($widget_saved_values)) {
            $image_markup = render_image_markup_by_attachment_id($widget_saved_values['site_logo']);
            $image_preview = '<div class="attachment-preview"><div class="thumbnail"><div class="centered">' . $image_markup . '</div></div></div>';
            $image_field_label = __('Change Image');
        }

        $output .= '<div class="form-group"><label for="site_logo"><strong>' . __('Logo') . '</strong></label>';
        $output .= '<div class="media-upload-btn-wrapper"><div class="img-wrap">' . $image_preview . '</div><input type="hidden" name="site_logo" value="' . $image_val . '">';
        $output .= '<button type="button" class="btn btn-info btn-xs media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-toggle="modal" data-target="#media_upload_modal">';
        $output .= $image_field_label . '</button></div>';
        $output .= '<small class="form-text text-muted">' . __('allowed image format: jpg,jpeg,png. Recommended image size 160x50') . '</small></div>';
        //start multi langual tab option

        //render language tab
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();
        $all_languages = Language::all();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);

            $description = $widget_saved_values['description_' . $lang->slug] ?? '';
            $output .= '<div class="form-group"><textarea name="description_' . $lang->slug . '"  class="form-control" cols="30" rows="5" placeholder="' . __('Description') . '">' .  purify_html($description) . '</textarea></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_saved_values = $this->get_settings();
        $description = $widget_saved_values['description_' . $user_selected_language] ?? '';
        $image_val = $widget_saved_values['site_logo'] ?? '';
        $white_logo = render_image_markup_by_attachment_id(get_static_option('site_white_logo'));
        $foot_logo1 = get_static_option('site_frontend_dark_mode') == 'on' ? $white_logo : render_image_markup_by_attachment_id($image_val, 'footer-logo') ;
        $root_url = url('/');


return <<<HTML
  <div class="col-sm-8 col-md-7 col-lg-6 col-xl-3">
    <div class="footer-widget">
        <div class="logo-wrapper">
            <a href="{$root_url}" class="logo">
               {$foot_logo1}
            </a>
        </div>
        <div class="content">
            <p class="info">{$description}</p>
        </div>
    </div>
</div>

HTML;
}

    public function widget_title(){
        return __('About Us');
    }

}