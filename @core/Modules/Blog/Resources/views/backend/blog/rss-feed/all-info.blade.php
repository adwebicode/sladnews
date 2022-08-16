@extends('backend.admin-master')
@section('site-title')
    {{__('All Rss Feed Info')}}
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
                               <h4 class="header-title">{{__('All Rss Feed Info')}}  </h4>
                               <x-bulk-action/>
                           </div>
                           <div class="header-title d-flex">
                               <div class="btn-wrapper-inner ml-2">
                                   <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#rss_automation_modal">{{__('Add New Import')}}</button>
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
                                   <th>{{__('Link')}}</th>
                                   <th>{{__('Imported Item')}}</th>
                                   <th>{{__('Cronjob Type')}}</th>
                                   <th>{{__('Status')}}</th>
                                   <th>{{__('Action')}}</th>
                                   </thead>
                                   <tbody>
                                   @foreach($all_feed_info as $data)
                                       <tr>
                                         <td>
                                             <div class="bulk-checkbox-wrapper">
                                                 <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                             </div>
                                         </td>
                                           <td>{{$data->id}}</td>
                                           <td>{{$data->link ?? ''}}</td>
                                           <td>{{$data->imported_item ?? ''}}</td>
                                           <td>{{str_replace('_',' ',ucwords($data->automation_type)) }}</td>

                                           <td>
                                               @if($data->status == 'off')
                                                   <span class="alert alert-danger" >{{__('Off')}}</span>
                                               @else
                                                   <span class="alert alert-success" >{{__('On')}}</span>
                                               @endif
                                           </td>

                                           <td>
                                               <a href="#"
                                                  data-toggle="modal"
                                                  data-target="#rss_edit_modal"
                                                  class="btn btn-lg btn-primary btn-sm mb-3 mr-1 rss_edit_btn"
                                                  data-id="{{$data->id}}"
                                                  data-link="{{$data->link}}"
                                                  data-imported_item="{{$data->imported_item}}"
                                                  data-automation_type="{{$data->automation_type}}"
                                                  data-status="{{$data->status}}"
                                               >
                                                   <i class="ti-pencil"></i>
                                               </a>
                                               <x-delete-popover-all-lang :url="route('admin.blog.rss.feed.all.info.delete',$data->id)"/>
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

  <div class="modal fade" id="rss_automation_modal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">{{__('Import Rss Feed')}}</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
              </div>
              <form action="{{route('admin.blog.rss.feed.all.info')}}" method="post">
                  <div class="modal-body">
                      @csrf

                      <div class="form-group">
                          <label>{{__('Rss Feed  Link')}}</label>
                          <input  type="text" class="form-control rss_automation_link" name="link">
                          <small> {{'All Rss Times of India (Demo) : '}}
                              <span class="text-info">
                            <a href="https://timesofindia.indiatimes.com/rss.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rss.cms')}}</a></span> </small><br>
                          <small> {{'This is a demo link : '}} <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rssfeeds/296589292.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rssfeeds/296589292.cms')}}</a></span> </small><br>
                          <small class="text-danger">{{__('Allowed feed image formats : jpg, jpeg, png , gif')}} </small>
                      </div>

                      <div class="row">
                          <div class="form-group col-lg-6 mt-3">
                              <label for="">{{__('Automation Type')}}</label>
                              <select class="form-control" name="automation_type">
                                  <option value="every_minutes">{{__('Every Minutes')}}</option>
                                  <option value="every_two_hours">{{__('Every Two Hours')}}</option>
                                  <option value="every_six_hours">{{__('Every Six Hours')}}</option>
                                  <option value="daily">{{__('Daily')}}</option>
                                  <option value="weekly" >{{__('Weekly')}}</option>
                              </select>
                          </div>

                          <div class="form-group col-lg-6 mt-3">
                              <label>{{__('Import Item')}}</label>
                              <input  type="number" min="1" class="form-control" name="imported_item">
                          </div>

                      <div class="form-group col-lg-12">
                          <label for="edit_status">{{__('Status')}}</label>
                          <select name="status" class="form-control">
                              <option value="on">{{__("On")}}</option>
                              <option value="off">{{__("Off")}}</option>
                          </select>
                      </div>
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

  <div class="modal fade" id="rss_edit_modal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">{{__('Edit Rss Feed Item')}}</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
              </div>
              <form action="{{route('admin.blog.rss.feed.all.info.update')}}"  method="post">
                  <input type="hidden" name="id" id="rss_feed_id">
                  <div class="modal-body">
                      @csrf
                      <div class="form-group">
                          <label>{{__('Rss Feed  Link')}}</label>
                          <input  type="text" class="form-control rss_automation_link" name="link" id="edit_link">
                          <small> {{'All Rss Times of India (Demo) : '}}
                              <span class="text-info">
                            <a href="https://timesofindia.indiatimes.com/rss.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rss.cms')}}</a></span> </small><br>
                          <small> {{'This is a demo link : '}} <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rssfeeds/296589292.cms" target="_blank"> {{__( 'https://timesofindia.indiatimes.com/rssfeeds/296589292.cms')}}</a></span> </small><br>
                          <small class="text-danger">{{__('Allowed feed image formats : jpg, jpeg, png , gif')}} </small>
                      </div>

                      <div class="row">
                          <div class="form-group col-lg-6 mt-3">
                              <label for="">{{__('Automation Type')}}</label>
                              <select class="form-control" name="automation_type" id="edit_automation_type">
                                  <option value="every_minutes">{{__('Every Minutes')}}</option>
                                  <option value="every_two_hours">{{__('Every Two Hours')}}</option>
                                  <option value="every_six_hours">{{__('Every Six Hours')}}</option>
                                  <option value="daily">{{__('Daily')}}</option>
                                  <option value="weekly" >{{__('Weekly')}}</option>
                              </select>
                          </div>

                          <div class="form-group col-lg-6 mt-3">
                              <label>{{__('Import Item')}}</label>
                              <input  type="number" min="1" class="form-control" name="imported_item" id="edit_imported_item">
                          </div>

                          <div class="form-group col-lg-12">
                              <label for="edit_status">{{__('Status')}}</label>
                              <select name="status" class="form-control" id="edit_status">
                                  <option value="on">{{__("On")}}</option>
                                  <option value="off">{{__("Off")}}</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                      <button id="update" type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
@endsection

@section('script')
    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('admin.blog.rss.feed.all.info.bulk.action')" />
                    <x-btn.submit/>
                    <x-btn.update/>

                    $(document).on('click','.rss_edit_btn',function(){
                        var el = $(this);
                        var id = el.data('id');
                        var link = el.data('link');
                        var status = el.data('status');
                        var automation_type = el.data('automation_type');
                        var imported_item = el.data('imported_item');
                        var modal = $('#rss_edit_modal');

                        modal.find('#rss_feed_id').val(id);
                        modal.find('#edit_status option[value="'+status+'"]').attr('selected',true);
                        modal.find('#edit_link').val(link);
                        modal.find('#edit_automation_type').val(automation_type);
                        modal.find('#edit_imported_item').val(imported_item);

                    });
            });
        })(jQuery)
    </script>
    <x-datatable.js/>
@endsection

