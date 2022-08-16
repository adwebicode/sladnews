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

class MostVisitedBlogPostWidget extends WidgetBase
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

        $blog_posts = Blog::select('id','title','image','slug','created_at','category_id')->where(['status' => 'publish'])->take($blog_items)->orderBy('views','desc')->get();

        $blogs_markup = '';
        foreach ($blog_posts as $post){

            $image = render_background_image_markup_by_attachment_id($post->image,'','thumb');
            $route = route('frontend.blog.single',$post->slug);
            $title = Str::words($post->getTranslation('title',$user_selected_language),10);
            $date = date('M d, Y',strtotime($post->created_at));

            $category_markup = '';
            foreach ($post->category_id as $cat){
                $category = $cat->getTranslation('title',$user_selected_language);
                $category_route = route('frontend.blog.category',['id'=> $cat->id,'any'=> Str::slug($cat->title)]);
                $category_markup.=  '  <li class="post-meta-item date"><a href="'.$category_route.'"><i class="las la-tag icon"></i><span class="text">'.$category.'</span></a> </li>';
            }

     $blogs_markup.=  <<<LIST
             <li class="single-blog-post-item">
                <div class="thumb">
                 <div class="background-img lazy border-radius-10" data-width="150" data-height="150" {$image}></div>
                </div>
                <div class="content">
                    <h4 class="title font-size-20">
                        <a href="{$route}">{$title}</a>
                    </h4>
                    <div class="post-meta">
                        <ul class="post-meta-list">
                           
                                {$category_markup}
                           
                            <li class="post-meta-item date">
                                <i class="lar la-clock icon"></i>
                                <span class="text">{$date}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

LIST;

        }


 return <<<HTML

            <div class="widget">
                <h4 class="widget-title style-02">{$widget_title}</h4>
                <ul class="recent-blog-post-style-01 index-02 one">
                    {$blogs_markup}
                </ul>
            </div>

      

HTML;
    }

    public function widget_title()
    {
        return __('Most Read Blogs');
    }
}
