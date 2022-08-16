<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Advertisement;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Language;
use App\Blog;
use App\Newsletter;
use App\Poll;
use App\User;
use App\VisitorInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsFacade;
use Spatie\Analytics\Period;

class AdminDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
        $this->middleware('permission:appearance-menubar-settings',['only'=>['menubar_settings','update_menubar_settings']]);
        $this->middleware('permission:appearance-home-variant',['only'=>['home_variant','update_home_variant']]);
    }
    public function adminIndex(){

        $all_blogs = Blog::count();
        $total_admin = Admin::count();
        $total_user = User::count();

        $all_poll = Poll::count();
        $total_advertisement = Advertisement::count();
        $total_subscriber = Newsletter::count();


        $data_url = [
            'admin' => route('admin.new.user'),
            'user' => route('admin.frontend.new.user'),
            'blog' => route('admin.blog.new'),
            'poll' => route('admin.polls.new'),
            'advertisement' => route('admin.advertisement.new'),
            'subscriber' => route('admin.newsletter'),
        ];

        return view('backend.admin-home')->with([
            'blog_count' => $all_blogs,
            'total_admin' => $total_admin,
            'total_user' => $total_user,
            'all_poll' => $all_poll,
            'total_advertisement' => $total_advertisement,
            'total_subscriber' => $total_subscriber,
            'data_url'=> $data_url,
        ]);
    }

    public function lang_change_backend(Request $request)
    {
        $data = $request->lang ?? LanguageHelper::default_slug();
        return response()->json($data);
    }

    public function admin_settings(){
        return view('auth.admin.settings');
    }
    public function admin_profile_update(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'image' => 'nullable|string',
             'description'=> 'nullable',
            'designation'=> 'nullable',
            'facebook_url' => 'nullable|string',
            'twitter_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'linkedin_url' => 'nullable|string',
        ]);

        Admin::find(Auth::user()->id)->update([
            'name'=>$request->name,
            'email' => $request->email ,
            'image' => $request->image ,
            'description' => $request->description,
            'designation' => $request->designation,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'instagram_url' => $request->instagram_url,
            'linkedin_url' => $request->linkedin_url,
        ]);
        return redirect()->back()->with(['msg' => __('Profile Update Success' ), 'type' => 'success']);
    }
    public function admin_password_chagne(Request $request){
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = Admin::findOrFail(Auth::guard('admin')->user()->id);

        if (Hash::check($request->old_password ,$user->password)){

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();

            return redirect()->route('admin.login')->with(['msg'=> __('Password Changed Successfully'),'type'=> 'success']);
        }

        return redirect()->back()->with(['msg'=> __('Somethings Going Wrong! Please Try Again or Check Your Old Password'),'type'=> 'danger']);
    }
    public function adminLogout(){
        Auth::logout();
        return redirect()->route('admin.login')->with(['msg'=>__('You Logged Out !!'),'type'=> 'danger']);
    }

    public function admin_profile(){
        return view('auth.admin.edit-profile');
    }
    public function admin_password(){
        return view('auth.admin.change-password');
    }


    public function menubar_settings(){
        $all_languages = Language::all();
        return view('backend.pages.menubar-settings')->with(['all_languages' => $all_languages]);;
    }

    public function update_menubar_settings(Request $request){
        $all_languages = Language::all();

        foreach ($all_languages as $lang){
            $this->validate($request, [
                'menubar_button_'.$lang->slug.'_text'=> 'nullable|string',
                'menubar_button_'.$lang->slug.'_url'=> 'nullable|string',
            ]);

            $fields = [
                'menubar_button_'.$lang->slug.'_text',
                'menubar_button_'.$lang->slug.'_url',
            ];
            foreach ($fields as $field){
                if ($request->has($field)){
                    update_static_option($field,$request->$field);
                }
            }
            update_static_option('menubar_button',$request->menubar_button);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function cache_settings(){
          return view('backend.general-settings.cache-settings');
    }

    public function update_cache_settings(Request $request){

         $this->validate($request,[
            'cache_type' => 'required|string'
        ]);

        Artisan::call($request->cache_type.':clear');

        return redirect()->back()->with(['msg'=> __('Cache Cleaned...') ,'type'=> 'success']);
    }

    public function dark_mode_toggle(Request $request){
        if($request->mode == 'off'){
            update_static_option('site_admin_dark_mode','on');
        }
        if($request->mode == 'on'){
            update_static_option('site_admin_dark_mode','off');
        }

        return response()->json(['status'=>'done']);
    }


    public function get_visited_url_data(Request $request){
        $all_data_by_view = VisitorInfo::select('visited_url')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy( 'visited_url');
           return $this->similler_data($all_data_by_view);
    }

    public function get_chart_data_device(Request $request){
        $all_data_by_device = VisitorInfo::select('device')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy( 'device');

        return $this->similler_data($all_data_by_device);
    }


    public function get_chart_data_country(Request $request){
        $all_data_by_country = VisitorInfo::select('country')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy( 'country');

       return $this->similler_data($all_data_by_country);
    }

    public function get_chart_data_os(Request $request){
        $all_data_by_os = VisitorInfo::select('os')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy( 'os');

         return $this->similler_data($all_data_by_os);
    }

    public function get_chart_data_browser(Request $request){
        $all_data_by_browser = VisitorInfo::select('browser')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy( 'browser');

        return $this->similler_data($all_data_by_browser);
    }


    private function similler_data($data){

        $labels = [];
        $counts = [];
        foreach ($data as $name => $item){
            $labels[] = $name;
            $counts[] = $item->count();
        }

        return response()->json( [
            'labels' => $labels,
            'data' => $counts
        ]);

    }

}
