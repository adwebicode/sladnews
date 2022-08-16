@extends('backend.admin-master')
@section('site-title')
    {{__('All Pages')}}
@endsection

@section('style')
<x-datatable.css/>
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
                                <h4 class="header-title">{{__('All Polls')}}  </h4>
                                @can('poll-list')
                                  <x-bulk-action/>
                                @endcan
                            </div>
                            @can('poll-create')
                            <div class="right-content">
                                <a href="{{ route('admin.polls.new')}}" class="btn btn-primary">{{__('Add New Poll')}}</a>
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
                                <th>{{__('Question')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_polls as $data)
                                        <tr>
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id"/>
                                            </td>
                                            <td>{{$data->id}}</td>
                                            <td>{{$data->question}}</td>
                                            <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
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
                                                @can('poll-delete')
                                                  <x-delete-popover :url="route('admin.poll.delete',$data->id)"/>
                                                @endcan
                                                 @can('poll-edit')
                                                  <x-edit-icon :url="route('admin.poll.edit',$data->id)"/>
                                                  @endcan
                                                  <a href="{{route('admin.poll.result',$data->id)}}" class="btn btn-dark btn-sm mb-3" data-toggle="tooltip" title="View Result"> <i class="fa fa-eye"></i> </a>
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
@endsection

@section('script')
 <x-datatable.js/>
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){
                <x-bulk-action-js :url="route('admin.poll.bulk.action')"/>
              });
        })(jQuery);
    </script>
@endsection
