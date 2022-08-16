<?php


namespace App\WidgetsBuilder\Widgets;

use App\Blog;
use App\Facades\GlobalLanguage;
use App\Language;
use App\Menu;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class NewBlogPostWidget extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function frontend_render()
    {
        $user_selected_language = GlobalLanguage::user_lang_slug();
        $widget_title =  purify_html($this->setting_item('widget_title_' . $user_selected_language) ?? '');
        $post_items = $this->setting_item('post_items') ?? '';

        $blog_posts = Blog::select('id','title','image','slug','created_at','category_id')->where(['status' => 'publish'])->orderBy('id','DESC')->take($post_items)->get();

        $output = $this->widget_before(); //render widget before content

        $output .= '<div class="what-new-area padding-top-30">';
        if (!empty($widget_title)) {
            $output .= '<div class="section-title-three desktop-center"><h4 class="title">' .purify_html($widget_title) . '</h4></div>';
        }
        $output .= '<div class="what-contents">';

        foreach ($blog_posts as $post) {
            $image = render_image_markup_by_attachment_id($post->image);
            $route = route('frontend.blog.single',$post->slug);

            $output.= '
                  <div class="single-what-flex wow animated fadeInUp" data-wow-delay=".1s">
                        <div class="what-thumb">
                            '.$image.'
                        </div>
                        <div class="what-inner-contents">
                            <h6 class="what-title"><a href="'.$route.'"> '.Str::words(purify_html($post->getTranslation('title',$user_selected_language)),7).'</a> </h6>
                            <span class="what-dates"> '.date('d M Y',strtotime($post->created_at)).'</span>
                        </div>
                    </div>';
        }
        $output .= '</div>';
        $output .= '</div>';

        $output .= $this->widget_after(); // render widget after content
        return $output;
    }


    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('New Posts');
    }
}
