<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class FrontendUserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete',['only' => 'all_user']);
        $this->middleware('permission:user-create',['only' => ['new_user','new_user_add']]);
        $this->middleware('permission:user-delete',['only' => ['bulk_action','new_user_delete']]);
        $this->middleware('permission:user-edit',['only' => ['user_password_change','user_update','email_status']]);
    }

    public function all_user()
    {
        $all_user = User::all();
        return view('backend.frontend-user.all-user')->with(['all_user' => $all_user]);
    }
    public function user_password_change(Request $request)
    {
        $this->validate(
            $request,
            [
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'password.required' => __('password is required'),
                'password.confirmed' => __('password does not matched'),
                'password.min' => __('password minimum length is 8'),
            ]
        );
        $user = User::findOrFail($request->ch_user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with(['msg' => __('Password Change Success..'), 'type' => 'success']);
    }

    public function user_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'address' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:191',
            'facebook_url' => 'nullable|string',
            'twitter_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'linkedin_url' => 'nullable|string',
        ], [
            'name.required' => __('Name is required'),
            'email.required' => __('Email is required'),
        ]);

        User::find($request->user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'image' => $request->image,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
            'description' => $request->description,
            'designation' => $request->designation,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'linkedin_url' => $request->linkedin_url,
        ]);
        return redirect()->back()->with(['msg' => __('User Profile Update Success..'), 'type' => 'success']);
    }

    public function new_user_delete(Request $request, $id)
    {
        User::find($id)->delete();
        return redirect()->back()->with(['msg' => __('User Profile Deleted..'), 'type' => 'danger']);
    }

    public function new_user()
    {
        return view('backend.frontend-user.add-new-user');
    }

    public function new_user_add(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|max:191|unique:users',
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'address' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:191',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'description' => $request->description,
            'designation' => $request->designation,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'linkedin_url' => $request->linkedin_url,
        ]);

        return redirect()->back()->with(['msg' => __('New User Created..'), 'type' => 'success']);
    }
    public function bulk_action(Request $request)
    {
        $all = User::find($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }
    public function email_status(Request $request)
    {
        User::where('id', $request->user_id)->update([
            'email_verified' => $request->email_verified == 0 ? 1 : 0
        ]);
        return redirect()->back()->with(['msg' => __('Email Verify Status Changed..'), 'type' => 'success']);
    }

    public function permission_settings($id)
    {
        $user = User::find($id);
        return view('backend.frontend-user.permission-settings',compact('user'));
    }

    public function update_permission_settings(Request $request)
    {
        $this->validate($request, [
            'auto_post_approval' => 'nullable|string',
            'is_banned' => 'nullable|string',
            'post_permission' => 'nullable|string',
        ]);

        $data = User::findOrFail($request->id);

        if($request->auto_post_approval){
            $data->auto_post_approval = 1;
        }else{
            $data->auto_post_approval = 0;
        }

        if($request->is_banned){
            $data->is_banned = 1;
        }else{
            $data->is_banned = 0;
        }

        if($request->post_permission){
            $data->post_permission = 1;
        }else{
            $data->post_permission = 0;
        }

        $data->save();

        return redirect()->back()->with(FlashMsg::settings_update('Permission Settings Updated'));
    }
}
