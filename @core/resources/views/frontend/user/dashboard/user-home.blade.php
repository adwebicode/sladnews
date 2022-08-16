@extends('frontend.user.dashboard.user-master')
@section('page-title')
    <li class="list-item"><a href="#">{{__('User Dashboard')}}</a></li>
@endsection

@section('site-title')
    {{__('User Dashboard')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/frontend/css/custom-dashboard.css')}}">
@endsection

@section('section')

    <div class="row">
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">

                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles">#{{ auth()->guard('web')->user()->id }} </h2>
                        <span class="order-para"> {{__('User ID')}} </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">

                <div class="orders-flex-content">

                    <div class="contents">
                        <h2 class="order-titles"> {{$total_post}} </h2>
                        <span class="order-para">{{__('Total Created Post')}} </span>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-xl-12 col-md-12 orders-child mt-5">
             <div class="dashboard-form-wrapper">
                 <div class="row">
                     <div class="col-lg-12">
                         <div class="card">
                             <div class="card-body">
                                 <h4 class="pb-3">{{__('Recent Posts')}}</h4>


                                 <div class="table-wrap table-responsive">
                                     <table class="table table-default" id="all_blog_table">
                                         <thead>
                                         <th>{{__('ID')}}</th>
                                         <th>{{__('Title')}}</th>
                                         <th>{{__('View')}}</th>
                                         <th>{{__('Status')}}</th>
                                         <th>{{__('Action')}}</th>
                                         </thead>
                                         <tbody>
                                         @foreach($all_auth_user_data as $data)
                                             <tr>
                                                 <td>{{$data->id}}</td>
                                                 <td>{{$data->getTranslation('title',$user_select_lang_slug) ?? ''}}</td>
                                                 <td>{{$data->views ?? ''}}</td>

                                                 <td>
                                                     <div class="mt-2">
                                                         <x-status-span :status="$data->status"/>
                                                     </div>
                                                 </td>

                                                 <td>
                                                     <a class="btn btn-primary btn-xs mb-3 mr-1" href="{{route('user.blog.edit',$data->id).'?lang='.$user_select_lang_slug}}">
                                                         <i class="las la-edit"></i>
                                                     </a>

                                                     <a class="btn btn-info btn-xs mb-3 mr-1" target="_blank" href="{{route('frontend.blog.single',$data->slug)}}">
                                                         <i class="las la-eye"></i>
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
             </div>
         </div>
    </div>
@endsection





