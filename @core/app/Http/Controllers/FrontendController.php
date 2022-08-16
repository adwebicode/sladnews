<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Advertisement;
use App\Facades\GlobalLanguage;
use App\Facades\InstagramFeed;
use App\Helpers\FlashMsg;
use App\Helpers\HomePageStaticSettings;
use App\Helpers\LanguageHelper;
use App\Mail\BasicMail;
use App\Newsletter;
use App\Page;
use App\Blog;
use App\BlogCategory;
use App\HeaderSlider;
use App\Language;
use App\Mail\AdminResetEmail;
use App\Poll;
use App\PollInfo;
use App\StaticOption;
use App\Tag;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use function Sodium\increment;


class FrontendController extends Controller

{
    public function index()
    {
        $home_page_id = get_static_option('home_page');
        $page_details = Page::find($home_page_id);
        if (empty($page_details)){
            // show any notice or
        }

        $static_field_data = StaticOption::whereIn('option_name',HomePageStaticSettings::get_home_field(get_static_option('home_page_variant')))->get()->mapWithKeys(function ($item) {
            return [$item->option_name => $item->option_value];
        })->toArray();

        return view('frontend.frontend-home')->with([
          'static_field_data' => $static_field_data,
            'page_details' => $page_details
        ]);
    }


    public function dynamic_single_page($slug)
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $page_post = Page::usingLocale($current_lang)->where('slug', $slug)->first();
        if(empty($page_post)){
            abort(404);
        }
        
        //check for blog page
        $blog_page_slug = get_page_slug(get_static_option('blog_page'),'blog');
        if($slug === $blog_page_slug){
            $all_blogs = Blog::select('id','title','image','slug','created_at','category_id','author')->where(['status'=>'publish'])->paginate(10);
            return view('blog::frontend.blog.blog',compact('all_blogs'));
        }

        return view('frontend.pages.dynamic-single')->with([
            'page_post' => $page_post
        ]);
    }

    public function poll_vote_store(Request $request)
    {
        $request->validate([
           'name'=> 'required|string|max:191',
           'email'=> 'required|email|unique:poll_infos,email',
           'vote_name'=> 'required',
        ],[
            'email.unique' => __('You have already voted..!'),
        ]);

            PollInfo::create([
                'poll_id' => $request->id,
                'name' => purify_html($request->name),
                'email' => purify_html($request->email),
                'vote_name' => purify_html($request->vote_name),
            ]);

            return response()->json([
                'msg' => __('Your vote submitted successfully..'),
                'type' => 'success',
                'status' => 'ok'
            ]);

    }


    public function ajax_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required'   => __('Username required'),
            'password.required' => __('Password required'),
            'password.min' => __('Password length must be 6 characters')
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'danger',
                'status' => 'valid'
            ]);
        }
        return response()->json([
            'msg' => __('User name and password do not match'),
            'type' => 'danger',
            'status' => 'invalid'
        ]);
    }

    public function showAdminForgetPasswordForm()
    {
        return view('auth.admin.forget-password');
    }
    public function sendAdminForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = Admin::where('username', $request->username)->orWhere('email', $request->username)->first();
        $token_id = Str::random(30);
        $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
        if (empty($existing_token)) {
            DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
        }
        $message = __('Here is you password reset link, If you did not request to reset your password just ignore this mail.').' <a style="background-color:#d0f1ff;color:#fff;text-decoration:none;padding: 10px 15px;border-radius: 3px;display: block;width: 130px;margin-top: 20px;" href="' . route('admin.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">'.__('Click Reset Password').'</a>';
        if (sendEmail($user_info->email, $user_info->username, __('Reset Your Password'), $message)) {
            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }
        return redirect()->back()->with([
            'msg' => __('Something Wrong, Please Try Again!!'),
            'type' => 'danger'
        ]);
    }
    public function showAdminResetPasswordForm($username, $token)
    {
        return view('auth.admin.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }
    public function AdminResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = Admin::where('username', $request->username)->first();
        $user = Admin::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('admin.login')->with(['msg' =>__( 'Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);
        return redirect()->route('homepage');
    }


    public function showUserForgetPasswordForm()
    {
        return view('frontend.user.forget-password');
    }
    public function sendUserForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        if (!empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }
            $message = __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . ' <a class="btn" href="' . route('user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '">' . __('Click Reset Password') . '</a>';
            $data = [
                'username' => $user_info->username,
                'message' => $message
            ];
            try{
                Mail::to($user_info->email)->send(new AdminResetEmail($data));
            }catch(\Exception $e){
                return redirect()->back()->with([
                    'msg' =>  $e->getMessage(),
                    'type' => 'danger'
                ]);
            }

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }
        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger'
        ]);
    }
    public function showUserResetPasswordForm($username, $token)
    {
        return view('frontend.user.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }
    public function UserResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = User::where('username', $request->username)->first();
        $user = User::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }


    public function user_logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function home_advertisement_click_store(Request $request)
    {
         Advertisement::where('id',$request->id)->increment('click');
         return response()->json('success');
    }

    public function home_advertisement_impression_store(Request $request)
    {
        Advertisement::where('id',$request->id)->increment('impression');
        return response()->json('success');
    }


    public function subscribe_newsletter(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:newsletters'
        ]);
        $verify_token = Str::random(32);
        Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);
        $message = __('verify your email to get all news from '). get_static_option('site_'.get_default_language().'_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('subscriber.verify', ['token' => $verify_token]) . '">' . __('verify email') . '</a></div>';
        $data = [
            'message' => $message,
            'subject' => __('verify your email')
        ];
        //send verify mail to newsletter subscriber
        try {
            Mail::to($request->email)->send(new BasicMail($data));
        }catch (\Exception $e){
            return redirect()->back()->with(FlashMsg::item_delete($e->getMessage()));
        }

        return response()->json([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    public function subscriber_verify(Request $request){
        $newsletter = Newsletter::where('token',$request->token)->first();
        $title = __('Sorry');
        $description = __('your token is expired');
        if (!empty($newsletter)){
            Newsletter::where('token',$request->token)->update([
                'verified' => 1
            ]);
            $title = __('Thanks');
            $description = __('we are really thankful to you for subscribe our newsletter');
        }
        return view('frontend.thankyou',compact('title','description'));
    }


    public function dark_mode_toggle(Request $request){
        if($request->mode == 'off'){
            update_static_option('site_frontend_dark_mode','on');
        }
        if($request->mode == 'on'){
            update_static_option('site_frontend_dark_mode','off');
        }

        return response()->json(['status'=>'done']);
    }




}
