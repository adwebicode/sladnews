<?php


namespace App\PageBuilder\Addons\Common;
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

class AuthorGrid extends PageBuilderBase
{

  use LanguageFallbackForPageBuilder;
    public function preview_image()
    {
       return 'common/authors.png';
    }


    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


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
            ],
            'value' => $widget_saved_values['columns'] ?? null,
            'info' => __('set column')
        ]);
        $output .= Notice::get([
            'type' => 'secondary',
            'text' => __('Pagination Settings')
        ]);
        $output .= Switcher::get([
            'name' => 'pagination_status',
            'label' => __('Enable/Disable Pagination'),
            'value' => $widget_saved_values['pagination_status'] ?? null,
            'info' => __('your can show/hide pagination'),
        ]);
        $output .= Select::get([
            'name' => 'pagination_alignment',
            'label' => __('Pagination Alignment'),
            'options' => [
                'text-left' => __('Left'),
                'center-text' => __('Center'),
                'end-text' => __('Right'),
            ],
            'value' => $widget_saved_values['pagination_alignment'] ?? null,
            'info' => __('set pagination alignment'),
        ]);
        $output .= Notice::get([
           'type' => 'secondary',
           'text' => __('Section Settings')
        ]);
        $output .= ColorPicker::get([
            'name' => 'background_color',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['background_color'] ?? '',
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
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $background_color = SanitizeInput::esc_html($this->setting_item('background_color'));
        $background_color = !empty($background_color) ? 'style="background-color:'.$background_color.';"' : '';
        $pagination_alignment = $this->setting_item('pagination_alignment');
        $pagination_status = $this->setting_item('pagination_status') ?? '';
        $columns = SanitizeInput::esc_html($this->setting_item('columns'));

  $team_member = Cache::remember($this->generateCacheKey(), 600 ,function () use($order_by,$order,$items) {
        $team_member = User::select('id','name','image','facebook_url','twitter_url','linkedin_url','instagram_url')->orderBy($order_by,$order);
        if(!empty($items)){
            $team_member = $team_member->paginate($items);
        }else{
            $team_member =  $team_member->get();
        }
        
        return $team_member;
        
  });

        $pagination_markup = '';
        if (!empty($pagination_status) && !empty($items)){
            $pagination_markup = '<div class="col-lg-12 mt-5"><div class="pagination-wrapper '.$pagination_alignment.'">'.$team_member->links().'</div></div>';
        }

        if(!empty($items)){
            $team_member = $team_member->take($items);
        }
        $category_markup = '';
        foreach ($team_member as $item){
            $image = render_image_markup_by_attachment_id($item->image);
            $name = $item->name;

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
            $author_url =  route('frontend.author.profile', $item->id);

     $category_markup .= <<<HTML

     <div class="col-sm-6 col-md-6 {$columns}">
        <div class="single-author-item">
            <div class="img-box">
               {$image}
             
            </div>
            <div class="content">
                <h4 class="title">
                    <a href="{$author_url}">{$name}</a>
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

    <div class="author-area-wrapper three-column" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}"{$background_color}>
        <div class="container">
            <div class="row">
               {$category_markup}                       
                {$pagination_markup}
            </div>
        </div>
    </div>
  
HTML;
}


    public function addon_title()
    {
        return __('Author Grid: 01');
    }
}