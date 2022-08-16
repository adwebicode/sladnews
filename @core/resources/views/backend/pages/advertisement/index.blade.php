@extends('backend.admin-master')
@section('site-title')
    {{__('All Advertisements')}}
@endsection

@section('style')
<x-datatable.css/>
<x-media.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('All Advertisements')}}  </h4>
                                 @can('advertisement-delete')
                                  <x-bulk-action/>
                                 @endcan
                            </div>
                            @can('advertisement-create')
                            <div class="right-content">
                                <a href="{{ route('admin.advertisement.new')}}" class="btn btn-primary">{{__('Add New Advertisement')}}</a>
                            </div>
                             @endcan
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
                                <th>{{__('Type')}}</th>
                                <th>{{__('Size')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Click')}}</th>
                                <th>{{__('Impression')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_advertisements as $data)
                                        <tr>
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id"/>
                                            </td>
                                            <td>{{$data->id}}</td>
                                            <td>{{$data->title}}</td>
                                            <td>{{__(str_replace('_',' ',$data->type))}}</td>
                                            <td>{{$data->size}}</td>
                                            <td>
                                                @php
                                                    $add_img = get_attachment_image_by_id($data->image,null,true);
                                                @endphp
                                                @if (!empty($add_img))
                                                    <div class="attachment-preview">
                                                        <div class="thumbnail">
                                                            <div class="centered">
                                                                <img class="avatar user-thumb" src="{{$add_img['img_url']}}" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{$data->click}}</td>
                                            <td>{{$data->impression}}</td>
                                            <td>
                                                @php
                                                $type = 'warning';
                                                $name = __('Inactive');
                                                  if($data->status === 1){
                                                      $type = 'primary';
                                                      $name = __('Active');
                                                  }
                                                 @endphp
                                                    <span class="alert alert-{{$type}}">{{$name}}</span>
                                            </td>
                                            <td>
                                                @can('advertisement-delete')
                                                  <x-delete-popover :url="route('admin.advertisement.delete',$data->id)"/>
                                                @endcan
                                                  @can('advertisement-edit')
                                                  <x-edit-icon :url="route('admin.advertisement.edit',$data->id)"/>
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
        </div>
    </div>
<x-media.css/>
@endsection

@section('script')
 <x-datatable.js/>
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){
                <x-bulk-action-js :url="route('admin.advertisement.bulk.action')"/>
              });
        })(jQuery);
    </script>
 <x-media.js/>
@endsection
