<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RssNewsFeedSlider extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'blog-page/rss-news-feed.png';
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
                'name' => 'heading_text_'.$lang->slug,
                'label' => __('Heading Text'),
                'value' => $widget_saved_values['heading_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Text::get([
            'name' => 'feed_url',
            'label' => __('Feed URL'),
            'value' => $widget_saved_values['feed_url'] ?? null,
        ]);

        $output .= Number::get([
            'name' => 'feed_items',
            'label' => __('Items'),
            'value' => $widget_saved_values['feed_items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Select::get([
            'name' => 'custom_class',
            'label' => __('Apply Title Background Color'),
            'options' => [
                'v-02' => __('None'),
                '' => __('Apply Background Color'),
            ],
            'value' => $widget_saved_values['custom_class'] ?? null,
            'info' => __('you can set title color or leave this blank')
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
        $current_lang = LanguageHelper::user_lang_slug();
        $heading_text= SanitizeInput::esc_html($this->setting_item('heading_text_'.$current_lang));
        $feed_url = SanitizeInput::esc_html($this->setting_item('feed_url')) ?? '';
        $feed_items = SanitizeInput::esc_html($this->setting_item('feed_items')) ?? '';
        $custom_class = SanitizeInput::esc_html($this->setting_item('custom_class'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $response = Http::get($feed_url);
        $body = $response->body() ?? [];
        $xmlObject = simplexml_load_string($body);
        $json = json_encode($xmlObject);
        
     
        
        $phpArray = json_decode($json, true);
        if(isset($phpArray['entry'])){
             $xmlObject = json_decode(json_encode(simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA),true), true);
             $all_items = $xmlObject['entry'] ?? [];
        }else{
             $all_items = $phpArray['channel']['item'] ?? [];
        }
       
        

        $output_arr = [];
        for ($i=1; $i <= $feed_items; $i++) {
            if (isset($all_items[$i])) {
                $output_arr[] = $all_items[$i];
            }
        }


        $feed_markup = '';
        foreach ($output_arr as $item){
            
            $title = !empty($item['title']) ? Str::words($item['title'],12) : '' ;
            
            if(is_array($item['link'])){
                $link = $item['link']['@attributes']['href'] ?? '#';
            }else{
              $link = $item['link'] ?? '';  
            }
            
            
            if(isset($item['updated'])){
                $published_date = $item['updated'] ?? '';
            }else{
                $published_date = $item['pubDate'] ?? '';
            }
            
            $feed_image = '';
            $desc = '';
            $date = optional(Carbon::parse($published_date))->format('d M, Y');
            if(isset($item['description'])){
                $desc = $item['description'] ?? '';
                $explode = $desc ? explode('/>',$desc) : '' ;
                $feed_image = $explode ? $explode[0]  :  '';
            }elseif(isset($item['summery'])){
                $desc = $item['summery'] ?? '';
            }
           


$feed_markup .= <<<HTML
     <div class="col-lg-12">
        <div class="blog-grid-style-01">
            <div class="img-box">
             <a href="$link">{$feed_image}</a>
            </div>
            <div class="content">
                <div class="post-meta">
                    <ul class="post-meta-list">
            
                        <li class="post-meta-item date">
                            <i class="lar la-clock icon"></i>
                            <span class="text">{$date}</span>
                        </li>
                    </ul>
                </div>
                <h4 class="title">
                    <a href="{$link}">{$title}</a>
                </h4>
            </div>
        </div>
    </div>
HTML;

}


 return <<<HTML

    <!-- popular stories area start -->
    <div class="popular-stories-area-wrapper index-01" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="container custom-container-01">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-style-01 {$custom_class}">
                        <h3 class="title">{$heading_text}</h3>
                    </div>
                </div>
            </div>
            <div class="row popular-stories-inner popular-stories-index-01-slider-inst">
                {$feed_markup}
            </div>
        </div>
    </div>
    <!-- popular stories area end -->
       
HTML;

    }



    public function addon_title()
    {
        return __('Rss News Feed');
    }
}