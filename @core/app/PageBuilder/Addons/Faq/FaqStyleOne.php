<?php


namespace App\PageBuilder\Addons\Faq;
use App\Faq;
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


class FaqStyleOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
       return 'Faq/faq-01.png';
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
                'col-lg-12' => __('01 Column'),
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
                'text-center' => __('Center'),
                'text-right' => __('Right'),
            ],
            'value' => $widget_saved_values['pagination_alignment'] ?? null,
            'info' => __('set pagination alignment'),
        ]);

        $output .= Notice::get([
           'type' => 'secondary',
           'text' => __('Section Settings')
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
        $pagination_alignment = $this->setting_item('pagination_alignment');
        $pagination_status = $this->setting_item('pagination_status')?? '';
        $columns = SanitizeInput::esc_html($this->setting_item('columns'));

        $faq_items = Faq::select('title','description','is_open')->orderBy($order_by,$order);
        if(!empty($items)){
            $faq_items = $faq_items->paginate($items);
        }else{
            $faq_items =  $faq_items->get();
        }

        $pagination_markup = '';
        if (!empty($pagination_status) && !empty($items)){
            $pagination_markup = '<div class="col-lg-12"><div class="pagination-wrapper mt-5 '.$pagination_alignment.'">'.$faq_items->links().'</div></div>';
        }

        $rand_number = rand(9999,99999999);
        $faq_markup ='<div class="faq-area-wrapper">';
        foreach ($faq_items as $key=> $faq){

            $aria_expanded = 'false';
            if($faq->is_open == 'on'){
                $aria_expanded = 'true';
            }

            $open_condition = $faq->is_open == 'on' ? 'show' : '';
            $title = SanitizeInput::esc_html($faq->title);
            $description = SanitizeInput::kses_basic($faq->description);

        $faq_markup .= <<<HTML
            <div class="card">
                <div class="card-header" id="headingOne_{$key}">
                    <h5 class="mb-0">
                        <a href="#" class="accordion-btn btn-link collapsed"
                            data-toggle="collapse" data-target="#collapseOne_{$key}"
                            aria-expanded="{$aria_expanded}" aria-controls="collapseOne_{$key}">
                           {$title}
                            <span class="color-1">
                                <i class="las la-plus open"></i>
                                <i class="las la-minus close"></i>
                            </span>
                        </a>
                    </h5>
                </div>
                <div id="collapseOne_{$key}" class="collapse {$open_condition}" aria-labelledby="headingOne_{$key}"
                   data-parent="#accordion_{$rand_number}">
                    <div class="card-body">
                        <p class="info">{$description} </p>
                    </div>
                </div>
            </div>

HTML;
}

$faq_markup .= '</div>';

return <<<HTML
 
    <div class="container" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="row">
            <div class="col-md-12">
                <div class="faq-accordion">
                    <div class="accordion" id="accordion_{$rand_number}">
                        <div class="row">
                          <div class="{$columns}">
                            {$faq_markup}
                        </div>
                        </div>
            
                        </div>
                    </div>
                </div>
                   {$pagination_markup}
            </div>    
        </div>
    </div>



HTML;

}



    public function addon_title()
    {
        return __('FAQ: 01');
    }
}