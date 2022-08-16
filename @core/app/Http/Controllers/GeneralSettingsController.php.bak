<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Mail\BasicMail;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Language;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Sitemap\SitemapGenerator;



class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:general-settings-site-identity',['only'=>['site_identity','update_site_identity']]);
        $this->middleware('permission:general-settings-reading-settings',['only'=>['reading','update_reading']]);
        $this->middleware('permission:general-settings-global-navbar-settings',['only'=>['global_variant_navbar','update_global_variant_navbar']]);
        $this->middleware('permission:general-settings-global-footer-settings',['only'=>['global_variant_footer','update_global_variant_footer']]);
        $this->middleware('permission:general-settings-basic-settings',['only'=>['basic_settings','update_basic_settings']]);
        $this->middleware('permission:general-settings-color-settings',['only'=>['color_settings','update_color_settings']]);
        $this->middleware('permission:general-settings-typography-settings',['only'=>['typography_settings','get_single_font_variant','update_typography_settings']]);
        $this->middleware('permission:general-settings-seo-settings',['only'=>['seo_settings','update_seo_settings']]);
        $this->middleware('permission:general-settings-third-party-scripts',['only'=>['update_scripts_settings','scripts_settings']]);
        $this->middleware('permission:general-settings-email-template',['only'=>['email_template_settings','update_email_template_settings']]);
        $this->middleware('permission:general-settings-email-settings',['only'=>['email_settings','update_email_settings']]);
        $this->middleware('permission:general-settings-smtp-settings',['only'=>['smtp_settings','update_smtp_settings','test_smtp_settings']]);
        $this->middleware('permission:general-settings-page-settings',['only'=>['page_settings','update_page_settings']]);
        $this->middleware('permission:general-settings-payment-gateway-settings',['only'=>['payment_settings','update_payment_settings']]);
        $this->middleware('permission:general-settings-custom-css',['only'=>['custom_css_settings','update_custom_css_settings']]);
        $this->middleware('permission:general-settings-custom-js',['only'=>['custom_js_settings','update_custom_js_settings']]);
        $this->middleware('permission:general-settings-licence-settings',['only'=>['license_settings','update_license_settings']]);
        $this->middleware('permission:general-settings-cache-settings',['only'=>['cache_settings','update_cache_settings']]);
    }

    public function reading()
    {
        $all_home_pages = Page::where(['status'=> 'publish'])->get();
        return view('backend.general-settings.reading',compact('all_home_pages'));
    }
    public function update_reading(Request $request)
    {
        $this->validate($request, [
            'home_page' => 'nullable|string',
            'blog_page' => 'nullable|string',
        ]);
        $fields = [
            'home_page',
            'blog_page',
        ];
        foreach ($fields as $field) {
                update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function global_variant_navbar()
    {
        return view('backend.general-settings.navbar-global-variant');
    }
    public function update_global_variant_navbar(Request $request)
    {
        $this->validate($request, [
            'global_navbar_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_navbar_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function global_variant_footer()
    {
        return view('backend.general-settings.footer-global-variant');
    }
    public function update_global_variant_footer(Request $request)
    {
        $this->validate($request, [
            'global_footer_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_footer_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function site_identity()
    {
        return view('backend.general-settings.site-identity');
    }
    public function update_site_identity(Request $request)
    {
        $this->validate($request, [
            'site_logo' => 'nullable|string',
            'site_white_logo' => 'nullable|string',
            'site_logo_two' => 'nullable|string',
            'site_favicon' => 'nullable|string',
        ]);
        $fields = [
            'site_logo',
            'site_white_logo',
            'site_logo_two',
            'site_favicon',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function basic_settings()
    {
        $all_languages = Language::all();
        return view('backend.general-settings.basic')->with(['all_languages' => $all_languages]);
    }

    public function update_basic_settings(Request $request)
    {
        $this->validate($request, [
            'language_select_option' => 'nullable|string',
            'disable_user_email_verify' => 'nullable|string',
            'site_maintenance_mode' => 'nullable|string',
            'admin_loader_animation' => 'nullable|string',
            'site_loader_animation' => 'nullable|string',
            'site_force_ssl_redirection' => 'nullable|string',
            'admin_panel_rtl_status' => 'nullable|string',
            'site_google_captcha_enable' => 'nullable|string',
            'site_title' => 'nullable|string',
            'site_tag_line' => 'nullable|string',
            'site_footer_copyright' => 'nullable|string',
        ]);

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_' . $lang->slug . '_title' => 'nullable|string',
                'site_' . $lang->slug . '_tag_line' => 'nullable|string',
                'site_' . $lang->slug . '_footer_copyright' => 'nullable|string',
            ]);
            $_title = 'site_' . $lang->slug . '_title';
            $_tag_line = 'site_' . $lang->slug . '_tag_line';
            $_footer_copyright = 'site_' . $lang->slug . '_footer_copyright';
            update_static_option($_title, $request->$_title);
            update_static_option($_tag_line, $request->$_tag_line);
            update_static_option($_footer_copyright, $request->$_footer_copyright);
        }

        $all_fields = [
            'language_select_option',
            'disable_user_email_verify',
            'site_maintenance_mode',
            'admin_loader_animation',
            'site_loader_animation',
            'admin_panel_rtl_status',
            'site_force_ssl_redirection',
            'site_google_captcha_enable',
            'login_show_hide',
            'register_show_hide',
            'dark_mode_show_hide',
            'timezone'
        ];
        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }

        $timezone = get_static_option('timezone');
        if (!empty($timezone)) {
            setEnvValue(['APP_TIMEZONE' => $timezone]);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }

    public function color_settings()
    {
        return view('backend.general-settings.color-settings');
    }

    public function update_color_settings(Request $request)
    {
        $this->validate($request, [
            'site_main_color_one' => 'nullable|string',
            'site_main_color_two' => 'nullable|string',
            'site_secondary_color' => 'nullable|string',
            'site_heading_color' => 'nullable|string',
            'site_paragraph_color' => 'nullable|string',
            'site_bg_light_one' => 'nullable|string',
            'site_bg_light_two' => 'nullable|string',
            'site_bg_dark_one' => 'nullable|string',
            'site_bg_dark_two' => 'nullable|string',
            'site_bg_dark_three' => 'nullable|string',
            'site_bg_dark_four' => 'nullable|string',
            'site_bg_dark_five' => 'nullable|string',
        ]);

        $all_fields = [
          'site_main_color_one',
          'site_main_color_two',
          'site_secondary_color',
          'site_heading_color',
          'site_paragraph_color',
          'site_bg_light_one',
          'site_bg_light_two',
          'site_bg_dark_one',
          'site_bg_dark_two',
          'site_bg_dark_three',
          'site_bg_dark_four',
          'site_bg_dark_five',
        ];

        foreach ($all_fields as $field) {
            update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function seo_settings()
    {
        $all_languages = Language::all();
        return view('backend.general-settings.seo')->with(['all_languages' => $all_languages]);
    }
    public function update_seo_settings(Request $request)
    {
        $all_languages = Language::all();
        foreach ($all_languages as $lang) {
            $this->validate($request, [
                'site_meta_' . $lang->slug . '_tags' => 'nullable|string',
                'site_meta_' . $lang->slug . '_description' => 'nullable|string',
                'og_meta_' . $lang->slug . '_title' => 'nullable|string',
                'og_meta_' . $lang->slug . '_description' => 'nullable|string',
                'og_meta_' . $lang->slug . '_site_name' => 'nullable|string',
                'og_meta_' . $lang->slug . '_url' => 'nullable|string',
                'og_meta_' . $lang->slug . '_image' => 'nullable|string',
            ]);
            $fields = [
                'site_meta_' . $lang->slug . '_tags',
                'site_meta_' . $lang->slug . '_description',
                'og_meta_' . $lang->slug . '_title',
                'og_meta_' . $lang->slug . '_description',
                'og_meta_' . $lang->slug . '_site_name',
                'og_meta_' . $lang->slug . '_url',
                'og_meta_' . $lang->slug . '_image'
            ];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    update_static_option($field, $request->$field);
                }
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function scripts_settings()
    {
        return view( 'backend.general-settings.thid-party');
    }

    public function update_scripts_settings(Request $request)
    {

        $this->validate($request, [
            'tawk_api_key' => 'nullable|string',
            'google_adsense_id' => 'nullable|string',
            'site_third_party_tracking_code' => 'nullable|string',
            'site_google_analytics' => 'nullable|string',
            'site_google_captcha_v3_secret_key' => 'nullable|string',
            'site_google_captcha_v3_site_key' => 'nullable|string',
        ]);

        update_static_option('site_disqus_key', $request->site_disqus_key);
        update_static_option('site_google_analytics', $request->site_google_analytics);
        update_static_option('tawk_api_key', $request->tawk_api_key);
        update_static_option('site_third_party_tracking_code', $request->site_third_party_tracking_code);
        update_static_option('site_google_captcha_v3_site_key', $request->site_google_captcha_v3_site_key);
        update_static_option('site_google_captcha_v3_secret_key', $request->site_google_captcha_v3_secret_key);

        $fields = [
            'site_google_captcha_v3_secret_key',
            'site_google_captcha_v3_site_key',
            'site_third_party_tracking_code',
            'site_google_analytics',
            'tawk_api_key',
            'enable_google_login',
            'google_client_id',
            'google_client_secret',
            'enable_facebook_login',
            'facebook_client_id',
            'facebook_client_secret',
            'google_adsense_publisher_id',
            'google_adsense_customer_id',
            'enable_google_adsense',
            'instagram_access_token',
        ];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }

        setEnvValue([
            'GOOGLE_ADSENSE_PUBLISHER_ID' => $request->google_adsense_publisher_id,
            'GOOGLE_ADSENSE_CUSTOMER_ID' => $request->google_adsense_customer_id,
            'FACEBOOK_CLIENT_ID' => $request->facebook_client_id,
            'FACEBOOK_CLIENT_SECRET' => $request->facebook_client_secret,
            'FACEBOOK_CALLBACK_URL' => route('facebook.callback'),
            'GOOGLE_CLIENT_ID' => $request->google_client_id,
            'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
            'GOOGLE_CALLBACK_URL' => route('google.callback'),
        ]);


        return redirect()->back()->with(['msg' => __('Third Party Scripts Settings Updated..'), 'type' => 'success']);
    }
    public function email_template_settings()
    {
        return view('backend.general-settings.email-template');
    }
    public function update_email_template_settings(Request $request)
    {

        $this->validate($request, [
            'site_global_email' => 'required|string',
            'site_global_email_template' => 'required|string',
        ]);

        $save_data = [
            'site_global_email',
            'site_global_email_template'
        ];
        foreach ($save_data as $item) {
            if (empty($request->$item)) {
                continue;
            }
            update_static_option($item, $request->$item);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function cache_settings()
    {
        return view('backend.general-settings.cache-settings');
    }
    public function email_settings()
    {
        $all_languages = Language::all();
        return view('backend.general-settings.email-settings')->with(['all_languages' => $all_languages]);
    }
    public function update_email_settings(Request $request)
    {
        $all_languages = Language::all();
        foreach ($all_languages as $lang) {
            $this->validate($request, [
                'contact_mail_' . $lang->slug . '_success_message' => 'nullable|string',
                'order_mail_' . $lang->slug . '_success_message' => 'nullable|string',

            ]);
            $fields = [

                'contact_mail_' . $lang->slug . '_success_message',
                'order_mail_' . $lang->slug . '_success_message',
            ];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    update_static_option($field, $request->$field);
                }
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function update_cache_settings(Request $request)
    {

        $this->validate($request, [
            'cache_type' => 'required|string'
        ]);

        Artisan::call($request->cache_type . ':clear');

        return redirect()->back()->with(['msg' => __('Cache Cleaned'), 'type' => 'success']);
    }
    public function typography_settings()
    {
        $all_google_fonts = file_get_contents('assets/frontend/webfonts/google-fonts.json');
        return view('backend.general-settings.typograhpy')->with(['google_fonts' => json_decode($all_google_fonts)]);
    }
    public function get_single_font_variant(Request $request)
    {
        $all_google_fonts = file_get_contents('assets/frontend/webfonts/google-fonts.json');
        $decoded_fonts = json_decode($all_google_fonts, true);
        return response()->json($decoded_fonts[$request->font_family]);
    }
    
    
    public function update_typography_settings(Request $request)
    {
        $this->validate($request, [
            'body_font_family' => 'required|string|max:191',
            'body_font_variant' => 'required',
            'heading_font' => 'nullable|string',
            'heading_font_family' => 'nullable|string|max:191',
            'heading_font_variant' => 'nullable',
        ]);

        $save_data = [
            'body_font_family',
            'heading_font_family',
            'heading_font_family_two',
        ];
        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }

        $body_font_variant = !empty($request->body_font_variant) ?  $request->body_font_variant : ['regular'];
        $heading_font_variant = !empty($request->heading_font_variant) ?  $request->heading_font_variant : ['regular'];
        $heading_font_variant_two = !empty($request->heading_font_variant_two) ?  $request->heading_font_variant_two : ['regular'];

        update_static_option('heading_font', $request->heading_font);
        update_static_option('body_font_variant', serialize($body_font_variant));
        update_static_option('heading_font_variant', serialize($heading_font_variant));
        update_static_option('heading_font_variant_two', serialize($heading_font_variant_two));


        return redirect()->back()->with(['msg' => __('Typography Settings Updated..'), 'type' => 'success']);
    }
    
    
    
    public function smtp_settings()
    {
        return view('backend.general-settings.smtp-settings');
    }
    public function update_smtp_settings(Request $request)
    {

        $this->validate($request, [
            'site_smtp_mail_host' => 'required|string',
            'site_smtp_mail_mailer' => 'required|string',
            'site_smtp_mail_port' => 'required|string',
            'site_smtp_mail_username' => 'required|string',
            'site_smtp_mail_password' => 'required|string',
            'site_smtp_mail_encryption' => 'required|string'
        ]);

        $fields = [
            'site_smtp_mail_mailer',
            'site_smtp_mail_host',
            'site_smtp_mail_port',
            'site_smtp_mail_username',
            'site_smtp_mail_password',
            'site_smtp_mail_encryption',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        $env_val['MAIL_MAILER'] = !empty($request->site_smtp_mail_mailer) ? $request->site_smtp_mail_mailer : 'smtp';
        $env_val['MAIL_HOST'] = !empty($request->site_smtp_mail_host) ? $request->site_smtp_mail_host : 'YOUR_SMTP_MAIL_HOST';
        $env_val['MAIL_PORT'] = !empty($request->site_smtp_mail_port) ? $request->site_smtp_mail_port : 'YOUR_SMTP_MAIL_POST';
        $env_val['MAIL_USERNAME'] = !empty($request->site_smtp_mail_username) ? $request->site_smtp_mail_username : 'YOUR_SMTP_MAIL_USERNAME';
        $env_val['MAIL_PASSWORD'] = !empty($request->site_smtp_mail_password) ? $request->site_smtp_mail_password : 'YOUR_SMTP_MAIL_USERNAME_PASSWORD';
        $env_val['MAIL_ENCRYPTION'] = !empty($request->site_smtp_mail_encryption) ? $request->site_smtp_mail_encryption : 'YOUR_SMTP_MAIL_ENCRYPTION';

        setEnvValue($env_val);

        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function test_smtp_settings(Request $request){
        $this->validate($request,[
            'subject' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'message' => 'required|string',
        ]);
        $res_data = [
            'msg' => __('Mail Send Success'),
            'type' => 'success'
        ];
        try {
            Mail::to($request->email)->send(new BasicMail([
                'subject' => $request->subject,
                'message' => $request->message
            ]));
        }catch (\Exception $e){
            return  redirect()->back()->with([
                'type' => 'danger',
                'msg' => $e->getMessage()
            ]);
        }

        if (Mail::failures()){
            $res_data = [
                'msg' => __('Mail Send Failed'),
                'type' => 'danger'
            ];
        }
        return redirect()->back()->with($res_data);
    }

    public function page_settings()
    {
        $all_languages = Language::all();
        return view('backend.general-settings.page-settings')->with(['all_languages' => $all_languages]);
    }

    public function update_page_settings(Request $request)
    {
        $all_languages = Language::all();
        $this->validate($request, [
            'gallery_page_slug'=> 'nullable|string|max:191',
            'blog_page_slug'=> 'nullable|string|max:191',
            'contact_page_slug'=> 'nullable|string|max:191',
        ]);
        $pages_list = ['gallery','blog','contact'];

        foreach ($pages_list as $slug_field) {
            $field = $slug_field.'_page_slug';
            update_static_option($field, Str::slug($request->$field));
        }
        foreach ($all_languages as $lang) {

            $this->validate($request,[
                'gallery_page_' . $lang->slug . '_name' => 'nullable|string',
                'gallery_page_' . $lang->slug . '_meta_tags' => 'nullable|string',
                'gallery_page_' . $lang->slug . '_meta_description' => 'nullable|string',
                'contact_page_' . $lang->slug . '_name' => 'nullable|string',
                'contact_page_' . $lang->slug . '_meta_tags' => 'nullable|string',
                'contact_page_' . $lang->slug . '_meta_description' => 'nullable|string',
                'blog_page_' . $lang->slug . '_name' => 'nullable|string',
                'blog_page_' . $lang->slug . '_meta_tags' => 'nullable|string',
                'blog_page_' . $lang->slug . '_meta_description' => 'nullable|string',
            ]);

            foreach ($pages_list as $field) {
                $field_name = $field.'_page_'. $lang->slug.'_name';
                $field_meta_tags = $field.'_page_'. $lang->slug.'_meta_tags';
                $field_meta_meta_description = $field.'_page_'. $lang->slug.'_meta_description';
                update_static_option($field_name, $request->$field_name);
                update_static_option($field_meta_tags, $request->$field_meta_tags);
                update_static_option($field_meta_meta_description, $request->$field_meta_meta_description);
            }
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function payment_settings()
    {
        return view('backend.general-settings.payment-gateway');
    }

    public function update_payment_settings(Request $request)
    {
        $this->validate($request, [
            'razorpay_preview_logo' => 'nullable|string|max:191',
            'stripe_preview_logo' => 'nullable|string|max:191',
            'paypal_gateway' => 'nullable|string|max:191',
            'paypal_preview_logo' => 'nullable|string|max:191',
            'paypal_client_id' => 'nullable|string|max:191',
            'paypal_secret' => 'nullable|string|max:191',
            'paytm_gateway' => 'nullable|string|max:191',
            'paytm_preview_logo' => 'nullable|string|max:191',
            'paytm_merchant_key' => 'nullable|string|max:191',
            'paytm_merchant_mid' => 'nullable|string|max:191',
            'paytm_merchant_website' => 'nullable|string|max:191',
            'site_global_currency' => 'nullable|string|max:191',
            'site_manual_payment_name' => 'nullable|string|max:191',
            'manual_payment_preview_logo' => 'nullable|string|max:191',
            'site_manual_payment_description' => 'nullable|string',
            'razorpay_key' => 'nullable|string|max:191',
            'razorpay_secret' => 'nullable|string|max:191',
            'stripe_publishable_key' => 'nullable|string|max:191',
            'stripe_secret_key' => 'nullable|string|max:191',
            'site_global_payment_gateway' => 'nullable|string|max:191',
            'paystack_merchant_email' => 'nullable|string|max:191',
            'paystack_preview_logo' => 'nullable|string|max:191',
            'paystack_public_key' => 'nullable|string|max:191',
            'paystack_secret_key' => 'nullable|string|max:191',
            'cash_on_delivery_gateway' => 'nullable|string|max:191',
            'cash_on_delivery_preview_logo' => 'nullable|string|max:191',
            'mollie_preview_logo' => 'nullable|string|max:191',
            'mollie_public_key' => 'nullable|string|max:191',
        ]);
        $save_data = [
            'cash_on_delivery_preview_logo',
            'stripe_preview_logo',
            'paystack_preview_logo',
            'paystack_public_key',
            'paystack_secret_key',
            'paystack_merchant_email',
            'razorpay_preview_logo',
            'paypal_preview_logo',
            'paypal_app_client_id',
            'paypal_app_secret',
            'paytm_preview_logo',
            'paytm_merchant_key',
            'paytm_merchant_mid',
            'paytm_merchant_website',
            'site_global_currency',
            'manual_payment_preview_logo',
            'site_manual_payment_name',
            'site_manual_payment_description',
            'razorpay_key',
            'razorpay_secret',
            'stripe_publishable_key',
            'stripe_secret_key',
            'site_global_payment_gateway',
            'site_usd_to_ngn_exchange_rate',
            'site_euro_to_ngn_exchange_rate',
            'mollie_public_key',
            'mollie_preview_logo',
            'flutterwave_preview_logo',
            'flutterwave_secret_key',
            'flutterwave_public_key',
            'site_currency_symbol_position',
            'site_default_payment_gateway',
        ];
        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }

        update_static_option('manual_payment_gateway', $request->manual_payment_gateway);
        update_static_option('paypal_gateway', $request->paypal_gateway);
        update_static_option('paytm_test_mode', $request->paytm_test_mode);
        update_static_option('paypal_test_mode', $request->paypal_test_mode);
        update_static_option('paytm_gateway', $request->paytm_gateway);
        update_static_option('razorpay_gateway', $request->razorpay_gateway);
        update_static_option('stripe_gateway', $request->stripe_gateway);
        update_static_option('paystack_gateway', $request->paystack_gateway);
        update_static_option('mollie_gateway', $request->mollie_gateway);
        update_static_option('cash_on_delivery_gateway', $request->cash_on_delivery_gateway);
        update_static_option('flutterwave_gateway', $request->flutterwave_gateway);

        $env_val['PAYSTACK_PUBLIC_KEY'] = $request->paystack_public_key ?: 'pk_test_081a8fcd9423dede2de7b4c3143375b5e5415290';
        $env_val['PAYSTACK_SECRET_KEY'] = $request->paystack_secret_key ?: 'sk_test_c874d38f8d08760efc517fc83d8cd574b906374f';
        $env_val['MERCHANT_EMAIL'] = $request->paystack_merchant_email ?: 'example@gmail.com';
        $env_val['MOLLIE_KEY'] = $request->mollie_public_key ?: 'test_SMWtwR6W48QN2UwFQBUqRDKWhaQEvw';
        $env_val['RAVE_PUBLIC_KEY'] = $request->flutterwave_public_key ?: 'FLWPUBK-d981d2a182ba72915b699603c2db82e0-X';
        $env_val['RAVE_SECRET_KEY'] = $request->flutterwave_secret_key ?: 'FLWSECK-e33b022937c2a64446dca55dbb7ceb08-X';
        $env_val['PAYPAL_MODE'] =  !empty($request->paypal_test_mode) ? 'sandbox' : 'live';
        $env_val['PAYPAL_CLIENT_ID'] = $request->paypal_app_client_id ?: 'ATx-SYQyPtXHw1bZaBDhJUZabxbutIqAqqZORgvgEoK_-9MrAkUzYkbt8pSnUyKNEdNN3aJt8tcpcY1I';
        $env_val['PAYPAL_SECRET'] = $request->paypal_app_secret ?: 'ELJCWPUabUnnMamfw5-ZxaUsvir3KAJrPnAcSeS11znsroi45HP0p7y7vGZcYsBsAAi7Ou6kelCpj69K';
        $env_val['PAYTM_ENVIRONMENT'] = !empty($request->paytm_test_mode) ? 'local' : 'production';
        $env_val['PAYTM_MERCHANT_ID'] = $request->paytm_merchant_mid ?: 'Digita57697814558795';
        $env_val['PAYTM_MERCHANT_KEY'] = '"' . $request->paytm_merchant_key . '"' ?: 'dv0XtmsPYpewNag&';
        $env_val['PAYTM_MERCHANT_WEBSITE'] = '"' . $request->paytm_merchant_website . '"' ?: 'WEBSTAGING';
        $env_val['PAYTM_CHANNEL'] = '"' . $request->paytm_channel . '"' ?: 'WEB';
        $env_val['PAYTM_INDUSTRY_TYPE'] = '"' . $request->paytm_industry_type . '"' ?: 'Retail';
        $env_val['RAVE_TITLE'] =  '"' . get_static_option('site_' . get_default_language() . '_title') . '"';
        $env_val['RAVE_ENVIRONMENT'] =  getenv('APP_ENV') === 'local' ? "staging" : "live";
        $env_val['FLW_PUBLIC_KEY'] = $request->flutterwave_public_key ?: 'FLWPUBK-d981d2a182ba72915b699603c2db82e0-X';
        $env_val['FLW_SECRET_KEY'] = $request->flutterwave_secret_key ?: 'FLWSECK-e33b022937c2a64446dca55dbb7ceb08-X';

        setEnvValue($env_val);

        $global_currency = get_static_option('site_global_currency');
        $currency_filed_name = 'site_' . strtolower($global_currency) . '_to_usd_exchange_rate';
        $inr_currency_filed_name = 'site_' . strtolower($global_currency) . '_to_inr_exchange_rate';
        $ngn_currency_filed_name = 'site_' . strtolower($global_currency) . '_to_ngn_exchange_rate';

        update_static_option('site_' . strtolower($global_currency) . '_to_usd_exchange_rate', $request->$currency_filed_name);
        update_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate', $request->$inr_currency_filed_name);
        update_static_option('site_' . strtolower($global_currency) . '_to_ngn_exchange_rate', $request->$ngn_currency_filed_name);

        return redirect()->back()->with(FlashMsg::settings_update());
    }
    public function custom_css_settings()
    {
        $custom_css = '/* Write Custom Css Here */';
        if (file_exists('assets/frontend/css/dynamic-style.css')) {
            $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
        }
        return view('backend.general-settings.custom-css')->with(['custom_css' => $custom_css]);
    }

    public function update_custom_css_settings(Request $request)
    {
        file_put_contents('assets/frontend/css/dynamic-style.css', $request->custom_css_area);

        return redirect()->back()->with(['msg' => __('Custom Style Successfully Added...'), 'type' => 'success']);
    }

    public function custom_js_settings()
    {
        $custom_js = '/* Write Custom js Here */';
        if (file_exists('assets/frontend/js/dynamic-script.js')) {
            $custom_js = file_get_contents('assets/frontend/js/dynamic-script.js');
        }
        return view('backend.general-settings.custom-js')->with(['custom_js' => $custom_js]);
    }

    public function update_custom_js_settings(Request $request)
    {
        file_put_contents('assets/frontend/js/dynamic-script.js', $request->custom_js_area);

        return redirect()->back()->with(['msg' => __('Custom Script Successfully Added...'), 'type' => 'success']);
    }
    public function gdpr_settings()
    {
        $all_languages = Language::all();
        return view('backend.general-settings.gdpr')->with(['all_languages' => $all_languages]);
    }

    public function update_gdpr_cookie_settings(Request $request)
    {

        $this->validate($request, [
            'site_gdpr_cookie_enabled' => 'nullable|string|max:191',
            'site_gdpr_cookie_expire' => 'required|string|max:191',
            'site_gdpr_cookie_delay' => 'required|string|max:191',
        ]);

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                "site_gdpr_cookie_" . $lang->slug . "_title" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_message" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_more_info_label" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_more_info_link" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_accept_button_label" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_decline_button_label" => 'nullable|string',
            ]);
            $_title = "site_gdpr_cookie_" . $lang->slug . "_title";
            $_message = "site_gdpr_cookie_" . $lang->slug . "_message";
            $_more_info_label = "site_gdpr_cookie_" . $lang->slug . "_more_info_label";
            $_more_info_link = "site_gdpr_cookie_" . $lang->slug . "_more_info_link";
            $_accept_button_label = "site_gdpr_cookie_" . $lang->slug . "_accept_button_label";
            $decline_button_label = "site_gdpr_cookie_" . $lang->slug . "_decline_button_label";

            $fields = [
                $_title,
                $_message,
                $_more_info_label,
                $_more_info_link,
                $_accept_button_label,
                $decline_button_label,
            ];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    update_static_option($field, $request->$field);
                }
            }
        }
        $fields = [
            'site_gdpr_cookie_delay',
            'site_gdpr_cookie_enabled',
            'site_gdpr_cookie_expire',
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function license_settings()
    {
        return view('backend.general-settings.license-settings');
    }

    /** @noinspection DuplicatedCode */
    public function update_license_settings(Request $request)
    {
        $this->validate($request, [
            'item_purchase_key' => 'required|string|max:191'
        ]);
        $response = Http::get('https://api.xgenious.com/license/new', [
            'purchase_code' => $request->item_purchase_key,
            'site_url' => url('/'),
            'item_unique_key' => 'levzDAKj06HVN4pL8W20PM2AaQgUi9Ev',
        ]);
        $result = $response->json();
        if ($response->ok()){
            update_static_option('item_purchase_key', $request->item_purchase_key);
            update_static_option('item_license_status', $result['license_status']);
            update_static_option('item_license_msg', $result['msg']);

            $type = 'verified' == $result['license_status'] ? 'success' : 'danger';
            setcookie("site_license_check", "", time() - 3600, '/');
            $license_info = [
                "item_license_status" => $result['license_status'],
                "last_check" => time(),
                "purchase_code" => get_static_option('item_purchase_key'),
                "xgenious_app_key" => env('XGENIOUS_API_KEY'),
                "author" => env('XGENIOUS_API_AUTHOR'),
                "message" => $result['msg']
            ];
            file_put_contents('@core/license.json', json_encode($license_info));
            return redirect()->back()->with(['msg' => $result['msg'], 'type' => $type]);
        }

        return redirect()->back()->with(['msg' => 'there is a problem to connect xgenious server please contact support', 'type' => 'danger']);
    }

    public function sitemap_settings()
    {
        $all_sitemap = glob('sitemap/*');
        return view('backend.general-settings.sitemap-settings')->with(['all_sitemap' => $all_sitemap]);
    }

    public function update_sitemap_settings(Request $request)
    {
        $this->validate($request, [
            'site_url' => 'required|url',
            'title' => 'nullable|string',
        ]);
        $title = $request->title ? $request->title : time();
        SitemapGenerator::create($request->site_url)->writeToFile('sitemap/sitemap-' . $title . '.xml');
        return redirect()->back()->with([
            'msg' => __('Sitemap Generated..'),
            'type' => 'success'
        ]);
    }

    public function delete_sitemap_settings(Request $request)
    {
        if (file_exists($request->sitemap_name)) {
            @unlink($request->sitemap_name);
        }
        return redirect()->back()->with(['msg' => __('Sitemap Deleted...'), 'type' => 'danger']);
    }
    
        public function database_upgrade(){
        return view('backend.general-settings.database-upgrade');
    }
    
    public function database_upgrade_post(Request $request){
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);
        return back()->with(FlashMsg::item_new('Database Upgraded Successfully'));
    }

    public function rss_feed_settings()
    {
        return view( 'backend.general-settings.rss-feed-settings');
    }

    public function update_rss_feed_settings(Request $request)
    {


            $this->validate($request, [
                'site_rss_feed_url' => 'nullable|string',
                'site_rss_feed_title' => 'nullable|string',
                'site_rss_feed_description' => 'nullable|string',

            ]);
            $fields = [
                'site_rss_feed_url',
                'site_rss_feed_title',
                'site_rss_feed_description'
            ];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    update_static_option($field, $request->$field);
                }
            }

            $url = $request->site_rss_feed_url;
            $title = $request->site_rss_feed_title;
            $description = $request->site_rss_feed_description;


        $env_val['RSS_FEED_URL'] = $url ? '"' . $url . '"' : '"rss-feeds"';
        $env_val['RSS_FEED_TITLE'] = $title ? '"' . $title . '"' : '"' . get_static_option('site_' . get_default_language() . '_title') . '"';
        $env_val['RSS_FEED_DESCRIPTION'] = $description ? '"' . $description . '"' : '"' . get_static_option('site_' . get_default_language() . '_tag_line') . '"';

        setEnvValue(array(
            'RSS_FEED_URL' => $env_val['RSS_FEED_URL'],
            'RSS_FEED_TITLE' => $env_val['RSS_FEED_TITLE'],
            'RSS_FEED_DESCRIPTION' => $env_val['RSS_FEED_DESCRIPTION'],
            'RSS_FEED_LANGUAGE' => get_default_language()
        ));

        return redirect()->back()->with([
            'msg' => __('RSS Settings Update..'),
            'type' => 'success'
        ]);
    }



}
