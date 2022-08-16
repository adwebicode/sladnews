@extends('backend.admin-master')
@section('site-title')
    {{__('Faq')}}
@endsection
@section('style')
    <x-media.css/>
    <x-summernote.css/>
    <x-datatable.css/>
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.error/>
                <x-msg.success/>
            </div>

            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Faq Items')}}</h4>
                        @can('faq-delete')

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

                        <div class="header-title pull-right">
                            <div class="btn-wrapper-inner">
                                <form action="{{route('admin.faq')}}" method="get" id="langauge_change_select_get_form">
                                    <x-lang.select :name="'lang'" :selected="$default_lang" :id="'langchange'"/>
                                </form>
                            </div>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_faqs as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{$data->id}}</td>
                                          @php $open = $data->is_open == 'on' ?  "<span class='badge badge-primary'> Open </span>" : '';  @endphp
                                            <td>{{$data->getTranslation('title',$default_lang)}} {!! $open !!}  </td>

                                        <td>@if($data->status == 'publish') <span
                                                    class="alert alert-success">{{__('Publish')}}</span> @else <span
                                                    class="alert alert-warning">{{__('Draft')}}</span> @endif</td>
                                        <td>
                                            @can('faq-delete')
                                                <x-delete-popover :url="route('admin.faq.delete',$data->id)"/>
                                            @endcan
                                            @can('faq-edit')
                                                <a href="#"
                                                   data-toggle="modal"
                                                   data-target="#faq_item_edit_modal"
                                                   class="btn btn-primary btn-xs mb-3 mr-1 faq_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-title="{{$data->getTranslation('title',$default_lang)}}"
                                                   data-is_open="{{$data->is_open}}"
                                                   data-description="{{$data->getTranslation('description',$default_lang)}}"
                                                   data-status="{{$data->status}}"
                                                >
                                                    <i class="ti-pencil"></i>
                                                </a>
                                                <form action="{{route('admin.faq.clone')}}" method="post"
                                                      style="display: inline-block">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{$data->id}}">
                                                    <button type="submit" title="{{__('clone this to new draft')}}"
                                                            class="btn btn-xs btn-secondary btn-sm mb-3 mr-1"><i
                                                                class="far fa-copy"></i></button>
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

            @can('faq-create')
                <div class="col-lg-5 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{__('New Faq')}}</h4>
                            <form action="{{route('admin.faq')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="lang" value="{{$default_lang}}">
                                <div class="form-group">
                                    <label for="title">{{__('Title')}}</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           placeholder="{{__('Title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="is_open">{{__('Is Open')}}</label>
                                    <label class="switch">
                                        <input type="checkbox" name="is_open" id="is_open">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="description">{{__('Description')}}</label>
                                    <input type="hidden" name="description">
                                    <div class="summernote"></div>
                                </div>
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="publish">{{__('Publish')}}</option>
                                        <option value="draft">{{__('Draft')}}</option>
                                    </select>
                                </div>
                                <button id="submit" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New Faq')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('faq-edit')
        <div class="modal fade" id="faq_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Edit Faq Item')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('admin.faq.update')}}" id="faq_edit_modal_form" enctype="multipart/form-data"
                          method="post">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="faq_id" value="">
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="edit_title">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="edit_title" name="title"
                                       placeholder="{{__('Title')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_is_open">{{__('Is Open')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="is_open" id="edit_is_open">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="edit_description">{{__('Description')}}</label>
                                <input type="hidden" id="edit_description" name="description">
                                <div class="summernote"></div>
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="publish">{{__('Publish')}}</option>
                                    <option value="draft">{{__('Draft')}}</option>
                                </select>
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
    @endcan
    <x-media.markup/>
@endsection
@section('script')
    <x-summernote.js/>
    <script>
        (function($){
            $(document).ready(function () {
                <x-bulk-action-js :url="route('admin.faq.bulk.action')" />
                    <x-btn.submit/>
                    <x-btn.update/>

                    $(document).on('change','#langchange',function(e){
                        $('#langauge_change_select_get_form').trigger('submit');
                    });

                    $(document).on('click', '.faq_edit_btn', function () {
                        var el = $(this);
                        var id = el.data('id');
                        var title = el.data('title');
                        var form = $('#faq_edit_modal_form');
                        form.find('#faq_id').val(id);
                        form.find('#edit_title').val(title);
                        form.find('#edit_description').val(el.data('description'));
                        form.find('#edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
                        if (el.data('is_open') != '') {
                            form.find('#edit_is_open').attr('checked', true);
                        } else {
                            form.find('#edit_is_open').attr('checked', false);
                        }
                        form.find('.summernote').summernote('code', el.data('description'));
                    });

            });
        })(jQuery)
    </script>

    <x-datatable.js/>
    <x-media.js/>
@endsection
