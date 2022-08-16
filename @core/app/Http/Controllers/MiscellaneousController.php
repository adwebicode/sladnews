<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;

class MiscellaneousController extends Controller
{
    public function __construct() {
          $this->middleware('auth:admin');
//        $this->middleware('permission:appearance-menubar-settings',['only'=>['menubar_settings','update_menubar_settings']]);
//        $this->middleware('permission:appearance-home-variant',['only'=>['home_variant','update_home_variant']]);
    }


    public function misc_index()
    {
        return view('backend.pages.misc-settings');
    }

    public function misc_update(Request $request)
    {

        $this->validate($request, [
            'terms_of_service_url' => 'nullable|string',
            'privacy_policy_url' => 'nullable|string',
            'condition_url' => 'nullable|string',
        ]);
        $fields = [
            'terms_of_service_url',
            'privacy_policy_url',
            'condition_url',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }


}
