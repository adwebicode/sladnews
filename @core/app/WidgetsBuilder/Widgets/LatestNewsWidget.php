<?php


namespace App\WidgetsBuilder\Widgets;

use App\Blog;
use App\Facades\GlobalLanguage;
use App\Language;
use App\Menu;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class LatestNewsWidget extends WidgetBase
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
            $output .= '<div class="form-group"><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' .purify_html($widget_title) . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option
        $post_items = $widget_saved_values['post_items'] ?? '';
        $output .= '<div class="form-group"><input type="number" name="post_items" class="form-control" placeholder="' . __('Post Items') . '" value="' . $post_items . '"></div>';

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_title = purify_html($this->setting_item('widget_title_' . $user_selected_language) ?? '');
        $post_items = $this->setting_item('post_items') ?? '';
        $blog_posts = Blog::select('id','title','image','slug','created_at')->where(['status' => 'publish'])->take($post_items)->get();

        $list_item = '';
        foreach ($blog_posts as $post) {
            $title = Str::words($post->getTranslation('title', $user_selected_language), 8);
            $image = render_image_markup_by_attachment_id($post->image);
            $route = route('frontend.blog.single', $post->slug);
            $date = date('d M, Y', strtotime($post->created_at));


            $list_item .= <<<LIST
    <li class="single-blog-post-item">
            <div class="thumb">
               {$image}
            </div>
            <div class="content">
                <h4 class="title">
                    <a href="{$route}">{$title}</a>
                </h4>
                <div class="post-meta">
                    <ul class="post-meta-list style-02">
                        <li class="post-meta-item date">
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
LIST;

}


 return <<<HTML
  <div class="col-sm-8 col-md-7 col-lg-6 col-xl-3">
    <div class="footer-widget">
        <h4 class="widget-title">{$widget_title}</h4>
        <ul class="recent-blog-post-style-01">
            {$list_item}
        </ul>
    </div>
    </div>

HTML;
 }


    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Latest News');
    }
}
