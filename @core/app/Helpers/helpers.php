
<?php


use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Language;
use App\RssFeedInfo;
use App\StaticOption;
use App\MediaUpload;
use App\Menu;
use App\Page;
use App\Blog;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

function active_menu($url)
{
    return $url == request()->path() ? 'active' : '';
}
function active_menu_frontend($url)
{

    return $url == request()->path() ? 'current-menu-item' : '';
}
function check_image_extension($file)
{
    $extension = strtolower($file->getClientOriginalExtension());
    if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension = 'gif') {
        return false;
    }
    return true;
}

function render_image_markup_by_attachment_id($id, $class = null, $size = 'full', $is_lazy= false)
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $lazy_class = $is_lazy ? 'lazy' : '';
        $source = $is_lazy ? 'data-' : '';
        $class_list = !empty($class) ? 'class=" '.$lazy_class.' '. $class . '"' : 'class="'.$lazy_class.'"';
        $output = '<img '.$source.'src="' . $image_details['img_url'] . '" ' . $class_list . '  alt="' . $image_details['img_alt'] . '"/>';
    }
    return $output;
}





function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
function sendEmail($to, $name, $subject, $message, $from = '')
{
    $template = get_static_option('site_global_email_template');
    $from = get_static_option('site_global_email');

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";;
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    $mm = str_replace("@username", $name, $template);
    $message = str_replace("@message", $message, $mm);
    $message = str_replace("@company", get_static_option('site_title'), $message);

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}
function sendPlanEmail($to, $name, $subject, $message, $from)
{

    $headers = "From: " . $from . " \r\n";
    $headers .= "Reply-To: <$from> \r\n";
    $headers .= "Return-Path: " . ($from) . "\r\n";;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "X-Priority: 2\nX-MSmail-Priority: high";;
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}


function set_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }
    return false;
}

function get_static_option($key)
{
    global $option_name;
    $option_name = $key;
    $value = \Illuminate\Support\Facades\Cache::remember($option_name, 86400, function () {
        global $option_name;
        return StaticOption::where('option_name', $option_name)->first();
    });

    return !empty($value) ? $value->option_value : null;
}

function get_default_language()
{
    $defaultLang = Language::where('default', 1)->first();
    return $defaultLang->slug;
}
function update_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    } else {
        StaticOption::where('option_name', $key)->update([
            'option_name' => $key,
            'option_value' => $value
        ]);
        \Illuminate\Support\Facades\Cache::forget($key);
        return true;
    }
    return false;
}
function delete_static_option($key)
{
    \Illuminate\Support\Facades\Cache::forget($key);
    return (boolean) StaticOption::where('option_name', $key)->delete();
}



function single_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);

    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;';
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $pinterest_share_link = 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;

    $output .= '<li class="list-item"><a class="facebook" href="' . $facebook_share_link . '"><i class="lab la-facebook-f icon"></i></a></li>';
    $output .= '<li class="list-item"><a class="twitter" href="' . $twitter_share_link . '"><i class="lab la-twitter icon"></i></a></li>';
    $output .= '<li class="list-item"><a class="linkedin" href="' . $linkedin_share_link . '"><i class="lab la-linkedin-in icon"></i></a></li>';
    $output .= '<li class="list-item"><a class="pinterest" href="' . $pinterest_share_link . '"><i class="lab la-pinterest-p icon"></i></a></li>';

    return $output;
}





function single_post_share_two($url, $title, $img_url)
{

    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);

    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;';
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $pinterest_share_link = 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;

   return <<<LIST
    <li class="single-item"><a href="{$facebook_share_link }" class="left-content"><span class="icon facebook"><i class="lab la-facebook-f"></i> </span></a></li>
    <li class="single-item"><a href="{$twitter_share_link }" class="left-content"><span class="icon twitter"><i class="lab la-twitter"></i> </span></a></li>
     <li class="single-item"><a href="{$linkedin_share_link }" class="left-content"><span class="icon facebook"><i class="lab la-linkedin-in"></i> </span></a></li>
    <li class="single-item"><a href="{$pinterest_share_link }" class="left-content"><span class="icon youtube"><i class="lab la-pinterest-p"></i> </span></a></li>

LIST;


}


function load_google_fonts()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts
    $body_font_family = get_static_option('body_font_family') ?? 'Open Sans';
    $heading_font_family = get_static_option('heading_font_family') ??  'serif';
    $heading_font_family_two = get_static_option('heading_font_family_two') ??  'sans-serif';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option('body_font_variant');
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant,['class' => false]) : ['400'];
    $body_font_variant_selected_arr = !is_null($body_font_variant) ? $body_font_variant : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/',$load_body_font_variant,$match);
    if(count($match) > 0){
        $body_italic =  'ital,';
    }else{
        $load_body_font_variant = str_replace('0,','',$load_body_font_variant);
    }

    $fonts_url .= $load_body_font_family . ':'.$body_italic.'wght@' . $load_body_font_variant;
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    $load_heading_font_family_two = str_replace(' ', '+', $heading_font_family_two);
    $heading_font_variant = get_static_option('heading_font_variant');
    $heading_font_variant_two = get_static_option('heading_font_variant_two');

    $heading_font_variant_selected_arr = !empty($heading_font_variant) ? unserialize($heading_font_variant,['class' => false]) : ['400'];
    $heading_font_variant_selected_arr = !is_null($heading_font_variant_selected_arr) ? $heading_font_variant_selected_arr : ['400'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(';', $heading_font_variant_selected_arr) : '400';

    $heading_font_variant_two_selected_arr = !empty($heading_font_variant_two) ? unserialize($heading_font_variant_two,['class' => false]) : ['400'];
    $heading_font_variant_two_selected_arr = !is_null($heading_font_variant_two_selected_arr) ? $heading_font_variant_two_selected_arr : ['400'];
    $load_heading_font_variant_two = is_array($heading_font_variant_two_selected_arr) ? implode(';', $heading_font_variant_two_selected_arr) : '400';

    if (!empty(get_static_option('heading_font')) && $heading_font_family != $body_font_family) {

        $heading_italic = '';
        preg_match('/1,/',$load_heading_font_variant,$match);
        if(count($match) > 0){
            $heading_italic =  'ital,';
        }else{
            $load_heading_font_variant = str_replace('0,','',$load_heading_font_variant);
        }

        $fonts_url .= '&family=' . $load_heading_font_family . ':'.$heading_italic.'wght@' . $load_heading_font_variant;
    }

    if (!empty(get_static_option('heading_font_family_two')) && $heading_font_family_two != $body_font_family) {
        $heading_italic = '';
        preg_match('/1,/',$load_heading_font_variant_two,$match);
        if(count($match) > 0){
            $heading_italic =  'ital,';
        }else{
            $load_heading_font_variant_two = str_replace('0,','',$load_heading_font_variant_two);
        }
         $fonts_url .= '&family=' . $load_heading_font_family_two . ':'.$heading_italic.'wght@' . $load_heading_font_variant_two;
    }



    return sprintf('<link  rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}

function generate_font_string($body_font_family_five,$body_font_variant_five,$load_heading_five_family){
    $body_font_variant_five_selected_arr = !empty($body_font_variant_five) ? unserialize($body_font_variant_five,['class' => false]) : ['400'];
    $body_font_variant_five_selected_arr = !is_null($body_font_variant_five_selected_arr) ? $body_font_variant_five_selected_arr : ['400'];
    $load_body_font_variant_five_variant = is_array($body_font_variant_five_selected_arr) ? implode(';', $body_font_variant_five_selected_arr) : '400';

    if ( !empty($body_font_family_five)) {

        $heading_italic = '';
        preg_match('/1,/',$load_body_font_variant_five_variant,$match);
        if(count($match) > 0){
            $heading_italic =  'ital,';
        }else{
            $load_heading_font_variant = str_replace('0,','',$load_body_font_variant_five_variant);
        }

        $fonts_url = '&family=' . $load_heading_five_family . ':'.$heading_italic.'wght@' . $load_heading_font_variant;
    }
    return $fonts_url ?? '';
}




function render_background_image_markup_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        // $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
        $output = 'data-src="'.$image_details['img_url'].'"';
    }
    return $output;
}
function render_favicon_by_id($id)
{
    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}
function get_attachment_image_by_id($id, $size = null, $default = false)
{
    $image_details = \Illuminate\Support\Facades\Cache::remember('media-uploader-image'.$id,300,function () use ($id){
        return MediaUpload::find($id);
    });
    $return_val = [];
    $image_url = '';

    if (file_exists('assets/uploads/media-uploader/' . optional($image_details)->path)) {
        $image_url = asset('assets/uploads/media-uploader/' . optional($image_details)->path);
    }

    if (!empty($id) && !empty($image_details)) {
        switch ($size) {
            case "large":
                if (file_exists('assets/uploads/media-uploader/large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/large-' . $image_details->path);
                }
                break;
            case "grid":
                if (file_exists('assets/uploads/media-uploader/grid-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/grid-' . $image_details->path);
                }
                break;

            case "semi-large":
                if (file_exists('assets/uploads/media-uploader/semi-large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/semi-large-' . $image_details->path);
                }
                break;
            case "box":
                if (file_exists('assets/uploads/media-uploader/box-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/box-' . $image_details->path);
                }
                break;
            case "thumb":
                if (file_exists('assets/uploads/media-uploader/thumb-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/thumb-' . $image_details->path);
                }else {
                    if (file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                        $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                    }
                }
                break;
            default:
                if (file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = asset('assets/uploads/no-image.png');
    }

    return $return_val;
}

function get_user_lang()
{
    return $lang = LanguageHelper::user_lang_slug();;
}

function get_user_lang_direction()
{
    $default = \App\Language::where('default', 1)->first();
    $user_direction = \App\Language::where('slug', session()->get('lang'))->first();
    return !empty(session()->get('lang')) ? $user_direction->direction : $default->direction;
}

function filter_static_option_value(string $index, array $array = [])
{
    return $array[$index] ?? '';
}

function render_og_meta_image_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';
    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '>';
    }
    return $output;
}


