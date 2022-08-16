<?php

namespace App\Http\Controllers;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\SubscriberMessage;
use App\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:newsletter-list|newsletter-create|newsletter-mail-send|newsletter-delete',['only' => ['index']]);
        $this->middleware('permission:newsletter-create',['only' => ['add_new_sub']]);
        $this->middleware('permission:newsletter-send-mail',['only' => ['send_mail_all_index','send_mail','verify_mail_send']]);
        $this->middleware('permission:newsletter-delete',['only' => ['delete','bulk_action']]);
    }

    public function index(){
        $all_subscriber = Newsletter::all();
        return view('backend.newsletter.newsletter-index')->with(['all_subscriber' => $all_subscriber]);
    }

    public function send_mail(Request $request){

        $this->validate($request,[
           'email' => 'required|email',
           'subject' => 'required',
           'message' => 'required',
        ]);

        $data = [
          'email' => $request->email,
          'subject' => $request->subject,
          'message' => $request->message,
        ];

        try {
            Mail::to($request->email)->send(new SubscriberMessage($data));
        }catch (\Exception $ex){
            return redirect()->back()->with(FlashMsg::item_delete($ex->getMessage()));
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success...'),
            'type' => 'success'
        ]);
    }
    public function delete($id){
        Newsletter::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Subscriber Delete Success....'),'type' => 'danger']);
    }

    public function send_mail_all_index(){
        return view('backend.newsletter.send-main-to-all');
    }

    public function send_mail_all(Request $request){
        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
        ]);
        $all_subscriber = Newsletter::all();

        foreach ($all_subscriber as $subscriber){
            $data = [
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            try {
                Mail::to($subscriber->email)->send(new SubscriberMessage($data));
            }catch (\Exception $ex){
                return redirect()->back()->with(FlashMsg::item_delete($ex->getMessage()));
            }
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success..'),
            'type' => 'success'
        ]);
    }

    public function add_new_sub(Request $request){
        $this->validate($request,[
           'email' => 'required|email|unique:newsletters'
        ],
        [
            'email.required' => __('email field required')
        ]);

        Newsletter::create($request->all());
        return redirect()->back()->with([
            'msg' => __('New Subscriber Added..'),
            'type' => 'success'
        ]);
    }

    public function bulk_action(Request $request){
        $all = Newsletter::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function verify_mail_send(Request $request){
        $subscriber_details = Newsletter::findOrFail($request->id);
        $token = $subscriber_details->token ?? Str::random(32);
        if (empty($subscriber_details->token)){
            $subscriber_details->token = $token;
            $subscriber_details->save();
        }
        $message = __('verify your email to get all news from '). get_static_option('site_'.get_default_language().'_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('subscriber.verify', ['token' => $token]) . '">' . __('verify email') . '</a></div>';
        $data = [
            'message' => $message,
            'subject' => __('verify your email')
        ];
        //send verify mail to newsletter subscriber
        Mail::to($subscriber_details->email)->send(new BasicMail($data));

        return redirect()->back()->with([
            'msg' => __('verify mail send success'),
            'type' => 'success'
        ]);
    }
}
