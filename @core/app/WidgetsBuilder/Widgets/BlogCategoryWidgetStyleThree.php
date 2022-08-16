<?php


namespace App\WidgetsBuilder\Widgets;


use App\Blog;
use App\BlogCategory;
use App\Facades\GlobalLanguage;
use App\Language;
use App\PageBuilder\Fields\Number;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;

class BlogCategoryWidgetStyleThree extends WidgetBase
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
            $output .= '<div class="form-group"> <label>' .__('Widget Title').' </label><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
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
        $widget_title = purify_html($settings['widget_title_' . $user_selected_language] ?? '');
        $category_items = $settings['category_items'] ?? '';

        $blog_categories = BlogCategory::select('id','title','status','image')->where('status','publish')->orderBy('id', 'DESC')->take($category_items)->get();

        $category_markup = '';
        foreach ($blog_categories as $item){

            $title = $item->getTranslation('title',$user_selected_language);
            $category_image = render_background_image_markup_by_attachment_id($item->image);
            $url = route('frontend.blog.category', ['id' => $item->id,'any' => Str::slug($item->title)]);
            $bol = Blog::whereJsonContains('category_id',(string) $item->id)->count();


 $category_markup.= <<<LIST

    <li class="single-item">
        <span class="extra-bg lazy" {$category_image}></span>
        <a href="{$url}" class="wrap">
            <span class="left-content">{$title}</span>
            <span class="right-content"> {$bol}</span>
        </a>
    </li>

LIST;

}

 return <<<HTML

   <div class="widget">
        <div class="category style-03">
            <h4 class="widget-title style-02">{$widget_title}</h4>
            <ul class="widget-category-list">
                {$category_markup}
            </ul>
        </div>
    </div>
      

HTML;
    }

    public function widget_title()
    {
        return __('Blog Category : 03');
    }
}