function setEnvValue(array $values)
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}
function get_language_by_slug($slug)
{
    $lang_details = \App\Language::where('slug', $slug)->first();
    return !empty($lang_details) ? $lang_details->name : '';
}
function site_currency_symbol($text = false)
{
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');
    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }
    return $symbol;
}
function amount_with_currency_symbol($amount, $text = false)
{
    $amount = number_format((float) $amount, 0, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = $symbol . $amount;
    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }
    return $return_val;
}
function get_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_' . get_user_lang() . '_footer_copyright');
    $footer_copyright_text = str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
    return $footer_copyright_text;
}
function get_country_field($name, $id, $class)
{
    return '<select style="height:50px;" name="' . $name . '" id="' . $id . '" class="' . $class .'"><option value="">' . __('Select Country') . '</option><option value="Afghanistan" >Afghanistan</option><option value="Albania" >Albania</option><option value="Algeria" >Algeria</option><option value="American Samoa" >American Samoa</option><option value="Andorra" >Andorra</option><option value="Angola" >Angola</option><option value="Anguilla" >Anguilla</option><option value="Antarctica" >Antarctica</option><option value="Antigua and Barbuda" >Antigua and Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Azerbaijan" >Azerbaijan</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Bangladesh" >Bangladesh</option><option value="Barbados" >Barbados</option><option value="Belarus" >Belarus</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option><option value="Botswana" >Botswana</option><option value="Bouvet Island" >Bouvet Island</option><option value="Brazil" >Brazil</option><option value="British Indian Ocean Territory" >British Indian Ocean Territory</option><option value="Brunei Darussalam" >Brunei Darussalam</option><option value="Bulgaria" >Bulgaria</option><option value="Burkina Faso" >Burkina Faso</option><option value="Burundi" >Burundi</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Islands" >Cayman Islands</option><option value="Central African Republic" >Central African Republic</option><option value="Chad" >Chad</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Island" >Christmas Island</option><option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Islands" >Cook Islands</option><option value="Costa Rica" >Costa Rica</option><option value="Croatia (Hrvatska)" >Croatia (Hrvatska)</option><option value="Cuba" >Cuba</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Democratic Republic of the Congo" >Democratic Republic of the Congo</option><option value="Denmark" >Denmark</option><option value="Djibouti" >Djibouti</option><option value="Dominica" >Dominica</option><option value="Dominican Republic" >Dominican Republic</option><option value="East Timor" >East Timor</option><option value="Ecuador" >Ecuador</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Equatorial Guinea" >Equatorial Guinea</option><option value="Eritrea" >Eritrea</option><option value="Estonia" >Estonia</option><option value="Ethiopia" >Ethiopia</option><option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option><option value="Faroe Islands" >Faroe Islands</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="France, Metropolitan" >France, Metropolitan</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Territories" >French Southern Territories</option><option value="Gabon" >Gabon</option><option value="Gambia" >Gambia</option><option value="Georgia" >Georgia</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey" >Guernsey</option><option value="Guinea" >Guinea</option><option value="Guinea-Bissau" >Guinea-Bissau</option><option value="Guyana" >Guyana</option><option value="Haiti" >Haiti</option><option value="Heard and Mc Donald Islands" >Heard and Mc Donald Islands</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Iran (Islamic Republic of)" >Iran (Islamic Republic of)</option><option value="Iraq" >Iraq</option><option value="Ireland" >Ireland</option><option value="Isle of Man" >Isle of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Ivory Coast" >Ivory Coast</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey" >Jersey</option><option value="Jordan" >Jordan</option><option value="Kazakhstan" >Kazakhstan</option><option value="Kenya" >Kenya</option><option value="Kiribati" >Kiribati</option><option value="Korea, Democratic People\'s Republic of" >Korea, Democratic People\'s Republic of</option><option value="Korea, Republic of" >Korea, Republic of</option><option value="Kosovo" >Kosovo</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Lao People\'s Democratic Republic" >Lao People\'s Democratic Republic</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liberia" >Liberia</option><option value="Libyan Arab Jamahiriya" >Libyan Arab Jamahiriya</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macau" >Macau</option><option value="Madagascar" >Madagascar</option><option value="Malawi" >Malawi</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Islands" >Marshall Islands</option><option value="Martinique" >Martinique</option><option value="Mauritania" >Mauritania</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia, Federated States of" >Micronesia, Federated States of</option><option value="Moldova, Republic of" >Moldova, Republic of</option><option value="Monaco" >Monaco</option><option value="Mongolia" >Mongolia</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Mozambique" >Mozambique</option><option value="Myanmar" >Myanmar</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Nepal" >Nepal</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niger" >Niger</option><option value="Nigeria" >Nigeria</option><option value="Niue" >Niue</option><option value="Norfolk Island" >Norfolk Island</option><option value="North Macedonia" >North Macedonia</option><option value="Northern Mariana Islands" >Northern Mariana Islands</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Palestine" >Palestine</option><option value="Panama" >Panama</option><option value="Papua New Guinea" >Papua New Guinea</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn" >Pitcairn</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Republic of Congo" >Republic of Congo</option><option value="Reunion" >Reunion</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Rwanda" >Rwanda</option><option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option><option value="Saint Lucia" >Saint Lucia</option><option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option><option value="Samoa" >Samoa</option><option value="San Marino" >San Marino</option><option value="Sao Tome and Principe" >Sao Tome and Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Senegal" >Senegal</option><option value="Serbia" >Serbia</option><option value="Seychelles" >Seychelles</option><option value="Sierra Leone" >Sierra Leone</option><option value="Singapore" >Singapore</option><option value="Slovakia" >Slovakia</option><option value="Slovenia" >Slovenia</option><option value="Solomon Islands" >Solomon Islands</option><option value="Somalia" >Somalia</option><option value="South Africa" >South Africa</option><option value="South Georgia South Sandwich Islands" >South Georgia South Sandwich Islands</option><option value="South Sudan" >South Sudan</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St. Helena" >St. Helena</option><option value="St. Pierre and Miquelon" >St. Pierre and Miquelon</option><option value="Sudan" >Sudan</option><option value="Suriname" >Suriname</option><option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Syrian Arab Republic" >Syrian Arab Republic</option><option value="Taiwan" >Taiwan</option><option value="Tajikistan" >Tajikistan</option><option value="Tanzania, United Republic of" >Tanzania, United Republic of</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad and Tobago" >Trinidad and Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turkmenistan" >Turkmenistan</option><option value="Turks and Caicos Islands" >Turks and Caicos Islands</option><option value="Tuvalu" >Tuvalu</option><option value="Uganda" >Uganda</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="United States minor outlying islands" >United States minor outlying islands</option><option value="Uruguay" >Uruguay</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican City State" >Vatican City State</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Islands (British)" >Virgin Islands (British)</option><option value="Virgin Islands (U.S.)" >Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="Zambia" >Zambia</option><option value="Zimbabwe" >Zimbabwe</option></select>';
}
function google_captcha_check($token)
{
    $captha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $captha_url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('secret' => get_static_option('site_google_captcha_v3_secret_key'), 'response' => $token)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    return $result;
}
function get_field_by_type($type, $name, $placeholder, $options = [], $requried = null, $mimes = null)
{
    $markup = '';
    $required_markup_html = 'required="required"';
     $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));

    switch ($type) {
        case ('email'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="email" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('tel'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="tel" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('date'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="date" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('url'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="url" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
        case ('textarea'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group textarea"><textarea name="' . $name . '" id="' . $name . '" cols="30" rows="10" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></textarea></div>';
            break;
        case ('file'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $mimes_type_markup = str_replace('mimes:', __('Accept File Type:') . ' ', $mimes);
            $markup = ' <div class="form-group file"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="file" id="' . $name . '" name="' . $name . '" ' . $required_markup . ' class="form-control" > <span class="help-info">' . $mimes_type_markup . '</span></div>';
            break;
        case ('checkbox'):
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group checkbox">  <input type="checkbox" id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '> <label for="' . $name . '">' . __($placeholder) . '</label></div>';
            break;
        case ('select'):
            $option_markup = '';
            $required_markup = !empty($requried) ? $required_markup_html : '';
            foreach ($options as $opt) {
                $option_markup .= '<option value="' . Str::slug(htmlspecialchars(strip_tags($opt))) . '">' .  htmlspecialchars(strip_tags($opt)) . '</option>';
            }
            $markup = ' <div class="form-group select"> <label for="' . $name . '">' . __($placeholder) . '</label> <select id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '>' . $option_markup . '</select></div>';
            break;
        default:
            $required_markup = !empty($requried) ? $required_markup_html : '';
            $markup = ' <div class="form-group"> <input type="text" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
            break;
    }

    return $markup;
}

function render_form_field_for_frontend($form_content)
{
    if (empty($form_content)) {
        return;
    }
    $output = '';
    $form_fields = json_decode($form_content);
    $select_index = 0;
    $options = [];
    foreach ($form_fields->field_type as $key => $value) {
        if (!empty($value)) {
            if ($value == 'select') {
                $options = explode("\n", $form_fields->select_options[$select_index]);
            }
            $required = isset($form_fields->field_required->$key) ? $form_fields->field_required->$key : '';
            $mimes = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            $output .= get_field_by_type($value, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $options, $required, $mimes);
            if ($value == 'select') {
                $select_index++;
            };
        }
    }
    return $output;
}
function render_payment_gateway_for_form($cash_on_delivery = false)
{
    $output = '<div class="payment-gateway-wrapper">';
    if (empty(get_static_option('site_payment_gateway'))) {
        return;
    }

    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
    $all_gateway = [
        'paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack'
    ];
    $output .= '<ul>';
    if ($cash_on_delivery) {
        $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
        $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
        $output .= '</div></li>';
    }
    foreach ($all_gateway as $gateway) {
        if (!empty(get_static_option($gateway . '_gateway'))) :
            $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected"' : '';

            $output .= '<li data-gateway="' . $gateway . '" ' . $class . '><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option($gateway . '_preview_logo'));
            $output .= '</div></li>';
        endif;
    }
    $output .= '</ul>';

    $output .= '</div>';
    return $output;
}
function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $placeholder = htmlspecialchars(strip_tags($placeholder));
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label ><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider-yes-no"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . strip_tags($args['select_option']) . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}

function is_paypal_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'];
    return in_array($global_currency, $supported_currency);
}

function get_manual_payment_description()
{
    $manual_payment_description = get_static_option('site_manual_payment_description');
    $manual_payment_description = str_replace(array('https://{url}', 'http://{url}'), array(url('/'), url('/')), $manual_payment_description);
    return $manual_payment_description;
}



function is_paytm_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return in_array($global_currency, $supported_currency);
}

function is_razorpay_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['INR'];
    return in_array($global_currency, $supported_currency);
}

function is_mollie_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR'];
    return in_array($global_currency, $supported_currency);
}

function is_flutterwave_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    return in_array($global_currency, $supported_currency);
}

function is_paystack_supported_currency()
{
    $global_currency = get_static_option('site_global_currency');
    $supported_currency = ['NGN', 'GHS'];
    return in_array($global_currency, $supported_currency);
}
function get_amount_in_usd($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'USD') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_usd_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_inr($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'INR') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_inr_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}

