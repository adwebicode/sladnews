<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('All Rss Feed Info')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
   <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.datatable.css','data' => []]); ?>
<?php $component->withName('datatable.css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.css','data' => []]); ?>
<?php $component->withName('media.css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <div class="col-lg-12 col-ml-12 padding-bottom-30">
       <div class="row">
           <div class="col-lg-12">
               <div class="margin-top-40"></div>
               <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.success','data' => []]); ?>
<?php $component->withName('msg.success'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
               <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.error','data' => []]); ?>
<?php $component->withName('msg.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
           </div>
           <div class="col-lg-12 mt-5">
               <div class="card">
                   <div class="card-body">
                       <div class="header-wrap d-flex justify-content-between">
                           <div class="left-content">
                               <h4 class="header-title"><?php echo e(__('All Rss Feed Info')); ?>  </h4>
                               <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.bulk-action','data' => []]); ?>
<?php $component->withName('bulk-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                           </div>
                           <div class="header-title d-flex">
                               <div class="btn-wrapper-inner ml-2">
                                   <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#rss_automation_modal"><?php echo e(__('Add New Import')); ?></button>
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
                                   <th><?php echo e(__('ID')); ?></th>
                                   <th><?php echo e(__('Link')); ?></th>
                                   <th><?php echo e(__('Imported Item')); ?></th>
                                   <th><?php echo e(__('Cronjob Type')); ?></th>
                                   <th><?php echo e(__('Status')); ?></th>
                                   <th><?php echo e(__('Action')); ?></th>
                                   </thead>
                                   <tbody>
                                   <?php $__currentLoopData = $all_feed_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                       <tr>
                                         <td>
                                             <div class="bulk-checkbox-wrapper">
                                                 <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="<?php echo e($data->id); ?>">
                                             </div>
                                         </td>
                                           <td><?php echo e($data->id); ?></td>
                                           <td><?php echo e($data->link ?? ''); ?></td>
                                           <td><?php echo e($data->imported_item ?? ''); ?></td>
                                           <td><?php echo e(str_replace('_',' ',ucwords($data->automation_type))); ?></td>

                                           <td>
                                               <?php if($data->status == 'off'): ?>
                                                   <span class="alert alert-danger" ><?php echo e(__('Off')); ?></span>
                                               <?php else: ?>
                                                   <span class="alert alert-success" ><?php echo e(__('On')); ?></span>
                                               <?php endif; ?>
                                           </td>

                                           <td>
                                               <a href="#"
                                                  data-toggle="modal"
                                                  data-target="#rss_edit_modal"
                                                  class="btn btn-lg btn-primary btn-sm mb-3 mr-1 rss_edit_btn"
                                                  data-id="<?php echo e($data->id); ?>"
                                                  data-link="<?php echo e($data->link); ?>"
                                                  data-imported_item="<?php echo e($data->imported_item); ?>"
                                                  data-automation_type="<?php echo e($data->automation_type); ?>"
                                                  data-status="<?php echo e($data->status); ?>"
                                               >
                                                   <i class="ti-pencil"></i>
                                               </a>
                                               <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.delete-popover-all-lang','data' => ['url' => route('admin.blog.rss.feed.all.info.delete',$data->id)]]); ?>
<?php $component->withName('delete-popover-all-lang'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.blog.rss.feed.all.info.delete',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                           </td>
                                       </tr>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                  <h5 class="modal-title"><?php echo e(__('Import Rss Feed')); ?></h5>
                  <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
              </div>
              <form action="<?php echo e(route('admin.blog.rss.feed.all.info')); ?>" method="post">
                  <div class="modal-body">
                      <?php echo csrf_field(); ?>

                      <div class="form-group">
                          <label><?php echo e(__('Rss Feed  Link')); ?></label>
                          <input  type="text" class="form-control rss_automation_link" name="link">
                          <small> <?php echo e('All Rss Times of India (Demo) : '); ?>

                              <span class="text-info">
                            <a href="https://timesofindia.indiatimes.com/rss.cms" target="_blank"> <?php echo e(__( 'https://timesofindia.indiatimes.com/rss.cms')); ?></a></span> </small><br>
                          <small> <?php echo e('This is a demo link : '); ?> <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rssfeeds/296589292.cms" target="_blank"> <?php echo e(__( 'https://timesofindia.indiatimes.com/rssfeeds/296589292.cms')); ?></a></span> </small><br>
                          <small class="text-danger"><?php echo e(__('Allowed feed image formats : jpg, jpeg, png , gif')); ?> </small>
                      </div>

                      <div class="row">
                          <div class="form-group col-lg-6 mt-3">
                              <label for=""><?php echo e(__('Automation Type')); ?></label>
                              <select class="form-control" name="automation_type">
                                  <option value="every_minutes"><?php echo e(__('Every Minutes')); ?></option>
                                  <option value="every_two_hours"><?php echo e(__('Every Two Hours')); ?></option>
                                  <option value="every_six_hours"><?php echo e(__('Every Six Hours')); ?></option>
                                  <option value="daily"><?php echo e(__('Daily')); ?></option>
                                  <option value="weekly" ><?php echo e(__('Weekly')); ?></option>
                              </select>
                          </div>

                          <div class="form-group col-lg-6 mt-3">
                              <label><?php echo e(__('Import Item')); ?></label>
                              <input  type="number" min="1" class="form-control" name="imported_item">
                          </div>

                      <div class="form-group col-lg-12">
                          <label for="edit_status"><?php echo e(__('Status')); ?></label>
                          <select name="status" class="form-control">
                              <option value="on"><?php echo e(__("On")); ?></option>
                              <option value="off"><?php echo e(__("Off")); ?></option>
                          </select>
                      </div>
                  </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                      <button id="submit" type="submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <div class="modal fade" id="rss_edit_modal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><?php echo e(__('Edit Rss Feed Item')); ?></h5>
                  <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
              </div>
              <form action="<?php echo e(route('admin.blog.rss.feed.all.info.update')); ?>"  method="post">
                  <input type="hidden" name="id" id="rss_feed_id">
                  <div class="modal-body">
                      <?php echo csrf_field(); ?>
                      <div class="form-group">
                          <label><?php echo e(__('Rss Feed  Link')); ?></label>
                          <input  type="text" class="form-control rss_automation_link" name="link" id="edit_link">
                          <small> <?php echo e('All Rss Times of India (Demo) : '); ?>

                              <span class="text-info">
                            <a href="https://timesofindia.indiatimes.com/rss.cms" target="_blank"> <?php echo e(__( 'https://timesofindia.indiatimes.com/rss.cms')); ?></a></span> </small><br>
                          <small> <?php echo e('This is a demo link : '); ?> <span class="text-info"> <a href="https://timesofindia.indiatimes.com/rssfeeds/296589292.cms" target="_blank"> <?php echo e(__( 'https://timesofindia.indiatimes.com/rssfeeds/296589292.cms')); ?></a></span> </small><br>
                          <small class="text-danger"><?php echo e(__('Allowed feed image formats : jpg, jpeg, png , gif')); ?> </small>
                      </div>

                      <div class="row">
                          <div class="form-group col-lg-6 mt-3">
                              <label for=""><?php echo e(__('Automation Type')); ?></label>
                              <select class="form-control" name="automation_type" id="edit_automation_type">
                                  <option value="every_minutes"><?php echo e(__('Every Minutes')); ?></option>
                                  <option value="every_two_hours"><?php echo e(__('Every Two Hours')); ?></option>
                                  <option value="every_six_hours"><?php echo e(__('Every Six Hours')); ?></option>
                                  <option value="daily"><?php echo e(__('Daily')); ?></option>
                                  <option value="weekly" ><?php echo e(__('Weekly')); ?></option>
                              </select>
                          </div>

                          <div class="form-group col-lg-6 mt-3">
                              <label><?php echo e(__('Import Item')); ?></label>
                              <input  type="number" min="1" class="form-control" name="imported_item" id="edit_imported_item">
                          </div>

                          <div class="form-group col-lg-12">
                              <label for="edit_status"><?php echo e(__('Status')); ?></label>
                              <select name="status" class="form-control" id="edit_status">
                                  <option value="on"><?php echo e(__("On")); ?></option>
                                  <option value="off"><?php echo e(__("Off")); ?></option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                      <button id="update" type="submit" class="btn btn-primary"><?php echo e(__('Save Change')); ?></button>
                  </div>
              </form>
          </div>
      </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.bulk-action-js','data' => ['url' => route('admin.blog.rss.feed.all.info.bulk.action')]]); ?>
<?php $component->withName('bulk-action-js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.blog.rss.feed.all.info.bulk.action'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.btn.submit','data' => []]); ?>
<?php $component->withName('btn.submit'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.btn.update','data' => []]); ?>
<?php $component->withName('btn.update'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

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
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.datatable.js','data' => []]); ?>
<?php $component->withName('datatable.js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sohan/public_html/katerio/@core/Modules/Blog/Resources/views/backend/blog/rss-feed/all-info.blade.php ENDPATH**/ ?>