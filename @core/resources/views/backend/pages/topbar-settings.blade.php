@extends('backend.admin-master')
@section('style')
    @include('backend.partials.datatable.style-enqueue')
@endsection
@section('site-title')
    {{__('Top Bar Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-8 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp">
                            <h4 class="header-title">{{__('Support Info Items for Topbar')}}  </h4>

                            <div class="header-title">
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" data-toggle="modal"
                                        data-target="#add_support_info">{{__('Add New Support Info')}}</button>
                            </div>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>

                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('URL')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_topbar_infos as $data)
                                    <tr>

                                        <td>{{$data->id}}</td>
                                        <td>{{$data->title}}</td>
                                        <td>{{$data->url}}</td>
                                        <td>
                                            <x-delete-popover :url="route('admin.delete.support.info',$data->id)"/>
                                            <a href="#"
                                               data-toggle="modal"
                                               data-target="#support_info_item_edit_modal"
                                               class="btn btn-primary btn-xs mb-3 mr-1 support_info_edit_btn"
                                               data-id="{{$data->id}}"
                                               data-action="{{route('admin.update.support.info')}}"
                                               data-title="{{$data->title}}"
                                               data-infourl="{{$data->url}}"
                                            >
                                                <i class="ti-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Social Icons for Tob Bar Right / Left Bar Inner Bar')}}</h4>
                        <div class="right-cotnent margin-bottom-40">
                            <a class="btn btn-primary"
                               data-target="#add_social_icon"
                               data-toggle="modal"
                               href="#">{{__('Add New Social Item')}}</a>
                        </div>
                        <table class="table table-default">
                            <thead>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Icon')}}</th>
                            <th>{{__('URL')}}</th>
                            <th>{{__('Action')}}</th>
                            </thead>
                            <tbody>
                            @foreach($all_social_icons as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td><i class="{{$data->icon}}"></i></td>
                                    <td>{{$data->url}}</td>
                                    <td>
                                        <x-delete-popover :url="route('admin.delete.social.item',$data->id)"/>
                                        <a href="#"
                                           data-toggle="modal"
                                           data-target="#social_item_edit_modal"
                                           class="btn btn-primary btn-xs mb-3 mr-1 social_item_edit_btn"
                                           data-id="{{$data->id}}"
                                           data-url="{{$data->url}}"
                                           data-iconsocial="{{$data->icon}}">
                                            <i class="ti-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_support_info" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add New Support Info')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.new.support.info')}}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{__('Title')}}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="{{__('Title')}}">
                        </div>

                        <div class="form-group">
                            <label for="details">{{__('URL')}}</label>
                            <input type="text" class="form-control" id="url" name="url"
                              placeholder="{{__('URL')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="support_info_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Support Info')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.update.support.info')}}" id="support_info_item_edit_modal_form"
                      method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="support_info_id" name="id" value="">

                        <div class="form-group">
                            <label for="edit_title">{{__('Title')}}</label>
                            <input type="text" class="form-control" id="edit_title" name="title"
                                   placeholder="{{__('Title')}}">
                        </div>

                        <div class="form-group">
                            <label for="edit_details">{{__('URL')}}</label>
                            <input type="text" class="form-control edit_info_url" name="url"
                                   placeholder="{{__('URL')}}">
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

    <div class="modal fade" id="add_social_icon" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add Social Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.new.social.item')}}" method="post">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group">
                            <label for="social_item_icon" class="d-block">{{__('Add New Social Icon')}}</label>
                            <div class="btn-group ">
                                <button type="button" class="btn btn-primary iconpicker-component">
                                    <i class="fab fa-instagram"></i>
                                </button>
                                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="fab fa-instagram" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                                </button>
                                <div class="dropdown-menu"></div>
                            </div>
                            <input type="hidden" class="form-control" id="social_item_icon" value="fab fa-instagram"
                                   name="icon">
                        </div>
                        <div class="form-group">
                            <label for="social_item_link">{{__('URL')}}</label>
                            <input type="text" name="url" id="social_item_link" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Add Social Item')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="social_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Social Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.update.social.item')}}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="social_item_id" value="">
                        <div class="form-group">
                            <label for="edit_icon" class="d-block">{{__('Social Icons')}}</label>
                            <div class="btn-group edit_icon">
                                <button type="button" class="btn btn-primary iconpicker-component">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>
                                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="fas fa-exclamation-triangle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                                </button>
                                <div class="dropdown-menu"></div>
                            </div>
                            <input type="hidden" class="form-control" id="edit_social_icon" value="fas fa-exclamation-triangle"
                                   name="icon">
                        </div>
                        <div class="form-group">
                            <label for="social_item_edit_url">{{__('Url')}}</label>
                            <input type="text" class="form-control" id="social_item_edit_url" name="url"
                                   placeholder="{{__('Url')}}">
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
@endsection
@section('script')
    @include('backend.partials.datatable.script-enqueue')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-icon-picker/>
                <x-btn.submit/>
                <x-btn.update/>
                $(document).on('click', '.support_info_edit_btn', function () {
                    var el = $(this);
                    var id = el.data('id');
                    var title = el.data('title');
                    var url = el.data('infourl');
                    var form = $('#support_info_item_edit_modal_form');
                    form.find('#support_info_id').val(id);
                    form.find('#edit_title').val(title);
                    form.find('.edit_info_url').val(url);
                    ;
                });

                $(document).on('click', '.social_item_edit_btn', function () {
                    var el = $(this);
                    var id = el.data('id');
                    var url = el.data('url');
                    var social_icon = el.data('iconsocial');
                    var form = $('#social_item_edit_modal');
                    form.find('#social_item_id').val(id);
                    form.find('#edit_social_icon').val(social_icon);
                    form.find('#social_item_edit_url').val(url);
                    form.find('#edit_icon').val(el.data('iconsocial'));
                    form.find('.edit_icon .icp-dd').attr('data-selected', el.data('iconsocial'));
                    form.find('.edit_icon .iconpicker-component i').attr('class', el.data('iconsocial'));
                });
            });

        })(jQuery);
    </script>
@endsection