function get_amount_in_ngn($amount, $currency)
{
    $output = 0;
    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];
    foreach ($all_currency as $cur => $symbol) {
        if ($cur == 'NGN') {
            continue;
        }
        if ($cur == $currency) {
            $exchange_rate = get_static_option('site_' . strtolower($cur) . '_to_ngn_exchange_rate');
            $output = $amount * $exchange_rate;
        }
    }

    return $output;
}
function check_currency_support_by_payment_gateway($gateway)
{
    $output = false;
    if ($gateway == 'paypal') {
        $output = is_paypal_supported_currency();
    } elseif ($gateway == 'paytm') {
        $output = is_paytm_supported_currency();
    } elseif ($gateway == 'mollie') {
        $output = is_mollie_supported_currency();
    } elseif ($gateway == 'stripe') {
        $output = true;
    } elseif ($gateway == 'razorpay') {
        $output = is_razorpay_supported_currency();
    } elseif ($gateway == 'flutterwave') {
        $output = is_flutterwave_supported_currency();
    } elseif ($gateway == 'paystack') {
        $output = is_paystack_supported_currency();
    } else {
        $output = true;
    }

    return $output;
}
function custom_number_format($amount)
{
    return number_format((float)$amount, 2, '.', '');
}
function get_charge_currency($gateway)
{
    $output = 'USD';
    if ($gateway == 'paypal') {
        $output = 'USD';
    } elseif ($gateway == 'paytm') {
        $output = 'INR';
    } elseif ($gateway == 'mollie') {
        $output = 'USD';
    } elseif ($gateway == 'razorpay') {
        $output = 'INR';
    } elseif ($gateway == 'flutterwave') {
        $output = 'USD';
    } elseif ($gateway == 'paystack') {
        $output = 'NGN';
    }

    return $output;
}
function get_charge_amount($amount, $gateway)
{
    $output = 0;
    if ($gateway == 'paypal') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paytm') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'mollie') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'razorpay') {
        $output = get_amount_in_inr($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'flutterwave') {
        $output = get_amount_in_usd($amount, get_static_option('site_global_currency'));
    } elseif ($gateway == 'paystack') {
        $output = get_amount_in_ngn($amount, get_static_option('site_global_currency'));
    }

    return $output;
}
function get_paypal_form_url()
{
    $output = 'https://secure.paypal.com/cgi-bin/webscr';
    $sandbox_enable = get_static_option('paypal_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
    return $output;
}
function get_paytm_environment()
{
    $output = 'PROD';
    $sandbox_enable = get_static_option('paytm_test_mode');
    if (!empty($sandbox_enable)) {
        $output = 'TEST';
    }
    return $output;
}

function redirect_404_page()
{
    return view('frontend.pages.404');
}
function getVisIpAddr()
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
function get_visitor_country()
{
    $return_val = 'not detected';
    $ip = getVisIpAddr();
    $ipdat = @json_decode(file_get_contents(
        "http://www.geoplugin.net/json.gp?ip=" . $ip
    ));

    $ipdat = (array) $ipdat;
    $return_val = isset($ipdat['geoplugin_countryCode']) ? $ipdat['geoplugin_countryCode'] : $return_val;

    return $return_val;
}
function get_user_name_by_id($id)
{
    $user = \App\User::find($id);
    return $user;
}
function all_languages()
{
    $languages = [
        [
            "value" => "af",
            "lang" => "af",
            "title" => "Afrikaans"
        ],
        [
            "value" => "ar",
            "lang" => "ar",
            "title" => "العربية"
        ],
        [
            "value" => "ary",
            "lang" => "ar",
            "title" => "العربية المغربية"
        ],
        [
            "value" => "as",
            "lang" => "as",
            "title" => "অসমীয়া"
        ],
        [
            "value" => "az",
            "lang" => "az",
            "title" => "Azərbaycan dili"
        ],
        [
            "value" => "azb",
            "lang" => "az",
            "title" => "گؤنئی آذربایجان"
        ],
        [
            "value" => "bel",
            "lang" => "be",
            "title" => "Беларуская мова"
        ],
        [
            "value" => "bg_BG",
            "lang" => "bg",
            "title" => "Български"
        ],
        [
            "value" => "bn_BD",
            "lang" => "bn",
            "title" => "বাংলা"
        ],
        [
            "value" => "bo",
            "lang" => "bo",
            "title" => "བོད་ཡིག"
        ],
        [
            "value" => "bs_BA",
            "lang" => "bs",
            "title" => "Bosanski"
        ],
        [
            "value" => "ca",
            "lang" => "ca",
            "title" => "Català"
        ],
        [
            "value" => "ceb",
            "lang" => "ceb",
            "title" => "Cebuano"
        ],
        [
            "value" => "cs_CZ",
            "lang" => "cs",
            "title" => "Čeština"
        ],
        [
            "value" => "cy",
            "lang" => "cy",
            "title" => "Cymraeg"
        ],
        [
            "value" => "da_DK",
            "lang" => "da",
            "title" => "Dansk"
        ],
        [
            "value" => "de_CH",
            "lang" => "de",
            "title" => "Deutsch (Schweiz)"
        ],
        [
            "value" => "de_AT",
            "lang" => "de",
            "title" => "Deutsch (Österreich)"
        ],
        [
            "value" => "de_CH_informal",
            "lang" => "de",
            "title" => "Deutsch (Schweiz, Du)"
        ],
        [
            "value" => "de_DE",
            "lang" => "de",
            "title" => "Deutsch"
        ],
        [
            "value" => "de_DE_formal",
            "lang" => "de",
            "title" => "Deutsch (Sie)"
        ],
        [
            "value" => "dsb",
            "lang" => "dsb",
            "title" => "Dolnoserbšćina"
        ],
        [
            "value" => "dzo",
            "lang" => "dz",
            "title" => "རྫོང་ཁ"
        ],
        [
            "value" => "el",
            "lang" => "el",
            "title" => "Ελληνικά"
        ],
        [
            "value" => "en_US",
            "lang" => "en",
            "title" => "English (USA)"
        ],
        [
            "value" => "en_AU",
            "lang" => "en",
            "title" => "English (Australia)"
        ],
        [
            "value" => "en_GB",
            "lang" => "en",
            "title" => "English (UK)"
        ],
        [
            "value" => "en_CA",
            "lang" => "en",
            "title" => "English (Canada)"
        ],
        [
            "value" => "en_ZA",
            "lang" => "en",
            "title" => "English (South Africa)"
        ],
        [
            "value" => "en_NZ",
            "lang" => "en",
            "title" => "English (New Zealand)"
        ],
        [
            "value" => "eo",
            "lang" => "eo",
            "title" => "Esperanto"
        ],
        [
            "value" => "es_AR",
            "lang" => "es",
            "title" => "Español de Argentina"
        ],
        [
            "value" => "es_EC",
            "lang" => "es",
            "title" => "Español de Ecuador"
        ],
        [
            "value" => "es_MX",
            "lang" => "es",
            "title" => "Español de México"
        ],
        [
            "value" => "es_ES",
            "lang" => "es",
            "title" => "Español"
        ],
        [
            "value" => "es_VE",
            "lang" => "es",
            "title" => "Español de Venezuela"
        ],
        [
            "value" => "es_CO",
            "lang" => "es",
            "title" => "Español de Colombia"
        ],
        [
            "value" => "es_CR",
            "lang" => "es",
            "title" => "Español de Costa Rica"
        ],
        [
            "value" => "es_PE",
            "lang" => "es",
            "title" => "Español de Perú"
        ],
        [
            "value" => "es_PR",
            "lang" => "es",
            "title" => "Español de Puerto Rico"
        ],
        [
            "value" => "es_UY",
            "lang" => "es",
            "title" => "Español de Uruguay"
        ],
        [
            "value" => "es_GT",
            "lang" => "es",
            "title" => "Español de Guatemala"
        ],
        [
            "value" => "es_CL",
            "lang" => "es",
            "title" => "Español de Chile"
        ],
        [
            "value" => "et",
            "lang" => "et",
            "title" => "Eesti"
        ],
        [
            "value" => "eu",
            "lang" => "eu",
            "title" => "Euskara"
        ],
        [
            "value" => "fa_IR",
            "lang" => "fa",
            "title" => "فارسی"
        ],
        [
            "value" => "fa_AF",
            "lang" => "fa",
            "title" => "(فارسی (افغانستان"
        ],
        [
            "value" => "fi",
            "lang" => "fi",
            "title" => "Suomi"
        ],
        [
            "value" => "fr_FR",
            "lang" => "fr",
            "title" => "Français"
        ],
        [
            "value" => "fr_BE",
            "lang" => "fr",
            "title" => "Français de Belgique"
        ],
        [
            "value" => "fr_CA",
            "lang" => "fr",
            "title" => "Français du Canada"
        ],
        [
            "value" => "fur",
            "lang" => "fur",
            "title" => "Friulian"
        ],
        [
            "value" => "gd",
            "lang" => "gd",
            "title" => "Gàidhlig"
        ],
        [
            "value" => "gl_ES",
            "lang" => "gl",
            "title" => "Galego"
        ],
        [
            "value" => "gu",
            "lang" => "gu",
            "title" => "ગુજરાતી"
        ],
        [
            "value" => "haz",
            "lang" => "haz",
            "title" => "هزاره گی"
        ],
        [
            "value" => "he_IL",
            "lang" => "he",
            "title" => "עִבְרִית"
        ],
        [
            "value" => "hi_IN",
            "lang" => "hi",
            "title" => "हिन्दी"
        ],
        [
            "value" => "hr",
            "lang" => "hr",
            "title" => "Hrvatski"
        ],
        [
            "value" => "hsb",
            "lang" => "hsb",
            "title" => "Hornjoserbšćina"
        ],
        [
            "value" => "hu_HU",
            "lang" => "hu",
            "title" => "Magyar"
        ],
        [
            "value" => "hy",
            "lang" => "hy",
            "title" => "Հայերեն"
        ],
        [
            "value" => "id_ID",
            "lang" => "id",
            "title" => "Bahasa Indonesia"
        ],
        [
            "value" => "is_IS",
            "lang" => "is",
            "title" => "Íslenska"
        ],
        [
            "value" => "it_IT",
            "lang" => "it",
            "title" => "Italiano"
        ],
        [
            "value" => "ja",
            "lang" => "ja",
            "title" => "日本語"
        ],
        [
            "value" => "jv_ID",
            "lang" => "jv",
            "title" => "Basa Jawa"
        ],
        [
            "value" => "ka_GE",
            "lang" => "ka",
            "title" => "ქართული"
        ],
        [
            "value" => "kab",
            "lang" => "kab",
            "title" => "Taqbaylit"
        ],
        [
            "value" => "kk",
            "lang" => "kk",
            "title" => "Қазақ тілі"
        ],
        [
            "value" => "km",
            "lang" => "km",
            "title" => "ភាសាខ្មែរ"
        ],
        [
            "value" => "kn",
            "lang" => "kn",
            "title" => "ಕನ್ನಡ"
        ],
        [
            "value" => "ko_KR",
            "lang" => "ko",
            "title" => "한국어"
        ],
        [
            "value" => "ckb",
            "lang" => "ku",
            "title" => "كوردی‎"
        ],
        [
            "value" => "lo",
            "lang" => "lo",
            "title" => "ພາສາລາວ"
        ],
        [
            "value" => "lt_LT",
            "lang" => "lt",
            "title" => "Lietuvių kalba"
        ],
        [
            "value" => "lv",
            "lang" => "lv",
            "title" => "Latviešu valoda"
        ],
        [
            "value" => "mk_MK",
            "lang" => "mk",
            "title" => "Македонски јазик"
        ],
        [
            "value" => "ml_IN",
            "lang" => "ml",
            "title" => "മലയാളം"
        ],
        [
            "value" => "mn",
            "lang" => "mn",
            "title" => "Монгол"
        ],
        [
            "value" => "mr",
            "lang" => "mr",
            "title" => "मराठी"
        ],
        [
            "value" => "ms_MY",
            "lang" => "ms",
            "title" => "Bahasa Melayu"
        ],
        [
            "value" => "my_MM",
            "lang" => "my",
            "title" => "ဗမာစာ"
        ],
        [
            "value" => "nb_NO",
            "lang" => "nb",
            "title" => "Norsk bokmål"
        ],
        [
            "value" => "ne_NP",
            "lang" => "ne",
            "title" => "नेपाली"
        ],
        [
            "value" => "nl_NL",
            "lang" => "nl",
            "title" => "Nederlands"
        ],
        [
            "value" => "nl_BE",
            "lang" => "nl",
            "title" => "Nederlands (België)"
        ],
        [
            "value" => "nl_NL_formal",
            "lang" => "nl",
            "title" => "Nederlands (Formeel)"
        ],
        [
            "value" => "nn_NO",
            "lang" => "nn",
            "title" => "Norsk nynorsk"
        ],
        [
            "value" => "oci",
            "lang" => "oc",
            "title" => "Occitan"
        ],
        [
            "value" => "pa_IN",
            "lang" => "pa",
            "title" => "ਪੰਜਾਬੀ"
        ],
        [
            "value" => "pl_PL",
            "lang" => "pl",
            "title" => "Polski"
        ],
        [
            "value" => "ps",
            "lang" => "ps",
            "title" => "پښتو"
        ],
        [
            "value" => "pt_BR",
            "lang" => "pt",
            "title" => "Português do Brasil"
        ],
        [
            "value" => "pt_PT_ao90",
            "lang" => "pt",
            "title" => "Português (AO90)"
        ],
        [
            "value" => "pt_AO",
            "lang" => "pt",
            "title" => "Português de Angola"
        ],
        [
            "value" => "pt_PT",
            "lang" => "pt",
            "title" => "Português"
        ],
        [
            "value" => "rhg",
            "lang" => "rhg",
            "title" => "Ruáinga"
        ],
        [
            "value" => "ro_RO",
            "lang" => "ro",
            "title" => "Română"
        ],
        [
            "value" => "ru_RU",
            "lang" => "ru",
            "title" => "Русский"
        ],
        [
            "value" => "sah",
            "lang" => "sah",
            "title" => "Сахалыы"
        ],
        [
            "value" => "snd",
            "lang" => "sd",
            "title" => "سنڌي"
        ],
        [
            "value" => "si_LK",
            "lang" => "si",
            "title" => "සිංහල"
        ],
        [
            "value" => "sk_SK",
            "lang" => "sk",
            "title" => "Slovenčina"
        ],
        [
            "value" => "skr",
            "lang" => "skr",
            "title" => "سرائیکی"
        ],
        [
            "value" => "sl_SI",
            "lang" => "sl",
            "title" => "Slovenščina"
        ],
        [
            "value" => "sq",
            "lang" => "sq",
            "title" => "Shqip"
        ],
        [
            "value" => "sr_RS",
            "lang" => "sr",
            "title" => "Српски језик"
        ],
        [
            "value" => "sv_SE",
            "lang" => "sv",
            "title" => "Svenska"
        ],
        [
            "value" => "sw",
            "lang" => "sw",
            "title" => "Kiswahili"
        ],
        [
            "value" => "szl",
            "lang" => "szl",
            "title" => "Ślōnskŏ gŏdka"
        ],
        [
            "value" => "ta_IN",
            "lang" => "ta",
            "title" => "தமிழ்"
        ],
        [
            "value" => "ta_LK",
            "lang" => "ta",
            "title" => "தமிழ்"
        ],
        [
            "value" => "te",
            "lang" => "te",
            "title" => "తెలుగు"
        ],
        [
            "value" => "th",
            "lang" => "th",
            "title" => "ไทย"
        ],
        [
            "value" => "tl",
            "lang" => "tl",
            "title" => "Tagalog"
        ],
        [
            "value" => "tr_TR",
            "lang" => "tr",
            "title" => "Türkçe"
        ],
        [
            "value" => "tt_RU",
            "lang" => "tt",
            "title" => "Татар теле"
        ],
        [
            "value" => "tah",
            "lang" => "ty",
            "title" => "Reo Tahiti"
        ],
        [
            "value" => "ug_CN",
            "lang" => "ug",
            "title" => "ئۇيغۇرچە"
        ],
        [
            "value" => "uk",
            "lang" => "uk",
            "title" => "Українська"
        ],
        [
            "value" => "ur",
            "lang" => "ur",
            "title" => "اردو"
        ],
        [
            "value" => "uz_UZ",
            "lang" => "uz",
            "title" => "O‘zbekcha"
        ],
        [
            "value" => "vi",
            "lang" => "vi",
            "title" => "Tiếng Việt"
        ],
        [
            "value" => "zh_TW",
            "lang" => "zh",
            "title" => "繁體中文"
        ],
        [
            "value" => "zh_HK",
            "lang" => "zh",
            "title" => "香港中文版"
        ],
        [
            "value" => "zh_CN",
            "lang" => "zh",
            "title" => "简体中文"
        ]
    ];

    return $languages;
}

function render_embed_google_map($address, $zoom = 10)
{
    if (empty($address)) {
        return;
    }
    printf(
        '<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
        rawurlencode($address),
        $zoom,
        $address
    );
}


function render_menu_by_id($id)
{
    $default_lang = get_user_lang();
    $mega_menu_enable = '';

    if (empty($id)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    $output = '';
    $menu_details_from_db = Menu::find($id);


    $menu_content = json_decode($menu_details_from_db->content);
    if (empty($menu_content)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    foreach ($menu_content as $menu_item) {
        $li_class = '';
        //set li class if page is current page

        $mega_menu_ids = [];
        if (property_exists($menu_item, 'items_id')) {
            $mega_menu_ids = explode(',', $menu_item->items_id);
        }


        if ($menu_item->ptype == 'static') {
            //[lang]
            $menu_title =  get_static_option(str_replace('[lang]',get_user_lang(),$menu_item->pname));
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $menu_title = '';
            $menu_slug = '';
            $page_details = Page::with('lang_front')->find($menu_item->pid);
            if (!empty($page_details)){
                $menu_title = $page_details->title;
                $menu_slug = route('frontend.dynamic.page', [$page_details->slug,$page_details->id]);
                $li_class .= (request()->is(route('frontend.dynamic.page', [$page_details->slug,$page_details->id]))) ? ' current-menu-item ' : '';
            }

        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ( $menu_item->ptype == 'blog' || $menu_item->ptype == 'practice-area' || $menu_item->ptype == 'case' ||  $menu_item->ptype == 'appointment') {

          if ($menu_item->ptype == 'blog') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Blog::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.blog.single', [$page_details->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.blog.single', [$page_details->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }

            }  if ($menu_item->ptype == 'practice-area') {
                    $menu_title = '';
                    $menu_slug = '';
                    $page_details = \App\PracticeArea::with('lang_front')->find($menu_item->pid);
                    if (!empty($page_details)){
                        $menu_title = $page_details->title;
                        $menu_slug = route('frontend.preactice.area.single', [$page_details->slug,$page_details->id]);
                        $li_class .= (request()->is(route('frontend.preactice.single', [$page_details->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                    }

                }elseif ($menu_item->ptype == 'case') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Cases::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.case.single', [$page_details->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.case.single', [$page_details->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }

              }elseif ($menu_item->ptype == 'appointment') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Appointment::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)){
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.appointment.single', [$page_details->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.appointment.single', [$page_details->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }
            }
        }  elseif ($menu_item->ptype == 'case_mega_menu') {
            $menu_title = get_static_option('product_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('product_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Cases::with('lang_front')->find($mega_menu_ids)->groupBy('category_id');
        } elseif ($menu_item->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('blog_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Blog::with('lang_front')->find($mega_menu_ids)->groupBy('category_id');
        }elseif ($menu_item->ptype == 'appointment_mega_menu') {
            $menu_title = get_static_option('appointment_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('appointment_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Appointment::with('lang_front')->find($mega_menu_ids)->groupBy('categories_id');
        }

        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';
        $li_class .= property_exists($menu_item, 'items_id') ? ' menu-item-has-mega-menu ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . purify_html($menu_item->icon) . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . purify_html($menu_item->antarget) . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)){//start condition

            $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
            $output .= $indent_tab . '<a href="' . $menu_slug . '" '.$link_target.'>' . $icon_value . purify_html($menu_title) . '</a>' . $indent_line;

            //check for megamenu
            if (!empty($mega_menu_enable)) {
                $output .= '<div class="xg_mega_menu_wrapper ' . $menu_item->ptype . '">';
                $output .= '<div class="xg-mega-menu-container"><div class="row">';
                    foreach ($mega_menu_items as $cat => $posts) {
                        $output .= '<div class="col-lg-3 col-md-6"><div class="xg-mega-menu-single-column-wrap">';
                        $output .= '<h4 class="mega-menu-title">' . get_mega_menu_cat_name_by_id($menu_item->ptype, $cat) . '</h4>';
                        $output .= '<ul>';
                        foreach ($posts as $post) {
                            $mega_menu_item_slug = get_mege_menu_item_url($menu_item->ptype,$post->lang_front->slug,$post->id);
                            $output .= '<li><a href="'.$mega_menu_item_slug.'">' . purify_html($post->lang_front->title) . '</a></li>';
                        }
                        $output .= '</ul>';
                        $output .= '</div></div>';
                    }
                $output .= '</div></div></div>';
                $mega_menu_enable = '';
            }
            //check it has submenu
            if (property_exists($menu_item, 'children')) {
                $output .= render_submenu_children($menu_item->children);
            }
            //load li end tag
            $output .= $indent_tab . '</li>' . $indent_line;
        }// end condition
    }

    return $output;
}

/* render submenu */


function render_submenu_children($menu_children)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ul class="sub-menu">' . $indent_line;
    foreach ($menu_children as $menu_item) {

        $li_class = '';
        //set li class if page is current page

        if ($menu_item->ptype == 'static') {
            $menu_title = get_static_option(str_replace('[lang]',get_user_lang(),$menu_item->pname));
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $page_details = Page::with('lang_front')->find($menu_item->pid);
            $menu_title = !empty($page_details) ? $page_details->lang_front->title: '';
            $menu_slug = !empty($page_details) ? route('frontend.dynamic.page', [$page_details->lang_front->slug,$page_details->id]) : '';
            if (!empty($page_details)){
                $li_class .= (request()->is(route('frontend.dynamic.page', [$page_details->lang_front->slug,$page_details->id])))   ? ' current-menu-item ' : '';
            }
        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'practice_area' || $menu_item->ptype == 'blog' || $menu_item->ptype == 'appointment' ||  $menu_item->ptype == 'case') {
            $menu_title = '';
            $menu_slug = '';
            if ($menu_item->ptype == 'practice_area') {

                $page_details = \App\PracticeArea::with('lang_front')->find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.practice.area.single', [$page_details->lang_front->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.practice.area.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'blog') {

                $page_details = \App\Blog::with('lang_front')->find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.blog.single', [$page_details->lang_front->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.blog.single', [$page_details->lang_front->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }

            } elseif ($menu_item->ptype == 'case') {

                $page_details = \App\Cases::with('lang_front')->find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = !empty($page_details) ? $page_details->lang_front->title : '';
                    $menu_slug = route('frontend.case.single', [$page_details->lang_front->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.case.single', [$page_details->lang_front->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }
            }
            elseif ($menu_item->ptype == 'appointment') {

                $page_details = \App\Appointment::with('lang_front')->find($menu_item->pid);
                if(!empty($page_details)){
                    $menu_title = !empty($page_details) ? $page_details->lang_front->title : '';
                    $menu_slug = route('frontend.appointment.single', [$page_details->lang_front->slug,$page_details->id]);
                    $li_class .= (request()->is(route('frontend.appointment.single', [$page_details->lang_front->slug,$page_details->id]))) ? ' current-menu-item ' : '';
                }

            }
        }


        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . purify_html($menu_item->icon) . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . purify_html($menu_item->antarget) . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)){
            $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
            $output .= $indent_tab . '<a href="' . $menu_slug . '" '.$link_target.'>' . $icon_value . purify_html($menu_title) . '</a>' . $indent_line;
        }
        //check it has submenu
        if (property_exists($menu_item, 'children')) {
            $output .= render_submenu_children($menu_item->children);
        }
        //load li end tag
        $output .= $indent_tab . '</li>' . $indent_line;
    }
    $output .= $indent_tab . '</ul>' . $indent_line;
    return $output;
}

/* render menu for drag & drop menu in admin panel */
function render_draggable_menu_by_id($id)
{
    $default_lang = get_default_language();
    $mega_menu_enable = '';
    $mega_menu_items = '';
    $output = '';
    $menu_details_from_db = Menu::find($id);

    $menu_data = json_decode($menu_details_from_db->content);

    $page_id = 0;
    foreach ($menu_data as $menu) :
        $page_id++;

        $menu_title = '';
        $menu_attr = 'data-ptype="' . $menu->ptype . '" ';

        if ($menu->ptype == 'static') {
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_attr .= ' data-pslug="' . $menu->pslug . '"';
            $menu_title = get_static_option(str_replace('[lang]',get_default_language(),$menu->pname));
        } elseif ($menu->ptype == 'dynamic') {
            $menu_attr .= ' data-pid="' . $menu->pid . '"';
            $menu_details = Page::find($menu->pid);
            $menu_title = !empty($menu_details) ? $menu_details->title : '';
        } elseif ($menu->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $menu->purl . '"';
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_title = $menu->pname;
        } elseif ($menu->ptype == 'event' || $menu->ptype == 'blog' || $menu->ptype == 'case' || $menu->ptype == 'contribution' ) {
            $menu_attr .= ' data-pid="' . $menu->pid . '"';
            if ($menu->ptype == 'event') {
                $menu_details = \App\Event::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '' ;
            }elseif ($menu->ptype == 'blog') {
                $menu_details = \App\Blog::with('lang_front')->find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '' ;
            }elseif ($menu->ptype == 'contribution') {
                $menu_details = \App\Contribution::find($menu->pid);
                $menu_title = !empty($menu_details) ?  $menu_details->lang_front->title : '' ;
            }
        } elseif ($menu->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Blog::with('lang_front')->where(['status' => 'publish'])->get();
        }elseif ($menu->ptype == 'appointment_mega_menu') {
            $menu_title = get_static_option('appointment_page_' . $default_lang . '_name') . __(' Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Appointment::with('lang_front')->where(['status' => 'publish'])->get();
        }

        $mega_menu_ids = [];
        if (property_exists($menu, 'items_id')) {
            $mega_menu_ids = explode(',', $menu->items_id);
            $menu_attr .= ' data-items_id="' . $menu->items_id . '" ';
        }

        $icon_value = property_exists($menu, 'icon') ? 'value="' . purify_html($menu->icon) . '"' : '';
        $link_target = property_exists($menu, 'antarget') ? 'value="' . purify_html($menu->antarget) . '"' : '';
        $icon_data = property_exists($menu, 'icon') ? 'data-icon="' . purify_html($menu->icon) . '"' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        if (!empty($menu_title)) {
            $output .= '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-handle">' . purify_html($menu_title) . '</div>' . $indent_line;
            $output .= $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';
        }

        //add mega menu extra field here
        if (!empty($mega_menu_enable)) {
            $output .= '<label for="items_id">' . __('Select Items') . '</label>';
            $output .= '<select name="items_id" multiple class="form-control">';
            foreach ($mega_menu_items as $data) :
                $selected = in_array($data->id, $mega_menu_ids) ? 'selected' : '';
                $output .= '<option value="' . $data->id . '" ' . $selected . ' >' . purify_html($data->lang_front->title) . '</option>';
            endforeach;
            $output .= '</select>';
            $mega_menu_enable = '';
        } else {
            if (!empty($menu_title)) {
                $output .= '<input type="text" class="anchor_target" placeholder="eg: _target" ' . purify_html($link_target) . '/>';
                $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . purify_html($icon_value) . '/>';
            }
        }
        if (!empty($menu_title)) {
            $output .= '</div>' . $indent_line;
        }

        //check it has children or not
        if (property_exists($menu, 'children')) {
            $output .= render_draggable_menu_children($menu->children, $page_id);
        }
        $output .= '</li>' . $indent_line;

    endforeach;
    return $output;
}

/* render submenu of menu for drag & drop menu in admin panel */
function render_draggable_menu_children($children, $page_id)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ol class="dd-list">' . $indent_line;
    foreach ($children as $item) {
        $page_id++;
        $menu_title = '';
        $menu_attr = 'data-ptype="' . $item->ptype . '" ';

        if ($item->ptype == 'static') {

            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_attr .= ' data-pslug="' . $item->pslug . '"';
            $menu_title = get_static_option(str_replace('[lang]',get_default_language(),$item->pname));;
        } elseif ($item->ptype == 'dynamic') {

            $menu_attr .= ' data-pid="' . $item->pid . '"';
            $menu_details = Page::with('lang_front')->find($item->pid);
            $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
        } elseif ($item->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $item->purl . '"';
            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_title = $item->pname;
        } elseif ($item->ptype == 'service' || $item->ptype == 'blog' || $item->ptype == 'product' || $item->ptype == 'appointment') {
            $menu_attr .= ' data-pid="' . $item->pid . '"';
           if ($item->ptype == 'blog') {
                $menu_details = \App\Blog::with('lang_front')->find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            }elseif ($item->ptype == 'appointment') {
                $menu_details = \App\Appointment::with('lang_front')->find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            }
        }
        $icon_value = property_exists($item, 'icon') ? 'value="' . purify_html($item->icon) . '"' : '';
        $icon_data = property_exists($item, 'icon') ? 'data-icon="' . purify_html($item->icon) . '"' : '';
        $link_target = property_exists($item, 'antarget') ? 'value="' . purify_html($item->antarget) . '"' : '';

        if (!empty($menu_title)) {
            $output .= $indent_tab . $indent_tab . '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<div class="dd-handle">' . purify_html($menu_title) . '</div>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';

            $output .= '<input type="text" class="anchor_target" placeholder="eg: _blank" ' . purify_html($link_target) . '/>';
            $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . purify_html($icon_value) . '/>';
            $output .= '</div>' . $indent_line;
        }

        if (property_exists($item, 'children')) {
            $output .= render_draggable_menu_children($item->children, $page_id);
        }
        if (!empty($menu_title)) {
            $output .= $indent_tab . $indent_tab . '</li>' . $indent_line;
        }
    }
    $output .= $indent_tab . '</ol>' . $indent_line;
    return $output;
}

function render_mega_menu_item_select_markup($type = '',$menu_id = null)
{
    $output = '';
    if (!empty($type)) {
        if ($type == 'practice_area_mega_menu') {
            $mega_menu_items = \App\PracticeArea::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($type == 'case_mega_menu') {
            $mega_menu_items = \App\Cases::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($type == 'blog_mega_menu') {
            $mega_menu_items = \App\Blog::with('lang_front')->where(['status' => 'publish'])->get();
        }
        elseif ($type == 'appointment_mega_menu') {
            $mega_menu_items = \App\Appointment::with('lang_front')->where(['status' => 'publish'])->get();
        }
        $output .= '<label for="items_id">' . __('Select Items') . '</label>';
        $output .= '<select name="items_id" multiple class="form-control">';
        foreach ($mega_menu_items as $data):
            $output .= '<option value="' . $data->id . '" >' . purify_html($data->lang_front->title) . '</option>';
        endforeach;
        $output .= '</select>';
    }
    return $output;
}
function get_mega_menu_cat_name_by_id($type, $cat_id)
{
    $return_val = '';

    switch ($type) {
        case('service_mega_menu'):
            $cat_details = \App\ServiceCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case('product_mega_menu'):
            $cat_details = \App\ProductCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case('appointment_mega_menu'):
            $cat_details = \App\AppointmentCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case('blog_mega_menu'):
            $cat_details = \App\BlogCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        default:
            break;
    }

    return $return_val;
}
function get_mege_menu_item_url($type, $slug,$id)
{
    $return_val = '';
    switch ($type) {
        case('service_mega_menu'):
            $return_val = route('frontend.services.single',[purify_html($slug),$id]);
            break;
        case('product_mega_menu'):
            $return_val =  route('frontend.products.single',[purify_html($slug),$id]);
            break;
        case('appointment_mega_menu'):
            $return_val =  route('frontend.appointment.single',[purify_html($slug),$id]);
            break;
        case('blog_mega_menu'):
            $return_val =  route('frontend.blog.single',[purify_html($slug),$id]);
            break;
        default:
            break;
    }

    return $return_val;
}

function paypal_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::paypal();
}
function paytm_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::paytm();
}
function stripe_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::stripe();
}
function paystack_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::paystack();
}
function razorpay_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::razorpay();
}
function flutterwaverave_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::flutterwaverev();
}
function mollie_gateway()
{
    return \App\PaymentGateway\PaymentGatewaySetup::mollie();
}
function script_currency_list()
{
    return \App\PaymentGateway\GlobalCurrency::script_currency_list();
}
function render_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_' . get_user_lang() . '_footer_copyright');
    $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);
    $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

    return purify_html_raw($footer_copyright_text);
}
function render_admin_panel_widgets_list()
{
    return \App\WidgetsBuilder\WidgetBuilderSetup::get_admin_panel_widgets();
}

