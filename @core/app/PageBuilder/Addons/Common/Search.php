<?php


namespace App\PageBuilder\Addons\Common;
use App\Blog;
use App\BlogCategory;
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

class Search extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'common/search.jpg';
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
                'name' => 'tag_title'.$lang->slug,
                'label' => __('Tag Title'),
                'value' => $widget_saved_values['tag_title'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


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
        $settings = $this->get_settings();
        $current_lang = LanguageHelper::user_lang_slug();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $tag_title = SanitizeInput::esc_html($this->setting_item('tag_title'.$current_lang));

        $tags = Tag::where(['status'=> 'publish'])->inRandomOrder()->take(5)->get();
        $search_route = route('frontend.blog.search');

        $tag_markup = '';
        foreach ($tags as $tag){
            $tag_markup.= ' <a href="'.route('frontend.blog.tags.page', ['any' => $tag->name]).'" class="tag">'.$tag->getTranslation('name',$current_lang).'</a>';
        }


 return <<<HTML



    <div class="search-box-area-wrapper" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <form class="form" action="{$search_route}">
                        <div class="form-group">
                            <input type="search" name="search" class="form-control"
                                placeholder="Search Stories, Places &amp; people here">
                        </div>
                        <div class="btn-wrapper">
                            <button type="submit" class="btn-default">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="content">
                        <h4 class="title">{$tag_title}</h4>
                        <div class="tag-box">          
                             {$tag_markup}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    



HTML;

    }

    public function addon_title()
    {
        return __('Search');
    }
}