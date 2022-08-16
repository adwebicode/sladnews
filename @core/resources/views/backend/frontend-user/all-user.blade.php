@extends('backend.admin-master')
@section('style')
    <x-media.css/>
    <x-datatable.css/>
@endsection

@section('site-title')
    {{__('All Users')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mt-5">
                            <div class="card">
                                <div class="card-body">
                                  <x-msg.success/>
                                  <x-msg.error/>
                                    <div class="d-flex justify-content-between">
                                        <div class="left-content">
                                            <h4 class="header-title">{{__('All Users')}}</h4>
                                        </div>
                                        <div class="right-content">
                                            <a href="{{route('admin.frontend.new.user')}}" class="btn btn-info"> {{__('Add New')}}</a>
                                        </div>
                                    </div>
                                    @can('user-delete')
                                       <x-bulk-action/>
                                    @endcan
                                    <div class="data-tables datatable-primary table-wrap">
                                        <table class="text-center">
                                            <thead class="text-capitalize">
                                            <tr>
                                                <th class="no-sort">
                                                    <div class="mark-all-checkbox">
                                                        <input type="checkbox" class="all-checkbox">
                                                    </div>
                                                </th>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Email')}}</th>
                                                <th>{{__('Designation')}}</th>
                                                <th>{{__('Username')}}</th>
                                                <th>{{__('Image')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($all_user as $data)
                                                <tr>
                                                    <td><x-bulk-delete-checkbox :id="$data->id"/></td>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{$data->name}}</td>
                                                    <td>{{$data->email}} @if($data->email_verified == 1) <i class="fas fa-check-circle text-success"></i> @endif</td>
                                                    <td>{{$data->designation}}</td>
                                                    <td>{{$data->username}}</td>
                                                    <td>
                                                        @php
                                                            $brand_img = get_attachment_image_by_id($data->image,null,true);
                                                        @endphp
                                                        @if (!empty($brand_img))
                                                            <div class="attachment-preview">
                                                                <div class="thumbnail">
                                                                    <div class="centered">
                                                                        <img class="avatar user-thumb" src="{{$brand_img['img_url']}}" alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @php  $img_url = $brand_img['img_url']; @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @can('user-delete')
                                                          <x-delete-popover :url="route('admin.frontend.delete.user',$data->id)"/>
                                                        @endcan

                                                      @can('user-edit')
                                                        <a href="#"
                                                           data-id="{{$data->id}}"
                                                           data-username="{{$data->username}}"
                                                           data-imageid="{{$data->image}}"
                                                           data-image="{{$img_url}}"
                                                           data-name="{{$data->name}}"
                                                           data-email="{{$data->email}}"
                                                           data-phone="{{$data->phone}}"
                                                           data-address="{{$data->address}}"
                                                           data-state="{{$data->state}}"
                                                           data-city="{{$data->city}}"
                                                           data-zipcode="{{$data->zipcode}}"
                                                           data-country="{{$data->country}}"
                                                           data-designation="{{$data->designation}}"
                                                           data-description="{{$data->description}}"
                                                           data-facebook_url="{{$data->facebook_url}}"
                                                           data-twitter_url="{{$data->twitter_url}}"
                                                           data-instagram_url="{{$data->instagram_url}}"
                                                           data-linkedin_url="{{$data->linkedin_url}}"
                                                           data-email_verified="{{$data->email_verified}}"
                                                           data-toggle="modal"
                                                           data-target="#user_edit_modal"
                                                           class="btn btn-primary btn-sm mb-3 mr-1 user_edit_btn"
                                                        >
                                                            <i class="ti-pencil"></i>
                                                        </a>

                                                            <a class="btn btn-success btn-sm mb-3 text-white user_permission_btn"
                                                               data-toggle="modal"
                                                               data-target="#user_permission_modal"
                                                               data-id="{{$data->id}}"
                                                               data-auto_post_approval="{{$data->auto_post_approval}}"
                                                               data-is_banned="{{$data->is_banned}}"
                                                               data-post_permission="{{$data->post_permission}}"
                                                           >
                                                                {{__('Permissions')}}
                                                            </a>

                                                        <a href="#"
                                                           data-id="{{$data->id}}"
                                                           data-toggle="modal"
                                                           data-target="#user_change_password_modal"
                                                           class="btn btn-info btn-sm mb-3 mr-1 user_change_password_btn"
                                                        >
                                                            {{__("Change Password")}}
                                                        </a>
                                                        <form action="{{route('admin.all.frontend.user.email.status')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" value="{{$data->id}}" name="user_id">
                                                            <input type="hidden" value="{{$data->email_verified}}" name="email_verified">
                                                            <button type="submit" class="btn btn-sm @if($data->email_verified == 1)  btn-dark @else btn-warning @endif">
                                                                @if($data->email_verified == 1) {{__('Enable Email Verify')}} @else {{__('Disable Email Verify')}} @endif
                                                            </button>
                                                        </form>
                                                         @endcan

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Primary table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('User Details Edit')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.frontend.user.update')}}" id="user_edit_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Enter name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="phone">{{__('Phone')}}</label>
                            <input type="text" class="form-control"  id="phone" name="phone" placeholder="{{__('Phone')}}">
                        </div>
                        <div class="form-group">
                            <label for="country">{{__('Country')}}</label>
                            {!! get_country_field('country','country','form-control') !!}
                        </div>
                        <div class="form-group">
                            <label for="state">{{__('State')}}</label>
                            <input type="text" class="form-control"  id="state" name="state" placeholder="{{__('State')}}">
                        </div>
                        <div class="form-group">
                            <label for="city">{{__('City')}}</label>
                            <input type="text" class="form-control"  id="city" name="city" placeholder="{{__('City')}}">
                        </div>
                        <div class="form-group">
                            <label for="zipcode">{{__('Zipcode')}}</label>
                            <input type="text" class="form-control"  id="zipcode" name="zipcode" placeholder="{{__('Zipcode')}}">
                        </div>
                        <div class="form-group">
                            <label for="address">{{__('Address')}}</label>
                            <input type="text" class="form-control"  id="address" name="address" placeholder="{{__('Address')}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">{{__('Designation')}}</label>
                            <input type="text" class="form-control"  id="designation" name="designation" placeholder="{{__('Designation')}}">
                        </div>

                        <div class="form-group">
                            <label for="email">{{__('Facebook URL')}}</label>
                            <input type="text" class="form-control"  id="facebook_url" name="facebook_url" >
                        </div>

                        <div class="form-group">
                            <label for="email">{{__('Twitter URL')}}</label>
                            <input type="text" class="form-control"  id="twitter_url" name="twitter_url" >
                        </div>

                        <div class="form-group">
                            <label for="email">{{__('Instagram URL')}}</label>
                            <input type="text" class="form-control"  id="instagram_url" name="instagram_url">
                        </div>

                        <div class="form-group">
                            <label for="email">{{__('Linkedin URL')}}</label>
                            <input type="text" class="form-control"  id="linkedin_url" name="linkedin_url" >
                        </div>

                        <div class="form-group">
                            <label for="phone">{{__('Description')}}</label>
                            <textarea class="form-control" name="description" id="description" cols="5"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">{{__('Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" id="edit_image" name="image" value="">
                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Team Image')}}" data-modaltitle="{{__('Upload Team Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                            <small>{{__('Recommended image size 270x280')}}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_change_password_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Change Admin Password')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.frontend.user.password.change')}}" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password" placeholder="{{__('Enter Password')}}">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">{{__('Confirm Password')}}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Change Password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="user_permission_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{__('User Permission Settings')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>

                <form action="{{route('admin.frontend.user.permission.settings')}}" method="post" enctype="multipart/form-data" id="user_permission_form">
                    @csrf
                    <input type="hidden" id="user_permission_id" name="id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Auto Approval')}}</label>
                                        <label class="switch">
                                            <input type="checkbox" name="auto_post_approval" class="auto_post_approval">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Banned User')}}</label>
                                        <label class="switch">
                                            <input type="checkbox" name="is_banned" class="is_banned">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Post Permission')}}</label>
                                        <label class="switch">
                                            <input type="checkbox" name="post_permission" class="post_permission">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<x-media.markup/>
@endsection

@section('script')

    <x-datatable.js/>

    <script>
        (function($){
        "use strict";
        $(document).ready(function() {
            $(document).on('click','.user_change_password_btn',function(e){
                e.preventDefault();
                var el = $(this);
                var form = $('#user_password_change_modal_form');
                form.find('#ch_user_id').val(el.data('id'));
            });

            $(document).on('click','.user_permission_btn',function(e){
               e.preventDefault();
                var form = $('#user_permission_form');
                var el = $(this);
                var user_id = el.data('id');

                var auto_post_approval = el.data('auto_post_approval');
                var is_banned = el.data('is_banned');
                var post_permission = el.data('post_permission');

                $('#user_permission_id').val(user_id);

                if(auto_post_approval === 1){
                    $('.auto_post_approval').prop('checked',true);
                }else{
                    $('.auto_post_approval').prop('checked',false);
                }

                if(is_banned === 1){
                    $('.is_banned').prop('checked',true);
                }else{
                    $('.is_banned').prop('checked',false);
                }

                if(post_permission === 1){
                    $('.post_permission').prop('checked',true);
                }else{
                    $('.post_permission').prop('checked',false);
                }

            });

            $(document).on('click','.user_edit_btn',function(e){
                e.preventDefault();
                var form = $('#user_edit_modal_form');
                var el = $(this);
                form.find('#user_id').val(el.data('id'));
                form.find('#name').val(el.data('name'));
                form.find('#username').val(el.data('username'));
                form.find('#email').val(el.data('email'));
                form.find('#phone').val(el.data('phone'));
                form.find('#state').val(el.data('state'));
                form.find('#city').val(el.data('city'));
                form.find('#zipcode').val(el.data('zipcode'));
                form.find('#address').val(el.data('address'));
                form.find('#designation').val(el.data('designation'));
                form.find('#description').val(el.data('description'));

                var image = el.data('image');
                var imageid = el.data('imageid');

                form.find('#facebook_url').val(el.data('facebook_url'));
                form.find('#twitter_url').val(el.data('twitter_url'));
                form.find('#instagram_url').val(el.data('instagram_url'));
                form.find('#linkedin_url').val(el.data('linkedin_url'));
                form.find('#country option[value="'+el.data('country')+'"]').attr('selected',true);

                if(imageid != ''){
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="'+image+'" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(imageid);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });
            $(document).on('click','#bulk_delete_btn',function (e) {
                e.preventDefault();
                var bulkOption = $('#bulk_option').val();
                var allCheckbox =  $('.bulk-checkbox:checked');
                var allIds = [];
                allCheckbox.each(function(index,value){
                    allIds.push($(this).val());
                });
                if(allIds != '' && bulkOption == 'delete'){
                    $(this).text('{{__('Deleting...')}}');
                    $.ajax({
                        'type' : "POST",
                        'url' : "{{route('admin.all.frontend.user.bulk.action')}}",
                        'data' : {
                            _token: "{{csrf_token()}}",
                            ids: allIds
                        },
                        success:function (data) {
                            location.reload();
                        }
                    });
                }

            });
            $('.all-checkbox').on('change',function (e) {
                e.preventDefault();
                var value = $('.all-checkbox').is(':checked');
                var allChek = $(this).parent().parent().parent().parent().parent().find('.bulk-checkbox');
                //have write code here fr
                if( value == true){
                    allChek.prop('checked',true);
                }else{
                    allChek.prop('checked',false);
                }
            });
        });
        <x-btn.update/>
    })(jQuery);
        
    </script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <x-media.js/>
@endsection
