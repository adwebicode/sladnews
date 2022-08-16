@extends('backend.admin-master')
@section('style')
    <x-summernote.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <x-datatable.css/>
    </style>
@endsection
@section('site-title')
    {{__('All Newsletter')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('All Newsletter Subscriber')}}</h4>
                        @can('newsletter-delete')
                        <div class="bulk-delete-wrapper">
                            <div class="select-box-wrap">
                                <select name="bulk_option" id="bulk_option">
                                    <option value="">{{{__('Bulk Action')}}}</option>
                                    <option value="delete">{{{__('Delete')}}}</option>
                                </select>
                                <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                            </div>
                        </div>
                        @endcan

                        <div class="table-wrap">
                            <table class="table table-default">
                                <thead>
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_subscriber as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->email}} @if($data->verified > 0) <i class="fas fa-check-circle"></i>@endif</td>
                                        <td>
                                            @can('newsletter-delete')
                                            <x-delete-popover :url="route('admin.newsletter.delete',$data->id)"/>
                                            @endcan
                                            @can('newsletter-send-mail')
                                            <a class="btn btn-lg btn-primary btn-sm mb-3 mr-1 send_mail_modal_btn"
                                               href="#"
                                               data-toggle="modal"
                                               data-target="#send_mail_to_subscriber_modal"
                                               data-email="{{$data->email}}"
                                            >
                                                <i class="ti-email"></i>
                                            </a>
                                            @if($data->verified <1)
                                                <form action="{{route('admin.newsletter.verify.mail.send')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$data->id}}">
                                                    <button class="btn btn-secondary" type="submit">{{__('Send Verify Mail')}}</button>
                                                </form>
                                            @endif
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
            @can('newsletter-create')
            <div class="col-lg-5 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Add New Subscriber')}}</h4>
                        <form action="{{route('admin.newsletter.new.add')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                            </div>
                            <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('newsletter-send-mail')
    <div class="modal fade" id="send_mail_to_subscriber_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.newsletter.single.mail')}}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="text" readonly class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_icon">{{__('Subject')}}</label>
                            <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                        </div>
                        <div class="form-group">
                            <label for="message">{{__('Message')}}</label>
                            <input type="hidden" name="message" >
                            <div class="summernote"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Send Mail')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
   <x-media.markup/>
@endsection

@section('script')
    <x-datatable.js/>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>

    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-btn.submit/>
                <x-bulk-action-js :url="route('admin.newsletter.bulk.action')" />

                $(document).on('click','.send_mail_modal_btn',function(){
                    var el = $(this);
                    var email = el.data('email');
                    var form = $('#send_mail_to_subscriber_edit_modal_form');
                    form.find('#email').val(email);
                });
                $('.summernote').summernote({
                    height: 300,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
            });

        })(jQuery)
    </script>

@endsection
