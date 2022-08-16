@extends('backend.admin-master')

@section('site-title')
    {{__('Edit Poll')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Edit Poll')}}   </h4>
                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">
                                    <a href="{{ route('admin.polls') }}" class="btn btn-primary">{{__('All Polls')}}</a>
                                </div>
                            </div>
                        </div>
                        <form action="{{route('admin.poll.update',$poll->id)}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="tab-content margin-top-40">

                                <div class="form-group">
                                    <label for="title">{{__('Question')}}</label>
                                    <input type="text" class="form-control" name="question" value="{{$poll->question}}" id="edit_question">
                                </div>

                                @php
                                    $options = json_decode($poll->options, true);
                                @endphp

                                @foreach($options as $name)
                              <div class="main">
                                  <div class="form-group">
                                      <label for="title">{{__('Option')}}</label>
                                      <div class="new-single-input">
                                          <input type="text" class="form-control" name="options[]" id="edit_options" value="{{$name}}">
                                          <div class="delete_icon pull-right">
                                              <button title="Click to Delete" class="btn btn-danger btn-sm btn-circle new-btn-plus" id="remove_btn">{{__('X')}}</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                @endforeach

                                <div class="show_markup">

                                </div>

                                <div class="add_icon pull-right">
                                    <button title="Click to Add New" class="btn btn-success btn-sm btn-circle" id="plus_btn"> <i class="fa fa-plus"></i></button>
                                </div>

                                <div class="form-group mt-5 pt-5">
                                    <label for="title">{{__('Status')}}</label>
                                    <select class="form-control" name="status">
                                        <option value="0" @if($poll->status == '0') selected @endif>{{__('Inactive')}}</option>
                                        <option value="1" @if($poll->status == '1') selected @endif>{{__('Active')}}</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 submit_btn">{{__('Submit ')}}</button>
                            </div>
                        </form>
                </div>

            </div>
        </div>
    </div>
    </div>
    <x-media.markup/>
@endsection
@section('script')

    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                $(document).on('click','#plus_btn',function(e){
                    e.preventDefault();

                    let Markup = ' <div class="main">'+
                        ' <div class="form-group add-data">'+
                        '  <label for="title">'+"{{__('Option')}}"+'</label>'+
                        ' <div class="new-single-input">'+
                        ' <input type="text" class="form-control" name="options[]" id="edit_options" value="">'+
                        ' <div class="delete_icon pull-right">'+
                        ' <button title="Click to Delete" class="btn btn-danger btn-sm btn-circle new-btn-plus" id="remove_btn">'+"{{__('X')}}"+'</button>'+
                        '  </div>'+
                        ' </div>'+
                        ' </div>'+
                        ' </div>';

                    $('.show_markup').append(Markup);
                });

                $(document).on('click','#remove_btn',function(e){
                    e.preventDefault();
                    $(this).parent().parent().parent().remove();
                })

            });

        })(jQuery);
    </script>
@endsection

