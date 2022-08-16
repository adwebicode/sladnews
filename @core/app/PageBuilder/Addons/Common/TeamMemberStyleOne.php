<?php


namespace App\PageBuilder\Addons\Common;
use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\ColorPicker;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use App\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use App\ProductCategory;
use App\TeamMember;
use App\Testimonial;
use App\User;
use Illuminate\Support\Facades\Cache;

class TeamMemberStyleOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'team/team-01.png';
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
                'name' => 'section_title_'.$lang->slug,
                'label' => __('Section Title'),
                'value' => $widget_saved_values['section_title_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Select::get([
            'name' => 'section_title_alignment',
            'label' => __('Section Title Alignment'),
            'options' => [
                'text-left' => __('Left Align'),
                'text-center' => __('Center Align'),
                'text-right' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_alignment'] ?? null,
            'info' => __('set alignment of section title')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);

        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);
        $output .= Select::get([
            'name' => 'columns',
            'label' => __('Column'),
            'options' => [
                'col-lg-3' => __('04 Column'),
                'col-lg-4' => __('03 Column'),
                'col-lg-6' => __('02 Column'),
                'col-lg-12' => __('01 Column'),
            ],
            'value' => $widget_saved_values['columns'] ?? null,
            'info' => __('set column')
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

    /**
     * @inheritDoc
     */
    public function frontend_render()
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $section_title = SanitizeInput::esc_html($this->setting_item('section_title_'.$current_lang));
        $section_title_alignment = SanitizeInput::esc_html($this->setting_item('section_title_alignment'));
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $columns = SanitizeInput::esc_html($this->setting_item('columns'));


 $team_member = Cache::remember($this->generateCacheKey(), 600 ,function () use($order_by,$order,$items) {
        $team_member = User::query()->orderBy($order_by,$order)->select('id','name','image','designation');
        if(!empty($items)){
            $team_member = $team_member->paginate($items);
        }else{
            $team_member =  $team_member->get();
        }

        if(!empty($items)){
            $team_member = $team_member->take($items);
        }
        
        return $team_member;
        
 });

        $column_markup = '';
        foreach ($team_member as $item){
            $image = render_image_markup_by_attachment_id($item->image);
            $name = $item->name;
            $member_url =  route('frontend.author.profile', $item->id);

            $social_links_markup = '<ul class="author-socials">';
            $social_fields = array(
                'lab la-facebook-f icon' => $item->facebook_url,
                'lab la-twitter icon' => $item->twitter_url,
                'lab la-instagram icon' => $item->instagram_url,
                'lab la-linkedin-in icon' => $item->linkedin_url,
            );
            $classes = ['facebook','twitter','instgram','linkedin'];
            $number = 0;
            foreach($social_fields as $key => $value){
                $social_links_markup .= '<li class="link-item"><a class="'.$classes[$number].'" href="'.$value.'"><i class="'.$key.'"></i></a></li>';
                 $number == 4 ? $number = 0 : $number++;
            }

     $social_links_markup .= '</ul>';

 $column_markup .= <<<HTML

 <div class="col-sm-6 col-md-6 {$columns}">
    <div class="single-author-item">
        <div class="img-box">
              {$image}
        </div>
        <div class="content">
            <h4 class="title">
                <a href="{$member_url}">{$name}</a>
            </h4>

            <ul class="author-social-link">
           
             {$social_links_markup}

            </ul>
        </div>
    </div>
</div>
HTML;
        }

  return <<<HTML

        <div class="author-area-wrapper three-column" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="author-post-title {$section_title_alignment} "> {$section_title}</h3>
                    </div>
                </div>
                <div class="row">
                       {$column_markup}
                </div>
              </div>
          </div>
HTML;

    }


    public function addon_title()
    {
        return __('Team Grid: 01');
    }
}