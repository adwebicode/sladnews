<?php


namespace App\WidgetsBuilder\Widgets;

use App\Blog;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Language;
use App\Menu;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Tag;
use App\WidgetsBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class MostVisitedBlogThreeWidget extends WidgetBase
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
                'name' => 'heading_text_'.$lang->slug,
                'label' => __('Heading Text'),
                'value' => $widget_saved_values['heading_text_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();

        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Number::get([
            'name' => 'blog_items',
            'label' => __('Blog Items'),
            'value' => $widget_saved_values['blog_items'] ?? null,
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
        $widget_title = purify_html($settings['heading_text_' . $user_selected_language] ?? '');
        $blog_items = $settings['blog_items'] ?? '';

        $blog_posts = Blog::select('id','title','slug','created_at','category_id')->where(['status' => 'publish'])->take($blog_items)->orderBy('views','desc')->get();

        $blogs_markup = '';
        foreach ($blog_posts as $post){
            $route = route('frontend.blog.single',$post->slug);
            $title = $post->getTranslation('title',$user_selected_language);
            $date = date('M d, Y',strtotime($post->created_at));
            $created_by = $post->author ?? __('Anonymouse');

            if ($post->created_by === 'user') {
                $user_id = $post->user_id;
            } else {
                $user_id = $post->admin_id;
            }

            $created_by_url = !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $post->created_by, 'id' => $user_id]) : route('frontend.blog.single',$post->slug);
            $author = $post->author;

            $category_markup = '';
            foreach ($post->category_id as $cat){
                $category = $cat->getTranslation('title',$user_selected_language);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup.=  ' <a href="'.$category_route.'"><i class="las la-tag icon"></i><span class="text">'.$category.'</span></a>';
            }

 $blogs_markup.=  <<<LIST
     <li class="news-heading-item">
        <h3 class="title font-size-18">
            <a href="{$route}">{$title}</a>
        </h3>
        <div class="post-meta">
            <ul class="post-meta-list style-02">
                <li class="post-meta-item">
                    <a href="{$created_by_url}">
                        <span class="text author">{$created_by}</span>
                    </a>
                </li>
                <li class="post-meta-item date">
                    <span class="text">{$date}</span>
                </li>
            </ul>
        </div>
    </li>

LIST;
}


 return <<<HTML
    <div class="widget">
        <div class="recent-post style-01">
            <h4 class="widget-title style-04">{$widget_title}</h4>
            <ul class="news-headline-list style-01">
                {$blogs_markup}
            </ul>
        </div>
    </div>
HTML;
}

    public function widget_title()
    {
        return __('Most Visited Blogs : 03');
    }
}
