@extends('backend.admin-master')
@section('site-title')
    {{__('View Vote Result')}}
@endsection

@section('style')
<x-datatable.css/>

    <style>
        .vote_progress_content .progress{
            position: relative;
            height: 20px;
        }
        .vote_progress_content .progress .progress-bar{
            padding: 0 7px;
        }
        .progress-percentage{
            position: absolute;
            right: 5px;
            color: #000;
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                      <div class="middle-part">
                          <a href="{{ route('admin.polls')}}" class="btn btn-primary mb-3 pull-right">{{__('All Polls')}}</a>
                          <h4 class="text-center">{{__('Vote Summary')}}</h4>
                          <h6 class="text-center mt-2 text-primary">{{__('Total Vote : ' .$poll_info->count())}}</h6>

                      </div>

                        @foreach ($vote_cart as $name => $count)
                        @php
                            $avg = $count / $poll_info->count() * 100 ?? 0 ;
                            $colors2 = ['#FEA47F','#BDC581','#EAB543','#55E6C1','#B33771'];
                        @endphp
                         <div class="vote_progress_content">
                            <div class="progress mt-4">
                                <div class="progress-bar" role="progressbar" style="width: {{$avg}}  % ; background-color: {{$colors2[$count % count($colors2)]}} " aria-valuenow="{{$avg}}"
                                     aria-valuemin="0" aria-valuemax="100"><strong>{{$name}} ({{$count}}) <span class="progress-percentage">{{ceil($avg ).'%'}}</span> </strong></div>
                            </div>
                        </div>

                        @endforeach

                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Vote Details')}}  </h4>
                            </div>

                            <div class="right-content">
                                <a href="{{ route('admin.polls')}}" class="btn btn-primary mb-3">{{__('All Polls')}}</a>
                            </div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Voted On')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Date')}}</th>

                                </thead>
                                <tbody>
                                    @foreach($poll->poll_infos as $data)
                                        <tr>
                                            <td>{{$data->id}}</td>
                                            <td>{{$data->vote_name}}</td>
                                            <td>{{$data->name}}</td>
                                            <td>{{$data->email}}</td>
                                            <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
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
