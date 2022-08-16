@extends('backend.admin-master')
@section('site-title')
    {{__('Testimonial Item')}}
@endsection
@section('style')
    <x-media.css/>
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.error/>
                <x-msg.success/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('All Categories')}}  </h4>
                                @can('blog-category-delete')
                                    <x-bulk-action/>
                                @endcan
                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">
                                    <form action="{{route('admin.testimonial')}}" method="get" id="langauge_change_select_get_form">
                                        <x-lang.select :name="'lang'" :selected="$default_lang" :id="'langchange'"/>
                                    </form>
                                </div>
                                <div class="btn-wrapper-inner ml-2">
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#new_testimonial">{{__('Add New')}}</button>
                                </div>
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
                                <th>{{__('Image')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_testimonials as $data)
                                    @php $img_url =''; @endphp
                                    <tr>
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>
                                            @php
                                                $testimonial_img = get_attachment_image_by_id($data->image,null,true);
                                            @endphp
                                            @if (!empty($testimonial_img))
                                                <div class="attachment-preview">
                                                    <div class="thumbnail">
                                                        <div class="centered">
                                                            <img class="avatar user-thumb"
                                                                 src="{{$testimonial_img['img_url']}}" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                @php  $img_url = $testimonial_img['img_url']; @endphp
                                            @endif
                                        </td>
                                        <td>{{$data->getTranslation('name',$default_lang)}}</td>
                                        <td>{{$data->getTranslation('designation',$default_lang)}}</td>
                                        <td>
                                            <x-status-span :status="$data->status"/>
                                        </td>
                                        <td>
                                            @can('testimonial-delete')
                                                <x-delete-popover :url="route('admin.testimonial.delete',$data->id)"/>
                                            @endcan
                                            @can('testimonial-edit')
                                                <a href="#"
                                                   data-toggle="modal"
                                                   data-target="#testimonial_item_edit_modal"
                                                   class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-action="{{route('admin.testimonial.update')}}"
                                                   data-name="{{$data->getTranslation('name',$default_lang)}}"
                                                   data-status="{{$data->status}}"
                                                   data-description="{{$data->getTranslation('description',$default_lang)}}"
                                                   data-designation="{{$data->getTranslation('designation',$default_lang)}}"
                                                   data-imageid="{{$data->image}}"
                                                   data-image="{{$img_url}}"
                                                >
                                                    <i class="ti-pencil"></i>
                                                </a>
                                                <x-clone-icon :action="route('admin.testimonial.clone')"
                                                              :id="$data->id"/>
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
            @can('testimonial-create')
                <div class="modal fade" id="new_testimonial" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('New Testimonial Item')}}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                            </div>
                            <form action="{{route('admin.testimonial')}}" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="lang" value="{{$default_lang}}">
                                    <div class="form-group">
                                        <label for="edit_name">{{__('Name')}}</label>
                                        <input type="text" class="form-control" name="name"
                                               placeholder="{{__('Name')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_designation">{{__('Designation')}}</label>
                                        <input type="text" class="form-control" name="designation"
                                               placeholder="{{__('Designation')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_description">{{__('Description')}}</label>
                                        <textarea class="form-control" name="description"
                                                  placeholder="{{__('Description')}}" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_status">{{__('Status')}}</label>
                                        <select name="status" class="form-control">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">{{__('Image')}}</label>
                                        <div class="media-upload-btn-wrapper">
                                            <div class="img-wrap"></div>
                                            <input type="hidden" name="image" value="">
                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                    data-btntitle="{{__('Select Image')}}"
                                                    data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                    data-target="#media_upload_modal">
                                                {{__('Upload Image')}}
                                            </button>
                                        </div>
                                        <small>{{__('360x360 px image recommended')}}</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{__('Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
            @can('testimonial-edit')
                <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('Edit Testimonial Item')}}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                            </div>
                            <form action="#" id="testimonial_edit_modal_form" method="post"
                                  enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="lang" value="{{$default_lang}}">
                                    <input type="hidden" name="id" id="testimonial_id" value="">
                                    <div class="form-group">
                                        <label for="edit_name">{{__('Name')}}</label>
                                        <input type="text" class="form-control" id="edit_name" name="name"
                                               placeholder="{{__('Name')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_designation">{{__('Designation')}}</label>
                                        <input type="text" class="form-control" id="edit_designation" name="designation"
                                               placeholder="{{__('Designation')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_description">{{__('Description')}}</label>
                                        <textarea class="form-control" id="edit_description" name="description"
                                                  placeholder="{{__('Description')}}" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_status">{{__('Status')}}</label>
                                        <select name="status" class="form-control" id="edit_status">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">{{__('Image')}}</label>
                                        <div class="media-upload-btn-wrapper">
                                            <div class="img-wrap"></div>
                                            <input type="hidden" id="edit_image" name="image" value="">
                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                    data-btntitle="{{__('Select Image')}}"
                                                    data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                    data-target="#media_upload_modal">
                                                {{__('Upload Image')}}
                                            </button>
                                        </div>
                                        <small>{{__('360x360 px image recommended')}}</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{__('Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
            <x-media.markup/>
            @endsection
            @section('script')

        <script>
            (function ($) {
                "use strict";

                $(document).ready(function () {
                    <x-bulk-action-js :url="route('admin.testimonial.bulk.action')" />
                    <x-btn.submit/>
                    <x-btn.update/>

                        $(document).on('change','#langchange',function(e){
                            $('#langauge_change_select_get_form').trigger('submit');
                        });

                    $(document).on('click', '.testimonial_edit_btn', function () {
                        var el = $(this);
                        var id = el.data('id');
                        var name = el.data('name');
                        var designation = el.data('designation');
                        var action = el.data('action');
                        var description = el.data('description');
                        var image = el.data('image');
                        var imageid = el.data('imageid');

                        var form = $('#testimonial_edit_modal_form');
                        form.attr('action', action);
                        form.find('#testimonial_id').val(id);
                        form.find('#edit_name').val(name);
                        form.find('#edit_description').val(description);
                        form.find('#edit_designation').val(designation);
                        form.find('#edit_languages option[value="' + el.data('lang') + '"]').attr('selected', true);
                        form.find('#edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
                        if (imageid != '') {
                            form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                            form.find('.media-upload-btn-wrapper input').val(imageid);
                            form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                        }
                    });
                });
            })(jQuery)
        </script>

        <x-datatable.js/>
        <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
        <x-media.js/>
@endsection