function render_admin_saved_widgets($location)
{
    $output = '';
    $all_widgets = \App\Widgets::where(['widget_location' => $location])->orderBy('widget_order','asc')->get();
    foreach ($all_widgets as $widget) {
        $output .= \App\WidgetsBuilder\WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $widget->widget_name,
            'id' => $widget->id,
            'type' => 'update',
            'order' => $widget->widget_order,
            'location' => $widget->widget_location
        ]);
    }

    return $output;
}

function get_admin_sidebar_list()
{
    return \App\WidgetsBuilder\WidgetBuilderSetup::get_admin_widget_sidebar_list();
}

function render_frontend_sidebar($location, $args = [])
{
    $output = '';
    $all_widgets = \App\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();
    foreach ($all_widgets as $widget) {
        $output .= \App\WidgetsBuilder\WidgetBuilderSetup::render_widgets_by_name_for_frontend([
            'name' => $widget->widget_name,
            'location' => $location,
            'id' => $widget->id,
            'column' => $args['column'] ?? false
        ]);
    }
    return $output;
}
function get_all_language()
{
    $all_lang = Language::orderBy('default', 'DESC')->get();
    return $all_lang;
}
function get_language_name_by_slug($slug)
{
    $data = Language::where('slug', $slug)->first();
    return $data->name;
}
function get_blog_category_by_id($id,$lang = null, $type = '')
{
    $default_lang = $lang ?? LanguageHelper::default_slug();
    $return_val = __('uncategorized');
    $blog_cat = \App\BlogCategory::find($id);

    if (!empty($blog_cat)) {
        $return_val = $blog_cat->getTranslation('name',$default_lang);
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.blog.category', ['id' => $blog_cat->id, 'any' => Str::slug($blog_cat->name)]) . '">' . $blog_cat->name . '</a>';
        }
    }
    return $return_val;
}

