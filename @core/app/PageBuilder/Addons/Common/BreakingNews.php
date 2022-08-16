<?php


namespace App\PageBuilder\Addons\Common;
use App\Blog;
use App\BlogCategory;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\Tag;
use Illuminate\Support\Str;

class BreakingNews extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'header/breaking-news.png';
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
                'name' => 'news_title'.$lang->slug,
                'label' => __('News Title'),
                'value' => $widget_saved_values['news_title'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Select::get([
            'name' => 'style',
            'label' => __('News Style'),
            'options' => [
                '1' => __('Style One'),
                '2' => __('Style Two'),
            ],
            'value' => $widget_saved_values['style'] ?? null,
            'info' => __('set breaking news style from here')
        ]);


        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 110,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 110,
            'max' => 200,
        ]);

        // add padding option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $style = SanitizeInput::esc_html($this->setting_item('style'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $news_title = SanitizeInput::esc_html($this->setting_item('news_title' . $current_lang));

        $news = Blog::select('id','title','slug')->where(['status' => 'publish', 'breaking_news' => 'on'])->orderBy('id','desc')->take(6)->get();

        $news_markup = '';
        foreach ($news as $nw){
            $route = route('frontend.blog.single',$nw->slug);
            $blog_title =  $nw->getTranslation('title',$current_lang);
            $news_markup.= '<li class="single-news-item"> <a href="'.$route.'">'.$blog_title.'</a></li>';
        }


if(!empty(get_static_option('blog_breaking_news_show_hide_all'))) {
    return <<<HTML

    <div class="container custom-container-01" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="live-news-update-area-wrapper style-0{$style} margin-top-30">
            <div class="title-box">
                <h4 class="title">{$news_title}</h4>
                <span class="background-style-01"></span>
            </div>
            <div class="news-box">
                <ul class="news-box-list">
                  {$news_markup}
                </ul>
            </div>
        </div>
    </div>
HTML;
    }
 }

    public function addon_title()
    {
        return __('Breaking News');
    }
}