<?php


namespace App\PageBuilder;


use App\PageBuilder;
use Illuminate\Support\Facades\Cache;

class PageBuilderSetup
{
    private static function registerd_widgets(): array
    {
        //check module wise widget by set condition
        return [
            PageBuilder\Addons\ContactArea\ContactArea::class,
            PageBuilder\Addons\Common\CustomFormStyleOne::class,
            PageBuilder\Addons\Common\GoogleMap::class,
            PageBuilder\Addons\Common\Advertise::class,
            PageBuilder\Addons\Common\Search::class,
            PageBuilder\Addons\AboutArea\AboutSectionStyleOne::class,
            PageBuilder\Addons\Common\TeamMemberStyleOne::class,
            PageBuilder\Addons\Common\AuthorGrid::class,
            PageBuilder\Addons\Faq\FaqStyleOne::class,
            PageBuilder\Addons\Common\BreakingNews::class,
            PageBuilder\Addons\Common\AuthorsFeedback::class,
            PageBuilder\Addons\Common\BrandsPartners::class,
            PageBuilder\Addons\Common\VideoGridOne::class,
            PageBuilder\Addons\StaticHeader\HeaderOne::class,
            PageBuilder\Addons\Blog\BlogListStyleOne::class,
            PageBuilder\Addons\Common\BannerTwo::class,
            PageBuilder\Addons\Blog\BlogSliderOne::class,
            PageBuilder\Addons\Blog\BlogVideoListOne::class,
            PageBuilder\Addons\Blog\BlogGridOne::class,
            PageBuilder\Addons\Blog\BlogGridWithListOne::class,
            PageBuilder\Addons\Blog\BlogSliderBigOne::class,
            PageBuilder\Addons\Blog\BlogGridTwo::class,
            PageBuilder\Addons\Common\AdvertiseTwo::class,
            PageBuilder\Addons\Blog\BlogGridThree::class,
            PageBuilder\Addons\Blog\BlogListStyleTwo::class,
            PageBuilder\Addons\Blog\BlogStandardOne::class,
            PageBuilder\Addons\Blog\BlogGridFour::class,
            PageBuilder\Addons\Blog\BlogFullWithRightSidebarOne::class,
            PageBuilder\Addons\Blog\BlogSliderTwo::class,
            PageBuilder\Addons\Blog\VideoSliderOne::class,
            PageBuilder\Addons\StaticHeader\HeaderTwo::class,
            PageBuilder\Addons\Blog\BlogMasornyOne::class,
            PageBuilder\Addons\Blog\BlogSliderThree::class,
            PageBuilder\Addons\Blog\BlogVideoListTwo::class,
            PageBuilder\Addons\Blog\BlogListStyleThree::class,
            PageBuilder\Addons\Blog\BlogListStyleFour::class,
            PageBuilder\Addons\StaticHeader\HeaderMixedOne::class,
            PageBuilder\Addons\Blog\BlogSliderFour::class,
            PageBuilder\Addons\Blog\BlogMiscOne::class,
            PageBuilder\Addons\Blog\BlogGridFive::class,
            PageBuilder\Addons\Blog\BlogSliderFive::class,
            PageBuilder\Addons\Blog\BlogMiscTwo::class,
            PageBuilder\Addons\Blog\BlogGridSix::class,
            PageBuilder\Addons\Blog\BlogVideoListThree::class,
            PageBuilder\Addons\Blog\BlogVideoListFour::class,
            PageBuilder\Addons\Blog\BlogMiscThree::class,
            PageBuilder\Addons\Blog\RssNewsFeedSlider::class
        ];
    }

    public static function get_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget){
            try {
                $widget_instance = new  $widget();
            }catch (\Exception $e){
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }
            if ($widget_instance->enable()){
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }

        }
        return $widgets_markup;
    }

    private static function render_admin_addon_item($args): string
    {
        return '<li class="ui-state-default widget-handler" data-name="'.$args['addon_name'].'" data-namespace="'.base64_encode($args['addon_namespace']).'">
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$args['addon_title'].$args['preview_image'].'</h4>
                </li>';
    }
    public static function render_widgets_by_name_for_admin($args){
        $widget_class = $args['namespace'];
        if(class_exists($widget_class)){
            $instance = new $widget_class($args);
            if ($instance->enable()){
                return $instance->admin_render();
            }
        }
    }

    public static function render_widgets_by_name_for_frontend($args){
        $widget_class = $args['namespace'];
        if(class_exists($widget_class)){
            $instance = new $widget_class($args);
            if ($instance->enable()){
                return $instance->frontend_render();
            }
        }
    }

    public static function render_frontend_pagebuilder_content_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'ASC')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'location' => $location,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }

    public static function get_saved_addons_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }
    public static function get_saved_addons_for_dynamic_page($page_type,$page_id): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_page_type' => $page_type,'addon_page_id' => $page_id])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }
    public static function render_frontend_pagebuilder_content_for_dynamic_page($page_type,$page_id): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_page_type' => $page_type,'addon_page_id' => $page_id])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
//                'location' => $location,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }
}