function get_events_category_by_id($id,$lang = null, $type = '')
{
    $default_lang = $lang ?? LanguageHelper::default_slug();
    $return_val = __('uncategorized');
    $event_cat = \App\EventCategory::find($id);

    if (!empty($event_cat)) {
        $return_val = $event_cat->getTranslation('name',$default_lang);
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.event.category', ['id' => $event_cat->id, 'any' => Str::slug($event_cat->name)]) . '">' . $event_cat->name . '</a>';
        }
    }
    return $return_val;
}


function custom_amount_with_currency_symbol($amount, $text = false)
{
    $amount = number_format((float) $amount, 0, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = '<span class="sign">'.$symbol.'</span>'.$amount;
    if ($position == 'right') {
        $return_val = $amount .'<span class="sign">'.$symbol.'</span>';
    }
    return $return_val;
}
function admin_default_lang(){
    $default_lang= Language::where(['default'=>1,'status'=>'publish'])->first();
    return $default_lang->slug;
}
function front_default_lang(){
    $default_lang= !empty(session()->get('lang')) ? session()->get('lang') : Language::where('default',1)->first()->slug;
    return $default_lang;
}
function get_default_language_direction(){
    $default_lang = Language::where('default',1)->first();
    return !empty($default_lang) ? $default_lang->direction : 'ltr';
}
function multilang_field_display($fields,$field_name,$lang){
    foreach ($fields as $field) {
       if($field->lang == $lang){
           return $field->$field_name;
       }
    }
}
function get_product_category_by_id($id,$type = ''){
    $return_val = __('uncategorized');
    $prod_cat = \App\ProductCategory::with('lang_front')->find($id);
    if (!empty($prod_cat)){
        $return_val = $prod_cat->lang_front->name;
        if ($type == 'link' ){
            $return_val = '<a href="'.route('frontend.products.category',['id' => $prod_cat->id,'any' => Str::slug($prod_cat->lang_front->name) ]).'">'.$prod_cat->lang_front->name.'</a>';
        }
    }

    return $return_val;
}


function get_shipping_name_by_id($id)
{
    $shipping_details = \App\ProductShipping::find($id);
    return !empty($shipping_details) ? $shipping_details->title : "Undefined";
}
function get_image_category_name_by_id($id){
    $return_val = __('uncategorized');

    $category_details = \App\GalleryCategory::find($id);
    if (!empty($category_details)){
            $return_val = $category_details->title;
    }

    return $return_val;
}
function is_tax_enable()
{
    return get_static_option('product_tax') && get_static_option('product_tax_system') == 'exclusive'  ? true : false;
}
function render_ratings($ratings)
{
    $return_val = '';
    switch ($ratings) {
        case(1):
            $return_val = '<i class="fas fa-star"></i>';
            break;
        case(2):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(3):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(4):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case(5):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        default:
            break;
    }
    return $return_val;
}
function get_cart_items()
{
    $old_cart_item = session()->get('cart_item');
    $return_val = !empty($old_cart_item) ? $old_cart_item : [];

    return $return_val;
}
function get_product_ratings_avg_by_id($id)
{
    $average_ratings = ProductRatings::Where('product_id', $id)->pluck('ratings')->avg();
    return $average_ratings;
}
function get_appointment_ratings_avg_by_id($id)
{
    $average_ratings = \App\AppointmentReview::Where('appointment_id', $id)->pluck('ratings')->avg();
    return $average_ratings;
}
function get_attachment_url_by_id($id,$size=null){
    $return_val =  get_attachment_image_by_id($id,$size);
    return $return_val['image_id'] ?? '';
 }
 function cart_tax_for_mail_template($cart_items = [])
{
    $tax_percentage = get_static_option('product_tax_percentage') ?: 0;
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');

    $return_val = $cart_sub_total;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * (int) $coupon_details->discount;
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - (int) $coupon_details->discount;
        }
    }

    $tax_amount = ($return_val / 100) * (int) $tax_percentage;

    if (get_static_option('product_tax_type') == 'individual') {
        //write code for all individual tax amount and sum all of them
        $all_cart_items = $cart_items;
        $all_individual_tax = [];
        foreach ($all_cart_items as $item) {
            $product_details = \App\Products::find($item['id']);
            if (empty($product_details)) {
                continue;
            }
            $price = $product_details->sale_price * $item['quantity'];
            $tax_percentage = ($price / 100) * $product_details->tax_percentage;
            $all_individual_tax[] = $tax_percentage;
        }
        $tax_amount = array_sum($all_individual_tax);

    }

    return $tax_amount;
}
function get_cart_subtotal($currency_symbol = true)
{
    $total_cart_items = session()->get('cart_item');
    $return_val = $currency_symbol ? amount_with_currency_symbol(0) : 0;
    if (!empty($total_cart_items)) {
        $return_val = 0;
        foreach ($total_cart_items as $product_id => $cat_data) {
            $return_val += (int) $cat_data['price'];
        }
        return $currency_symbol ? amount_with_currency_symbol($return_val) : $return_val;
    }

    return $return_val;
}
function cart_total_items()
{
    $return_val = session()->get('cart_item');
    return !empty($return_val) ? array_sum(array_column($return_val, 'quantity')) : 0;
}
function render_cart_table()
{
    $ajax_preloader = '<div class="ajax-loading-wrap hide">
    <i class="fas fa-spinner fa-spin mr-1"></i>
                    </div>';

    $output = '';
    $all_cart_item = session()->get('cart_item');
    if (!empty($all_cart_item)) {
        $output = '<div class="table-responsive cart-table"><form id="cart_update_form" method="post"><table class="table table-bordered">';
        $output .= "\t" . '<thead><tr>';

        $output .= "\n\t" . '<th>' . __('Serial') . '</th>';
        $output .= "\n\t" . '<th>' . __('Thumbnail') . '</th>';
        $output .= "\n\t" . '<th>' . __('Product Name') . '</th>';
        $output .= "\n\t" . '<th>' . __('Quantity') . '</th>';
        $output .= "\n\t" . '<th>' . __('Unit Price') . '</th>';
        if (get_static_option('product_tax_type') == 'individual') {
            $output .= "\n\t" . '<th>' . __('Tax') . '</th>';
        }
        $output .= "\n\t" . '<th>' . __('Subtotal') . '</th>';
        $output .= "\n\t" . '<th>' . __('Action') . '</th>';
        $output .= "\n\t" . '</tr></thead>';

        $output .= "\n\t" . '<tbody>';
        $a = 1;
        foreach ($all_cart_item as $id => $item) {


            $single_product = \App\Products::with('lang_front')->find($id);
            $colspan = 7;
            if (empty($single_product)) {
                continue;
            }

            $tax_markup = !empty($single_product->tax_percentage) ? '<small>+' . __('Tax') . ' (' . $single_product->tax_percentage . '%)</small>' : '';
            $output .= '<tr>';
            $output .= '<td>' . $a . '<input name="product_id[]" type="hidden" value="' . $id . '">' . '</td>';
            $output .= '<td><div class="thumbnail">' . render_image_markup_by_attachment_id($single_product->image, '', 'thumb') . '</div></td>';
            $output .= '<td><h4 class="product-title"><a href="' . route('frontend.products.single',['slug'=>$single_product->lang_front->slug,'id'=>$single_product->id]) . '">' . $single_product->lang_front->title . '</a></h4></td>';
            $output .= '<td><input type="number" name="product_quantity[]" class="quantity" min="0" value="' . $item['quantity'] . '"></td>';
            $output .= '<td class="unit_price">' . amount_with_currency_symbol($single_product->sale_price) . '</td>';
            $tax_amount = 0;
            if (get_static_option('product_tax_type') == 'individual') {
                $tax_amount = ($single_product->sale_price / 100) * $single_product->tax_percentage;
                $output .= '<td class="tax_amount">' . amount_with_currency_symbol($tax_amount) . '(' . $single_product->tax_percentage . '%)</td>';
                $colspan = 8;
            }
            $subtotal = (get_static_option('product_tax_type') == 'individual') ? $item['price'] + ($tax_amount * $item['quantity']) : $item['price'];
            $output .= '<td>' . amount_with_currency_symbol($subtotal) . '</td>';
            $output .= '<td><div class="cart-action-wrap"><a href="#" class="btn btn-sm btn-danger ajax_remove_cart_item"  data-product_id="' . $single_product->id . '"><i class="fas fa-trash-alt"></i></a>' . $ajax_preloader . '</div></td>';
            $output .= '</tr>';
            $a++;
        }

        $output .= "\n\t" . '</tbody>';
        $output .= "\n\t" . '<tfoot>';
        $output .= '<tr><td colspan="' . $colspan . '">';
        $output .= '<div class="cart-table-footer-wrap">';
        $output .= '<div class="coupon-wrap"><input type="text" class="form-control" name="coupon_code" placeholder="' . __('Coupon Code') . '"><button class="btn-boxed btn-small add_coupon_code_btn">' . __('Submit') . '</button>' . $ajax_preloader . '</div>';
        $output .= '<div class="update-cart-wrap">' . $ajax_preloader . '<button class="btn-small update_cart_items_btn">' . __('Update Cart') . '</button></div>';
        $output .= '</div>';
        $output .= '</td></tr>';
        $output .= "\n\t" . '</tfoot>';

        $output .= '</table></form></div>';
        return $output;
    }

    $output = '<div class="alert alert-warning">' . __('No Item In Cart!') . '</div>';
    return $output;
}
function render_cart_total_table()
{
    $output = '';

    $car_total = cart_total_items();
    if ($car_total > 0) {
        $output .= '<h4 class="title">' . __('Order Summery') . '</h4><div class="cart-total-table-wrap">';
        $output .= ' <div class="cart-total-table table-responsive"><table class="table table-bordered"> <tbody>';
        $output .= ' <tr><th>' . __('Subtotal') . '</th><td>' . get_cart_subtotal() . '</td></tr>';
        $output .= ' <tr><th>' . __('Coupon Discount') . '</th><td>-' . get_cart_coupon_discount() . '</td></tr>';
        if (is_tax_enable()) {
            $tax_percentage = get_static_option('product_tax_type') == 'total' ? ' (' . get_static_option('product_tax_percentage') . '%)' : '';
            $output .= ' <tr><th>' . __('Tax') . $tax_percentage . '</th><td>+ ' . amount_with_currency_symbol(get_cart_tax()) . '</td></tr>';
        }
        if (is_shipping_available()) {
            $output .= ' <tr><th>' . __('Shipping Cost') . '</th><td>+ ' . get_cart_shipping_cost() . '</td></tr>';
        }

        $output .= ' <tr><th>' . __('Total') . '</th><td><strong>' . get_cart_total_cost() . '</strong></td></tr>';
        $output .= '</tbody></table></div>';
        $output .= '</div><a href="' . route('frontend.products.checkout') . '" class="btn-boxed btn-small cart-view">' . __('Process To Checkout') . '</a></div>';
    }

    return $output;
}


