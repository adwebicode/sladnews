<?php


namespace App\WidgetsBuilder\Widgets;

use App\Language;
use App\Menu;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;

class NavigationMenuWidget extends WidgetBase
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
            $widget_title =  $widget_saved_values['widget_title_'. $lang->slug] ?? '';
            $selected_menu_id = $widget_saved_values['menu_id_'. $lang->slug] ?? '';

            $output .= '<div class="form-group"><input type="text" name="widget_title_'. $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="'. purify_html($widget_title) .'"></div>';


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option
        $navigation_menus = Menu::all();
        $output .= '<div class="form-group">';
        $output .= '<select class="form-control" name="menu_id">';
        foreach($navigation_menus as $menu_item){
            $selected = $selected_menu_id == $menu_item->id ? 'selected' : '';
            $output .= '<option value="'.$menu_item->id.'" '.$selected.'>'.purify_html($menu_item->title).'</option>';
        }
        $output .= '</select>';
        $output .= '</div>';
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $user_selected_language = get_user_lang();
        $widget_saved_values = $this->get_settings();
        $widget_title = purify_html($this->setting_item('widget_title_'. $user_selected_language) ?? '');
        $menu_id = $this->setting_item('menu_id') ?? '';

        $output = $this->widget_before(); //render widget before content
        $output .= '<div class="footer-widget">';

        $output .= '<ul class="footer-item-list">';
        $output .= render_frontend_menu($menu_id);
        $output .= '</ul>';

        $output .= '</div>';
        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Navigation Menu');
    }
}