<?php


namespace App\WidgetsBuilder\Widgets;
use App\Language;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;

class BlogSearchWidget extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

    public function admin_render()
    {
        // TODO: Implement admin_render() method.
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

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $user_selected_language = get_user_lang();
        $widget_title = purify_html($this->setting_item('widget_title_' . $user_selected_language) ?? '');

        $output = $this->widget_before(); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h4 class="widget-title">' . purify_html($widget_title) . '</h4>';
        }
        $output .='<div class="widget-search wow bounceInUp " data-wow-duration="1.5s">
                            <form action="'.route('frontend.blog.search').'" method="get" class="search-form"class="form-inline">
                                <div class="form-group">
                                    <input type="search" name="search" class="form-control" placeholder="search...">
                                </div>
                                <button type="submit" class="form-btn-1"><i class="las la-search"></i></button>
                            </form>
                        </div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Blog Search');
    }
}