<?php


namespace App\WidgetsBuilder\Widgets;


use App\Blog;
use App\BlogCategory;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Language;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Text;
use App\WidgetsBuilder\WidgetBase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Vedmant\FeedReader\Facades\FeedReader;
use Vedmant\FeedReader\FeedReaderServiceProvider;

class RssBlogFeed extends WidgetBase
{

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

        $output .= Text::get([
            'name' => 'feed_url',
            'label' => __('Feed URL'),
            'value' => $widget_saved_values['feed_url'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'header_style',
            'label' => __('Header Style'),
            'options' => [
                '1' => __('Style One'),
                '2' => __('Style Two'),
                '4' => __('Style Three'),
            ],
            'value' => $widget_saved_values['header_style'] ?? null,
            'info' => __('You can change header style from here')
        ]);

        $output .= Number::get([
            'name' => 'items',
            'label' => __('Feed Items'),
            'value' => $widget_saved_values['items'] ?? null,
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
        $header_style = $settings['header_style'] ?? '';
        $feed_url = $settings['feed_url'] ?? '';
        $feed_items = $settings['items'] ?? '';

        $response = Http::get($feed_url);
        $body = $response->body() ?? [];
        $xmlObject = simplexml_load_string($body);
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true) ?? [];
        
        
        
        $xmlObject = json_decode(json_encode(simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA),true), true);
        $dd = new \SimpleXMLElement($body);
         
        
        $all_items = [];
        
        if(isset($phpArray['channel'])){
            $all_items = $phpArray['channel']['item'] ?? []; 
        }elseif($phpArray['entry']){
            $all_items = $phpArray['entry'];
        }
        // dd($all_items);

        $output_arr = [];
        if(!empty($phpArray['entry'])){
            //todo run foreach loop to fetch data from it
            foreach($dd->entry as $entry){
                $link = property_exists($entry,'link') ? json_decode(json_encode($entry->link),true) : '';
                //todo build array for loop
                $output_arr[] = [
                    'title' => $entry->title->__toString(),
                    'link' => $link["@attributes"]["href"] ?? '',
                    'pubDate' => $entry->updated,
                    'description' => '',
                ];
            }
            
        }else{
             for ($i=0; $i<$feed_items; $i++) {
                $output_arr[] = $all_items[$i];
            }
        }
       

        $feed_markup = '';
        $count_item = 0;
        foreach ($output_arr ?? [] as $item){
            
            if($count_item >= $feed_items){
                break;
            }
            
            $title = Str::words($item['title'],12) ?? '' ;
            $link = $item['link'] ?? '';
            $published_date = $item['pubDate'] ?? '';
           $date = optional(Carbon::parse($published_date))->format('d M, Y');
            $desc = $item['description'] ?? '';
            $explode = $desc ? explode('/>',$desc) : '' ;
            $feed_image = $explode ? $explode[0] . '/>'  : '';

        $count_item++;
        $feed_markup.= <<<LIST

          <li class="single-blog-post-item">
            <div class="thumb newsfeed-img">
               {$feed_image}
            </div>
            <div class="content">
                <h4 class="title font-size-20">
                    <a href="{$link}">{$title}</a>
                </h4>
                <div class="post-meta">
                    <ul class="post-meta-list">       
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
        <h4 class="widget-title style-0{$header_style}">{$widget_title}</h4>
        <ul class="recent-blog-post-style-01 index-02 one exc">
            {$feed_markup}
        </ul>
    </div>


HTML;
    }

    public function widget_title()
    {
        return __('Blog Feeds');
    }
}