<?php


namespace App\WidgetsBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Language;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Tag;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;

class BlogTagsWidgetThree extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

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
            $output .= '<div class="form-group"> <label>' .__('Widget Title').' </label><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $output .= Number::get([
            'name' => 'tag_items',
            'label' => __('TagItems'),
            'value' => $widget_saved_values['tag_items'] ?? null,
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
        $tag_items = $settings['tag_items'] ?? '';

        $blog_tags = Tag::select('id','name','status')->orderBy('id', 'DESC')->take($tag_items)->get();

        $tags_markup = '';
        foreach ($blog_tags as $key=> $item){

            $title = $item->getTranslation('name',$user_selected_language);
            $url = route('frontend.blog.tags.page', ['any' => $item->name]);


 $tags_markup.=  <<<LIST
     <li class="single-tag-item">
        <a href="{$url}">{$title}</a>
    </li>

LIST;

}

return <<<HTML
     <div class="widget">
        <div class="tag style-01 black-bg">
            <h4 class="widget-title style-01">{$widget_title}</h4>
            <ul class="tag-list">
              {$tags_markup}
            </ul>
        </div>
    </div>

HTML;
    }

    public function widget_title()
    {
        return __('Blog Tags : 03');
    }
}