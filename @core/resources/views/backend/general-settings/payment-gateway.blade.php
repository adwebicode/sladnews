@extends('backend.admin-master')
@section('site-title')
    {{__('Payment Settings')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/media-uploader.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/summernote-bs4.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Payment Gateway Settings")}}</h4>
                        @include('backend/partials/error')
                        <form action="{{route('admin.general.payment.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="site_global_currency">{{__('Site Global Currency')}}</label>
                                        <select name="site_global_currency" class="form-control"
                                                id="site_global_currency">
                                            @foreach(script_currency_list() as $cur => $symbol)
                                                <option value="{{$cur}}"
                                                        @if(get_static_option('site_global_currency') == $cur) selected @endif>{{$cur.' ( '.$symbol.' )'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="site_currency_symbol_position">{{__('Currency Symbol Position')}}</label>
                                        @php $all_currency_position = ['left','right']; @endphp
                                        <select name="site_currency_symbol_position" class="form-control"
                                                id="site_currency_symbol_position">
                                            @foreach($all_currency_position as $cur)
                                                <option value="{{$cur}}"
                                                        @if(get_static_option('site_currency_symbol_position') == $cur) selected @endif>{{ucwords($cur)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="site_default_payment_gateway">{{__('Default Payment Gateway')}}</label>
                                        <select name="site_default_payment_gateway" class="form-control" >
                                            @php
                                                $all_gateways = ['paypal','manual_payment','mollie','paytm','stripe','razorpay','flutterwave','paystack'];
                                            @endphp
                                            @foreach($all_gateways as $gateway)
                                                @if(!empty(get_static_option($gateway.'_gateway')))
                                                    <option value="{{$gateway}}" @if(get_static_option('site_default_payment_gateway') == $gateway) selected @endif>{{ucwords(str_replace('_',' ',$gateway))}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @php $global_currency = get_static_option('site_global_currency');@endphp
                                    @if($global_currency != 'USD')
                                        <div class="form-group">
                                            <label for="site_{{strtolower($global_currency)}}_to_usd_exchange_rate">{{__($global_currency.' to USD Exchange Rate')}}</label>
                                            <input type="number" class="form-control"
                                                   name="site_{{strtolower($global_currency)}}_to_usd_exchange_rate"
                                                   value="{{get_static_option('site_'.$global_currency.'_to_usd_exchange_rate')}}">
                                            <span class="info-text">{{sprintf(__('enter %1$s to USD exchange rate. eg: 1 %2$s = ? USD'),$global_currency,$global_currency) }}</span>
                                        </div>
                                    @endif

                                    @if($global_currency != 'INR' && !empty(get_static_option('paytm_gateway') || !empty(get_static_option('razorpay_gateway'))))
                                        <div class="form-group">
                                            <label for="site_{{strtolower($global_currency)}}_to_inr_exchange_rate">{{__($global_currency.' to INR Exchange Rate')}}</label>
                                            <input type="number" class="form-control"
                                                   name="site_{{strtolower($global_currency)}}_to_inr_exchange_rate"
                                                   value="{{get_static_option('site_'.$global_currency.'_to_inr_exchange_rate')}}">
                                            <span class="info-text">{{__('enter '.$global_currency.' to INR exchange rate. eg: 1'.$global_currency.' = ? INR')}}</span>
                                        </div>
                                    @endif

                                    @if($global_currency != 'NGN' && !empty(get_static_option('paystack_gateway') ))
                                        <div class="form-group">
                                            <label for="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate">{{__($global_currency.' to NGN Exchange Rate')}}</label>
                                            <input type="number" class="form-control"
                                                   name="site_{{strtolower($global_currency)}}_to_ngn_exchange_rate"
                                                   value="{{get_static_option('site_'.$global_currency.'_to_ngn_exchange_rate')}}">
                                            <span class="info-text">{{__('enter '.$global_currency.' to NGN exchange rate. eg: 1'.$global_currency.' = ? NGN')}}</span>
                                        </div>
                                    @endif

                                    <div class="accordion-wrapper">
                                        <div id="accordion-payment">
                                            <div class="card">
                                                <div class="card-header" id="cash_on_delivery_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#pcash_on_delivery_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('Cash On Delivery Settings (only for product order)')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="pcash_on_delivery_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="cash_on_delivery_gateway"><strong>{{__('Enable Cash On Delivery')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="cash_on_delivery_gateway"  @if(!empty(get_static_option('cash_on_delivery_gateway'))) checked @endif id="cash_on_delivery_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_logo"><strong>{{__('Cash On Delivery Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $paypal_img = get_attachment_image_by_id(get_static_option('cash_on_delivery_preview_logo'),null,true);
                                                                        $paypal_image_btn_label = 'Upload Image';
                                                                    @endphp
                                                                    @if (!empty($paypal_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$paypal_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $paypal_image_btn_label = 'Change Image'; @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="cash_on_delivery_preview_logo" name="cash_on_delivery_preview_logo" value="{{get_static_option('cash_on_delivery_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($paypal_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="paypal_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#paypal_settings_content"
                                                                aria-expanded="true">
                                                            <span class="page-title"> {{__('Paypal Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="paypal_settings_content" class="collapse show"
                                                     data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Available Currency For Paypal is")}} {{implode(',',paypal_gateway()->supported_currency_list())}}</p>
                                                            <p>{{__('if your currency is not available in paypal, it will convert you currency value to USD value based on your currency exchange rate.')}}</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paypal_gateway"><strong>{{__('Enable Paypal')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="paypal_gateway"
                                                                       @if(!empty(get_static_option('paypal_gateway'))) checked
                                                                       @endif id="paypal_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paypal_test_mode"><strong>{{__('Enable Test Mode For Paypal')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="paypal_test_mode"
                                                                       @if(!empty(get_static_option('paypal_test_mode'))) checked
                                                                        @endif >
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_logo"><strong>{{__('Paypal Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $paypal_img = get_attachment_image_by_id(get_static_option('paypal_preview_logo'),null,true);
                                                                        $paypal_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($paypal_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb"
                                                                                         src="{{$paypal_img['img_url']}}"
                                                                                         alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $paypal_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="paypal_preview_logo"
                                                                       name="paypal_preview_logo"
                                                                       value="{{get_static_option('paypal_preview_logo')}}">
                                                                <button type="button"
                                                                        class="btn btn-info media_upload_form_btn"
                                                                        data-btntitle="{{__('Select Image')}}"
                                                                        data-modaltitle="{{__('Upload Image')}}"
                                                                        data-toggle="modal"
                                                                        data-target="#media_upload_modal">
                                                                    {{__($paypal_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paypal_app_client_id">{{__('Paypal Client ID')}}</label>
                                                            <input type="text" name="paypal_app_client_id"
                                                                   class="form-control"
                                                                   value="{{get_static_option('paypal_app_client_id')}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paypal_app_secret">{{__('Paypal Secret')}}</label>
                                                            <input type="text" name="paypal_app_secret"
                                                                   class="form-control"
                                                                   value="{{get_static_option('paypal_app_secret')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="paytm_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#paytm_settings_content"
                                                                aria-expanded="false">
                                                            <span class="page-title"> {{__('Paytm Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="paytm_settings_content" class="collapse"
                                                     data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="payment-notice alert alert-warning">
                                                                <p>{{__("Available Currency For Paytm is")}} {{implode(',',paytm_gateway()->supported_currency_list())}}</p>
                                                                <p>{{__('if your currency is not available in paytm, it will convert you currency value to INR value based on your currency exchange rate.')}}</p>
                                                                <p>{{__('You have to set conversion value of INR to use PAYTM payment gateway')}}</p>
                                                            </div>
                                                            <label for="paytm_gateway"><strong>{{__('Enable/Disable Paytm')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="paytm_gateway"
                                                                       @if(!empty(get_static_option('paytm_gateway'))) checked
                                                                       @endif id="paytm_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_test_mode"><strong>{{__('Enable Test Mode For Paytm')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="paytm_test_mode"
                                                                       @if(!empty(get_static_option('paytm_test_mode'))) checked
                                                                        @endif >
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_logo"><strong>{{__('Paytm Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $paytm_img = get_attachment_image_by_id(get_static_option('paytm_preview_logo'),null,true);
                                                                        $paytm_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($paytm_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb"
                                                                                         src="{{$paytm_img['img_url']}}"
                                                                                         alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $paytm_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="paytm_preview_logo"
                                                                       name="paytm_preview_logo"
                                                                       value="{{get_static_option('paytm_preview_logo')}}">
                                                                <button type="button"
                                                                        class="btn btn-info media_upload_form_btn"
                                                                        data-btntitle="{{__('Select Image')}}"
                                                                        data-modaltitle="{{__('Upload Image')}}"
                                                                        data-toggle="modal"
                                                                        data-target="#media_upload_modal">
                                                                    {{__($paytm_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_merchant_key">{{__('Paytm Merchant Key')}}</label>
                                                            <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" value="{{get_static_option('paytm_merchant_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_merchant_mid">{{__('Paytm Merchant ID')}}</label>
                                                            <input type="text" name="paytm_merchant_mid" id="paytm_merchant_mid"  value="{{get_static_option('paytm_merchant_mid')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_merchant_website">{{__('Paytm Merchant Website')}}</label>
                                                            <input type="text" name="paytm_merchant_website" id="paytm_merchant_website"  value="{{get_static_option('paytm_merchant_website')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_channel">{{__('Paytm channel')}}</label>
                                                            <input type="text" name="paytm_channel" value="{{get_static_option('paytm_channel')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paytm_industry_type">{{__('Paytm Industry Type')}}</label>
                                                            <input type="text" name="paytm_industry_type" value="{{get_static_option('paytm_industry_type')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="stripe_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#stripe_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('Stripe Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="stripe_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Stripe supported currency ")}} {{implode(',',stripe_gateway()->supported_currency_list())}}</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="stripe_gateway"><strong>{{__('Enable/Disable Stripe')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="stripe_gateway"  @if(!empty(get_static_option('stripe_gateway'))) checked @endif id="stripe_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="stripe_logo"><strong>{{__('Stripe Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $stripe_img = get_attachment_image_by_id(get_static_option('stripe_preview_logo'),null,true);
                                                                        $stripe_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($stripe_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$stripe_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $stripe_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="stripe_preview_logo" name="stripe_preview_logo" value="{{get_static_option('stripe_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($stripe_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="stripe_publishable_key">{{__('Stripe Publishable Key')}}</label>
                                                            <input type="text" name="stripe_publishable_key" id="stripe_publishable_key" value="{{get_static_option('stripe_publishable_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="stripe_secret_key">{{__('Stripe Secret')}}</label>
                                                            <input type="text" name="stripe_secret_key" id="stripe_secret_key"  value="{{get_static_option('stripe_secret_key')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="razorpay_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#razorpay_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('Razorpay Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="razorpay_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Available Currency For Razorpay is, ['INR']")}}</p>
                                                            <p>{{__('if your currency is not available in Razorpay, it will convert you currency value to INR value based on your currency exchange rate.')}}</p>
                                                            <p>{{__('You have to set conversion value of INR to use Razorpay payment gateway')}}</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="razorpay_gateway"><strong>{{__('Enable/Disable Razorpay')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="razorpay_gateway"  @if(!empty(get_static_option('razorpay_gateway'))) checked @endif id="razorpay_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="razorpay_logo"><strong>{{__('Razorpay Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $razorpay_img = get_attachment_image_by_id(get_static_option('razorpay_preview_logo'),null,true);
                                                                        $razorpay_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($razorpay_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$razorpay_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $razorpay_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="razorpay_preview_logo" name="razorpay_preview_logo" value="{{get_static_option('razorpay_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($razorpay_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="razorpay_key">{{__('Razorpay Key')}}</label>
                                                            <input type="text" name="razorpay_key" id="razorpay_key" value="{{get_static_option('razorpay_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="razorpay_secret">{{__('Razorpay Secret')}}</label>
                                                            <input type="text" name="razorpay_secret" id="razorpay_secret"  value="{{get_static_option('razorpay_secret')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="paystack_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#paystack_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('PayStack Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="paystack_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Available Currency For Paystack is")}} {{implode(',',paystack_gateway()->supported_currency_list())}}</p>
                                                            <p>{{__('if your currency is not available in Paystack, it will convert you currency value to NGN value based on your currency exchange rate.')}}</p>
                                                        </div>
                                                        <p class="margin-bottom-30 margin-top-20 info-paragraph">
                                                            {{__('Don\'t forget to put below url to "Settings > API Key & Webhook > Callback URL" in your paystack admin panel')}}
                                                            <input type="text" class="info-url" value="{{route('frontend.paystack.callback')}}">
                                                        </p>
                                                        <div class="form-group">
                                                            <label for="paystack_gateway"><strong>{{__('Enable/Disable PayStack')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="paystack_gateway"  @if(!empty(get_static_option('paystack_gateway'))) checked @endif id="paystack_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paystack_preview_logo"><strong>{{__('PayStack Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $paystack_img = get_attachment_image_by_id(get_static_option('paystack_preview_logo'),null,true);
                                                                        $paystack_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($paystack_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$paystack_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $paystack_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="paystack_preview_logo" name="paystack_preview_logo" value="{{get_static_option('paystack_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($paystack_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paystack_public_key">{{__('PayStack Public Key')}}</label>
                                                            <input type="text" name="paystack_public_key" id="paystack_public_key" value="{{get_static_option('paystack_public_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paystack_secret_key">{{__('PayStack Secret Key')}}</label>
                                                            <input type="text" name="paystack_secret_key" id="paystack_secret_key"  value="{{get_static_option('paystack_secret_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paystack_merchant_email">{{__('PayStack Merchant Email')}}</label>
                                                            <input type="text" name="paystack_merchant_email" id="paystack_merchant_email"  value="{{get_static_option('paystack_merchant_email')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="mollie_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#mollie_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('Mollie Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="mollie_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Available Currency For Mollie is, ['AED','AUD','BGN','BRL','CAD','CHF','CZK','DKK','EUR','GBP','HKD','HRK','HUF','ILS','ISK','JPY','MXN','MYR','NOK','NZD','PHP','PLN','RON','RUB','SEK','SGD','THB','TWD','USD','ZAR']")}}</p>
                                                            <p>{{__('if your currency is not available in mollie, it will convert you currency value to USD value based on your currency exchange rate.')}}</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mollie_gateway"><strong>{{__('Enable/Disable Mollie')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="mollie_gateway"  @if(!empty(get_static_option('mollie_gateway'))) checked @endif id="mollie_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mollie_preview_logo"><strong>{{__('Mollie Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $mollie_img = get_attachment_image_by_id(get_static_option('mollie_preview_logo'),null,true);
                                                                        $mollie_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($mollie_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$mollie_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $mollie_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="mollie_preview_logo" name="mollie_preview_logo" value="{{get_static_option('mollie_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($mollie_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mollie_public_key">{{__('Mollie Public Key')}}</label>
                                                            <input type="text" name="mollie_public_key" id="mollie_public_key" value="{{get_static_option('mollie_public_key')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="flluterwave_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#flutterwave_settings_content" aria-expanded="false" >
                                                            <span class="page-title"> {{__('Flutterwave Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="flutterwave_settings_content" class="collapse"  data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{__("Available Currency For Flutterwave is, ['BIF','CAD','CDF','CVE','EUR','GBP','GHS','GMD','GNF','KES','LRD','MWK','MZN','NGN','RWF','SLL','STD','TZS','UGX','USD','XAF','XOF','ZMK','ZMW','ZWD']")}}</p>
                                                            <p>{{__('if your currency is not available in flutterwave, it will convert you currency value to USD value based on your currency exchange rate.')}}</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="flutterwave_gateway"><strong>{{__('Enable/Disable Flutterwave')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="flutterwave_gateway"  @if(!empty(get_static_option('flutterwave_gateway'))) checked @endif id="flutterwave_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="flutterwave_test_mode"><strong>{{__('Enable Test Mode Flutterwave')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="flutterwave_test_mode" @if(!empty(get_static_option('flutterwave_test_mode'))) checked @endif>
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="flutterwave_preview_logo"><strong>{{__('Flutterwave Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $mollie_img = get_attachment_image_by_id(get_static_option('flutterwave_preview_logo'),null,true);
                                                                        $mollie_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($mollie_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb" src="{{$mollie_img['img_url']}}" alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $mollie_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="flutterwave_preview_logo" name="flutterwave_preview_logo" value="{{get_static_option('flutterwave_preview_logo')}}">
                                                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                                                    {{__($mollie_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="flutterwave_public_key">{{__('Flutterwave Public Key')}}</label>
                                                            <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" value="{{get_static_option('flutterwave_public_key')}}" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="flutterwave_secret_key">{{__('Flutterwave Secret Key')}}</label>
                                                            <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" value="{{get_static_option('flutterwave_secret_key')}}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="manual_payment_settings">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#manual_payment_settings_content"
                                                                aria-expanded="false">
                                                            <span class="page-title"> {{__('Manual Payment Settings')}}</span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="manual_payment_settings_content" class="collapse"
                                                     data-parent="#accordion-payment">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="manual_payment_gateway"><strong>{{__('Enable/Disable Manual Payment')}}</strong></label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="manual_payment_gateway"
                                                                       @if(!empty(get_static_option('manual_payment_gateway'))) checked
                                                                       @endif id="manual_payment_gateway">
                                                                <span class="slider onff"></span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_logo"><strong>{{__('Manual Payment Logo')}}</strong></label>
                                                            <div class="media-upload-btn-wrapper">
                                                                <div class="img-wrap">
                                                                    @php
                                                                        $paytm_img = get_attachment_image_by_id(get_static_option('manual_payment_preview_logo'),null,false);
                                                                        $paytm_image_btn_label = __('Upload Image');
                                                                    @endphp
                                                                    @if (!empty($paytm_img))
                                                                        <div class="attachment-preview">
                                                                            <div class="thumbnail">
                                                                                <div class="centered">
                                                                                    <img class="avatar user-thumb"
                                                                                         src="{{$paytm_img['img_url']}}"
                                                                                         alt="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php  $paytm_image_btn_label = __('Change Image'); @endphp
                                                                    @endif
                                                                </div>
                                                                <input type="hidden" id="manual_payment_preview_logo"
                                                                       name="manual_payment_preview_logo"
                                                                       value="{{get_static_option('manual_payment_preview_logo')}}">
                                                                <button type="button"
                                                                        class="btn btn-info media_upload_form_btn"
                                                                        data-btntitle="{{__('Select Image')}}"
                                                                        data-modaltitle="{{__('Upload Image')}}"
                                                                        data-toggle="modal"
                                                                        data-target="#media_upload_modal">
                                                                    {{__($paytm_image_btn_label)}}
                                                                </button>
                                                            </div>
                                                            <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 160x50')}}</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_manual_payment_name">{{__('Manual Payment Name')}}</label>
                                                            <input type="text" name="site_manual_payment_name"
                                                                   id="site_manual_payment_name"
                                                                   value="{{get_static_option('site_manual_payment_name')}}"
                                                                   class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="site_manual_payment_description">{{__('Manual Payment Description')}}</label>
                                                            <input type="hidden" name="site_manual_payment_description" value="{{get_static_option('site_manual_payment_description')}}">
                                                            <div class="summernote" data-content='{{get_static_option('site_manual_payment_description')}}'></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    @include('backend.partials.media-upload.media-js')
    <script>
        (function($){
            "use strict";
            $(document).ready(function ($) {
                $('.summernote').summernote({
                    height: 200,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if($('.summernote').length > 0){
                    $('.summernote').each(function(index,value){
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });
        })(jQuery);


    </script>
@endsection