function get_cart_coupon_discount_by_code( $code,$symbol = true)
{
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;
    if (!empty($code)) {
        return $return_val;
    }
    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $code)->first();
        if ($coupon_details->discount_type === 'percentage') {
            $return_val = $symbol ? $coupon_details->discount . '%' : (int) $coupon_details->discount;
        } elseif ($coupon_details->discount_type === 'amount') {
            $return_val = $symbol ? amount_with_currency_symbol($coupon_details->discount) : (int) $coupon_details->discount;
        }
    }

    return $return_val;
}

function get_cart_coupon_discount($symbol = true)
{
    $get_coupon_discount = session()->get('coupon_discount');
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $return_val = $symbol ? $coupon_details->discount . '%' : (int) $coupon_details->discount;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $symbol ? amount_with_currency_symbol($coupon_details->discount) : (int) $coupon_details->discount;
        }
    }

    return $return_val;
}
function get_cart_shipping_cost($symbol = true)
{
    $get_shipping_charge = session()->get('shipping_charge');
    $return_val = $symbol ? amount_with_currency_symbol(0) : 0;

    if (!empty($get_shipping_charge)) {
        $shipping_details = \App\ProductShipping::where('id', $get_shipping_charge)->first();
        $shipping_details = !empty($shipping_details) ? $shipping_details : 0;
        $return_val = $symbol ? amount_with_currency_symbol($shipping_details->cost) : (int) $shipping_details->cost;
    }
    return is_shipping_available() ? $return_val : 0;
}


