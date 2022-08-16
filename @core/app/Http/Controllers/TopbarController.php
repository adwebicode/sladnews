<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Language;
use App\SocialIcon;
use App\TopbarInfo;
use Illuminate\Http\Request;

class TopbarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:appearance-topbar-settings',['only' => ['index','new_support_info','update_support_info',
            'delete_support_info','bulk_action','new_social_item','update_social_item','delete_social_item']]);
        $this->middleware('permission:appearance-leftbar-settings',['only' => ['leftbar_settings','update_leftbar_settings',]]);
    }
    public function index(){
        $all_social_icons = SocialIcon::all();
        $all_topbar_infos = TopbarInfo::all();

        return view('backend.pages.topbar-settings')->with([
            'all_topbar_infos' => $all_topbar_infos,
            'all_social_icons' => $all_social_icons,
        ]);
    }
    public function new_support_info(Request $request){
        $this->validate($request,[
           'title' => 'required|string',
           'url' => 'required|string',
        ]);
        $topbar = new TopbarInfo();
        $topbar->title = $request->title;
        $topbar->url = $request->url;
        $topbar->save();

        return redirect()->back()->with(FlashMsg::item_new());
    }

    public function update_support_info(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'url' => 'required|string|max:191'
        ]);

        TopbarInfo::find($request->id)->update([
            'title' => $request->title,
            'url' => $request->url,
        ]);
        return redirect()->back()->with([
            'msg' => __('Support Info Item Updated..'),
            'type' => 'success'
        ]);
    }
    public function delete_support_info(Request $request,$id){
        TopbarInfo::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete());
    }
    public function bulk_action(Request $request){
        $all = TopbarInfo::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function new_social_item(Request $request){
        $this->validate($request,[
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        SocialIcon::create($request->all());

        return redirect()->back()->with([
            'msg' => 'New Social Item Added...',
            'type' => 'success'
        ]);
    }
    public function update_social_item(Request $request){
        $this->validate($request,[
            'icon' => 'required|string',
            'url' => 'required|string',
        ]);

        SocialIcon::find($request->id)->update([
            'icon' => $request->icon,
            'url' => $request->url,
        ]);

        return redirect()->back()->with([
            'msg' => 'Social Item Updated...',
            'type' => 'success'
        ]);
    }
    public function delete_social_item(Request $request,$id){
        SocialIcon::find($id)->delete();
        return redirect()->back()->with([
            'msg' => 'Social Item Deleted...',
            'type' => 'danger'
        ]);
    }

    public function leftbar_settings(){
        return view('backend.pages.leftbar-settings');
    }

    public function update_leftbar_settings(Request $request){
        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'leftbar_social_item_show' => 'nullable|string',
                'leftbar_show_hide' => 'nullable|string',
                'leftbar_blog_item_show' => 'nullable|string',
                'leftbar_tag_item_show' => 'nullable|string',

                'leftbar_social_'.$lang->slug.'_title' => 'nullable|string',
                'leftbar_blog_'.$lang->slug.'_title' => 'nullable|string',
                'leftbar_tag_'.$lang->slug.'_title' => 'nullable|string',
            ]);
            $leftbar_social_title = 'leftbar_social_' . $lang->slug . '_title';
            $leftbar_category_title = 'leftbar_blog_' . $lang->slug . '_title';
            $leftbar_tag_title = 'leftbar_tag_' . $lang->slug . '_title';

            update_static_option($leftbar_social_title, $request->$leftbar_social_title);
            update_static_option($leftbar_category_title, $request->$leftbar_category_title);
            update_static_option($leftbar_tag_title, $request->$leftbar_tag_title);
        }

        $all_fields = [
            'leftbar_show_hide',
            'leftbar_social_item_show',
            'leftbar_blog_item_show',
            'leftbar_tag_item_show',
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function header_banner_settings(){
        return view('backend.pages.header-banner-settings');
    }

    public function update_header_banner_settings(Request $request){

        $this->validate($request, [
            'home_page_one_banner' => 'nullable|string',
            'home_page_four_banner' => 'nullable|string',
            'home_page_five_banner' => 'nullable|string',
            'home_page_one_banner_url' => 'nullable|string',
            'home_page_four_banner_url' => 'nullable|string',
            'home_page_five_banner_url' => 'nullable|string',
        ]);

        $all_fields = [
            'home_page_one_banner',
            'home_page_four_banner',
            'home_page_five_banner',
            'home_page_one_banner_url',
            'home_page_four_banner_url',
            'home_page_five_banner_url'
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }
}
