@extends('backend.admin-master')
@section('site-title')
    {{__('Partner ')}}
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
                @include('backend/partials/message')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('All Partner Items')}}</h4>
                        @can('partner-delete')
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
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                            <thead>
                            <th class="no-sort">
                                <div class="mark-all-checkbox">
                                    <input type="checkbox" class="all-checkbox">
                                </div>
                            </th>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Url')}}</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Action')}}</th>
                            </thead>
                            <tbody>
                            @foreach($all_partners as $data)
                                <tr>
                                    <td>
                                        <div class="bulk-checkbox-wrapper">
                                            <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                        </div>
                                    </td>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->url}}</td>
                                    <td>
                                        @php
                                            $brand_img = get_attachment_image_by_id($data->image,null,true);
                                            $img_url = '';
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
                                        @can('partner-delete')
                                        <x-delete-popover :url="route('admin.partner.delete',$data->id)"/>
                                        @endcan

                                       @can('partner-edit')
                                        <a href="#"
                                           data-toggle="modal"
                                           data-target="#brand_item_edit_modal"
                                           class="btn btn-lg btn-primary btn-sm mb-3 mr-1 brand_edit_btn"
                                           data-id="{{$data->id}}"
                                           data-url="{{$data->url}}"
                                           data-imageid="{{$data->image}}"
                                           data-image="{{$img_url}}"
                                        >
                                            <i class="ti-pencil"></i>
                                        </a>
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

            @can('partner-create')
            <div class="col-lg-5 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Add New Client')}}</h4>
                        <form action="{{route('admin.partner')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="url">{{__('URl')}}</label>
                                <input type="text" class="form-control"  id="url"  name="url" placeholder="{{__('Url')}}">
                            </div>
                            <div class="form-group">
                                <label for="image">{{__('Image')}}</label>
                                <div class="media-upload-btn-wrapper">
                                    <div class="img-wrap"></div>
                                    <input type="hidden" name="image">
                                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="Select Brand Image" data-modaltitle="Upload Brand Image" data-toggle="modal" data-target="#media_upload_modal">
                                        {{__('Upload Image')}}
                                    </button>
                                </div>
                                <small>{{__('Recommended image size 160x80')}}</small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </form>
                    </div>
                </div>
            </div>
          @endcan
        </div>
    </div>

    @can('partner-edit')
    <div class="modal fade" id="brand_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Client Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.partner.update')}}" id="brand_edit_modal_form"  method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" class="form-control" name="id"  id="partner_id" >
                        <div class="form-group">
                            <label for="edit_url">{{__('URl')}}</label>
                            <input type="text" class="form-control"  id="edit_url"  name="url" placeholder="{{__('Url')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_image">{{__('Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" id="edit_image" name="image" value="">
                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="Select Brand Image" data-modaltitle="Upload Brand Image" data-toggle="modal" data-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                            <small>{{__('Recommended image size 160x80')}}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
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
        $(document).ready(function () {
        <x-bulk-action-js :url="route('admin.partner.bulk.action')"/>

            $(document).on('click','.brand_edit_btn',function(){
                var el = $(this);
                var id = el.data('id');
                var form = $('#brand_edit_modal_form');
                var image = el.data('image');
                var imageid = el.data('imageid');

                form.find('#partner_id').val(id);
                form.find('#edit_url').val(el.data('url'));

                if(imageid != ''){
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="'+image+'" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(imageid);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });
        });
    </script>

    <x-datatable.js/>
    </script>
    <x-media.js/>
@endsection