function get_cart_total_cost($symbol = true)
{
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');
    $get_shipping_id = session()->get('shipping_charge');
    $shipping_details = \App\ProductShipping::where('id', $get_shipping_id)->first();
    $get_shipping_charge = !empty($shipping_details) && is_shipping_available() ? $shipping_details->cost : 0;

    $return_val = $symbol ? amount_with_currency_symbol($cart_sub_total + $get_shipping_charge + get_cart_tax()) : $cart_sub_total + $get_shipping_charge + get_cart_tax();

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * (int) $coupon_details->discount;
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - (int) $coupon_details->discount;
        }

        $total_cost = $return_val + $get_shipping_charge + get_cart_tax();
        return $symbol ? amount_with_currency_symbol($total_cost) : $total_cost;
    }

    return $return_val;
}
function get_cart_tax()
{
    $tax_percentage = get_static_option('product_tax_percentage') ? get_static_option('product_tax_percentage') : 0;
    $cart_sub_total = get_cart_subtotal(false);
    $get_coupon_discount = session()->get('coupon_discount');

    $return_val = $cart_sub_total;

    if (!empty($get_coupon_discount)) {
        $coupon_details = \App\ProductCoupon::where('code', $get_coupon_discount)->first();
        if ($coupon_details->discount_type == 'percentage') {
            $discount_bal = ($cart_sub_total / 100) * intval($coupon_details->discount);
            $return_val = $cart_sub_total - $discount_bal;
        } elseif ($coupon_details->discount_type == 'amount') {
            $return_val = $cart_sub_total - intval($coupon_details->discount);
        }
    }

    $tax_amount = ($return_val / 100) * intval($tax_percentage);

    if (get_static_option('product_tax_type') == 'individual') {
        //write code for all individual tax amount and sum all of them
        $all_cart_items = session()->get('cart_item');
        $all_individual_tax = [];
        foreach ($all_cart_items as $item) {
            $product_details = \App\Products::find($item['id']);
            if (empty($product_details)) {
                continue;
            }
            $price = $product_details->sale_price * $item['quantity'];
            $tax_percentage = ($price / 100) * $product_details->tax_percentage;
            $all_individual_tax[] = $tax_percentage;
        }
        $tax_amount = array_sum($all_individual_tax);

    }

    return $tax_amount;
}
function is_shipping_available(){
    $all_cart_item = session()->get('cart_item');
    $return_val = true;
    $cart_item_type = !empty($all_cart_item) ? array_unique(array_column($all_cart_item,'type')) : [];
    if (count($cart_item_type)  == 1 && in_array('digital',$cart_item_type)){
        $return_val = false;
    }

    return $return_val;
}
function rest_cart_session()
{
    session()->forget([
        'shipping_charge',
        'cart_item',
        'coupon_discount',
    ]);
}
function all_lang_slugs(){
    return Language::all()->pluck('slug')->toArray();
}
function exist_slugs($model_data){
    return $model_data->lang_all->pluck('lang')->toArray();
}

function purify_html($html){
    return strip_tags(\Mews\Purifier\Facades\Purifier::clean($html));
}

function purify_html_raw($html){
    return \Mews\Purifier\Facades\Purifier::clean($html);
}

function attorney_practice_items($pactice_ids,$with = 'lang'){
    $data = PracticeArea::with($with)->whereIn('id',$pactice_ids)->get();
    return $data ?? [__('uncategorized')];
}


function get_case_category_by_id($id,$output = 'array'){

    $all_category = \App\CaseCategoryLang::whereIn('category_id',$id)->where(['lang' => get_user_lang()])->get()->pluck('name')->toArray();

    switch ($output){
        case ("string"):
            return implode(' ',
                array_map(function ($item){
                    return Str::slug($item);
                },$all_category)
            );
            break;
        default:
            return $all_category;
            break;
    }
}

//New Menu Functions
function render_pages_list($lang = null){
    $instance = new \App\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_static_pages_list($lang);
}
function render_dynamic_pages_list($lang = null){
    $instance = new \App\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_post_type_page_list($lang);
}
function render_mega_menu_list($lang = null){
    $instance = new \App\MenuBuilder\MegaMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_menu($id){
    $instance = new \App\MenuBuilder\MenuBuilderAdminRender();
    return $instance->render_admin_panel_menu($id);
}
function render_frontend_menu($id){
    $instance = new \App\MenuBuilder\MenuBuilderFrontendRender();
    return $instance->render_frrontend_panel_menu($id);
}

function get_percentage($amount, $numb)
{
    $numb = !empty($numb) ? $numb : 0;

    if ($amount > 0) {
        return round($numb / ($amount / 100), 2);
    }
    return 0;
}


function render_gallery_image_attachment_preview($gal_image)
{
    if (empty($gal_image)) {
        return;
    }
    $output = '';
    $gallery_images = explode('|', $gal_image);
    foreach ($gallery_images as $gl_img) {
        $work_section_img = get_attachment_image_by_id($gl_img, null, true);
        if (!empty($work_section_img)) {
            $output .= sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt=""> </div></div></div>', $work_section_img['img_url']);
        }
    }
    return $output;
}

function render_attachment_preview_for_admin($id)
{
    $markup = '';
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    $img_alt = $header_bg_img['img_alt'] ?? '';
    if (!empty($img_url)) {
        $markup = sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt="%2$s"></div></div></div>', $img_url, $img_alt);
    }
    return $markup;
}

 function get_page_slug($id,$default = null){
    return Page::where('id',$id)->first()->slug ?? $default;
}
function get_page_slug_two($slug){
    return Page::where('slug',$slug)->first();
}


function get_navbar_style(){
    $fallback = get_static_option('global_navbar_variant');
    if (request()->routeIs('frontend.dynamic.page')){
        $page_info = Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;
    }elseif(request()->routeIs('homepage')){
        $page_info = Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;
    }
    return $fallback;
}


function get_footer_style(){
    $fallback = get_static_option('global_footer_variant') ;
    if (request()->routeIs('frontend.dynamic.page')){
        $page_info = Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    }elseif(request()->routeIs('homepage')){
        $page_info = Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    }
    return $fallback;
}

function blog_comment_count($item){
    $comment_count = App\BlogComment::where('blog_id',$item->id)->count();
    $comment_condition_check = $comment_count == 0 ? '' : "($comment_count)";
    return $comment_condition_check;
}

function render_site_title($title){
    $site_title = get_static_option('site_'.LanguageHelper::user_lang_slug().'_title');
    return <<<HTML

    <title> {$title} - {$site_title} </title>
HTML;
}

 function render_site_meta(){

        $user_lang = LanguageHelper::user_lang_slug();
        $site_tags = get_static_option('site_meta_'.$user_lang.'_tags');
        $site_desc =  get_static_option('site_meta_'.$user_lang.'_description');
        $site_og_meta_title =  get_static_option('og_meta_'.$user_lang.'_title');
        $site_og_meta_description =  get_static_option('og_meta_'.$user_lang.'_description');
        $site_og_meta_site_name =  get_static_option('og_meta_'.$user_lang.'_site_name');
        $site_og_meta_url =  get_static_option('og_meta_'.$user_lang.'_url');
        $site_og_meta_image =  render_og_meta_image_by_attachment_id(get_static_option('og_meta_'.$user_lang.'_image'));

    return <<<HTML
       <meta name="site_meta_{$user_lang}_tags" content="{$site_tags}">
       <meta name="site_meta_{$user_lang}_description" content="{$site_desc}">
       <meta name="og_meta_{$user_lang}_title" content="{$site_og_meta_title}">
       <meta name="og_meta_{$user_lang}_description" content="{$site_og_meta_description}">
       <meta name="og_meta_{$user_lang}_site_name" content="{$site_og_meta_site_name}">
       <meta name="og_meta_{$user_lang}_url" content="{$site_og_meta_url}">
       <meta name="og_meta_{$user_lang}_image" content="{$site_og_meta_image}">
HTML;

}


function render_page_meta_data($blog_post){

    $user_lang = LanguageHelper::user_lang_slug();
    $site_url = url('/');

    $meta_title =  optional($blog_post->meta_data)->meta_title;
    $site_tags = optional($blog_post->meta_data)->meta_tags;
    $site_description =  optional($blog_post->meta_data)->meta_description;

    $facebook_meta_tags = optional($blog_post->meta_data)->facebook_meta_tags;
    $facebook_meta_description =  optional($blog_post->meta_data)->facebook_meta_description;
    $facebook_meta_image =  get_attachment_image_by_id(optional($blog_post->meta_data)->facebook_meta_image)['img_url'] ?? "";

    $twitter_meta_tags = optional($blog_post->meta_data)->twitter_meta_tags;
    $twitter_meta_description = optional( $blog_post->meta_data)->twitter_meta_description;
    $twitter_meta_image =  get_attachment_image_by_id(optional($blog_post->meta_data)->twitter_meta_image)['img_url'] ?? "";
    $current_url = \URL::current();

    return <<<HTML
       <meta name="meta_title" content="{$meta_title}" />
       <meta name="meta_tags" content="{$site_tags}" />
       <meta name="meta_description" content="{$site_description}" />
       <!--Facebook-->
       <meta property="og:url" content="{$current_url}" />
       <meta property="og:type" content="{$facebook_meta_tags}" />
       <meta property="og:title" content="{$meta_title}" />
       <meta property="og:description" content="{$facebook_meta_description}" />
       <meta  property="og:image" content="{$facebook_meta_image}" />
       
       <meta property="og:image" content="{$facebook_meta_image}" />
<meta property="og:image:secure_url" content="https://xgenious.com/wp-content/uploads/2021/10/xgenious-logo-with-dark-profile-photo.png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="1200" />
<meta property="og:image:alt" content="{$meta_title}" />

       <!--Twitter-->
       <meta name="twitter:card" content="{$twitter_meta_tags}" />
       <meta name="twitter:site" content="{$site_url}" />
       <meta name="twitter:title" content="{$meta_title}" />
       <meta name="twitter:description" content="$twitter_meta_description" />
       <meta name="twitter:image" content="{$twitter_meta_image}" />
HTML;

}

function get_blog_slug_by_page_id($id){
    $page_details = Page::find($id);
    return is_null($page_details) ? 'blog' : $page_details->slug;
}

function get_blog_category($data){

        $colors = ['text-primary','text-danger','text-success','text-info','text-dark'];
        foreach($data->category_id as $key => $cat) {
            //$comma = !$loop->last ? ' | ' : ' ';
           '<span class="'.$colors[random_int(0,4)].'">'.
                   '. $cat->getTranslation("title", $default_lang, true) .'
            .'</span >';
    }
}


function get_blog_created_user($item){

    if(empty($item)){
        return [];
    }

    if ($item->created_by === 'user') {
        $user_id = $item->user_id;
    } else {
        $user_id = $item->admin_id;
    }

    return !is_null($user_id) ?  route('frontend.user.created.blog', ['user' => $item->created_by, 'id' => $user_id]) : route('frontend.blog.single',$item->slug);

}



function upload_image_with_external_src($src)
{
    if ($src) {
        $image = $src;
        $image_dimension = getimagesize($image);;

        $image_width = $image_dimension[0];
        $image_height = $image_dimension[1];
        $image_dimension_for_db = $image_width . ' x ' . $image_height . ' pixels';
        $image_size_for_db = null;

        $image_extenstion = pathinfo($src,PATHINFO_EXTENSION);
        $image_name_with_ext = pathinfo($src,PATHINFO_FILENAME);

        $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
        $image_name = strtolower(Str::slug($image_name));
        $image_db = $image_name . time() . '.' . $image_extenstion;
        $image_grid = 'grid-' . $image_db;
        $image_large = 'large-' . $image_db;
        $image_thumb = 'thumb-' . $image_db;
        $image_semi_large = 'semi-large-' . $image_db;
        $image_box = 'box-' . $image_db;

        $folder_path = 'assets/uploads/media-uploader/';
        $resize_grid_image = Image::make($image)->resize(350, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_large_image = Image::make($image)->resize(740, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $resize_semi_large_image = Image::make($image)->resize(530, 350, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image_box_image = Image::make($image)->resize(527, 427, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_thumb_image = Image::make($image)->resize(200, 200);

        file_put_contents($folder_path.'/'.$image_db, file_get_contents($src));
        $resize_thumb_image->save($folder_path . $image_thumb);

       $media_object = MediaUpload::create([
            'title' => $image_name_with_ext,
            'size' => formatBytes($image_size_for_db),
            'path' => $image_db,
            'dimensions' => $image_dimension_for_db,
            'type' => 'admin',
            'user_id' => auth('admin')->id(),
        ]);

        if ($image_width > 150) {
            $resize_thumb_image->save($folder_path . $image_thumb);
            $resize_grid_image->save($folder_path . $image_grid);
            $resize_large_image->save($folder_path . $image_large);
            $resize_semi_large_image->save($folder_path . $image_semi_large);
            $image_box_image->save($folder_path . $image_box);
        }

        return $media_object->id;
    }
}

    function set_rss_import_automation($id){

        $all_rss_feed_info = RssFeedInfo::where('status','on')->where("id",$id)->get();

        foreach ($all_rss_feed_info as $rss_item) {

            $rss_link = $rss_item->link;
            $single_feed = $rss_item->imported_item;

                $response = Http::get($rss_link);
                $body = $response->body() ?? [];
                $xmlObject = simplexml_load_string($body);
                $json = json_encode($xmlObject);
                $phpArray = json_decode($json, true);
                $all_items = $phpArray['channel']['item'] ?? [];


                $final_all_item = [];
                for ($i = 0; $i < $single_feed; $i++) {
                    if (isset($all_items[$i])) {
                        $final_all_item[] = $all_items[$i];
                    }
                }

                foreach ($final_all_item ?? [] as $item) {
                    $title = $item['title'] ?? '';
                    $link = $item['link'] ?? '';
                    $published_date = $item['pubDate'] ?? '';
                    $date = optional(Carbon::parse($published_date))->format('d M, Y');

                    $desc = NULL;
                    if (isset($item['description']) && !is_array($item['description'])) {
                        $desc = $item['description'];
                    } elseif (isset($item['description']) && is_array($item['description']) && count($item['description']) > 0) {
                        $desc = implode("\n", $item['description']);
                    }
                    $doc = new \DOMDocument();

                    if (!is_null($doc) && !is_array($doc) && $desc != '') {
                        $doc->loadHTML($desc ?? '');
                    }


                    $xpath = new \DOMXPath($doc ?? '');
                    $src = $xpath->evaluate("string(//img/@src)") ?? '';

                    $image_extenstion = pathinfo($src, PATHINFO_EXTENSION);
                    $blog = new Blog();

                    if (in_array($image_extenstion, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $img = 'assets/tmp/test.png';
                        file_put_contents($img, file_get_contents($src));
                        $image_condition = $src ? $src : NULL;
                        $blog->image = upload_image_with_external_src($image_condition);
                        $blog->save();
                    }

                    $desc_condition = !$src ? $desc : NULL;

                    if (!empty($item)) {
                        $blog->setTranslation('title', LanguageHelper::user_lang_slug(), $title)
                            ->setTranslation('blog_content', LanguageHelper::user_lang_slug(), $desc_condition);

                        $blog->slug = Str::slug($title);
                        $blog->status = 'publish';
                        $blog->visibility = 'public';
                        $blog->save();
                    }
                }
        }

        RssFeedInfo::where('status','on')->where("id",$id)->update(["last_import_time" => Carbon::now()->format("Y-m-d H:i:s")]);
        return true;
    }




