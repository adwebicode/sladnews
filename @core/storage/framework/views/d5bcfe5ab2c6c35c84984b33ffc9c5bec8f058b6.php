<?php $__env->startSection('style'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('All Users')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mt-5">
                            <div class="card">
                                <div class="card-body">
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
                                    <div class="d-flex justify-content-between">
                                        <div class="left-content">
                                            <h4 class="header-title"><?php echo e(__('All Users')); ?></h4>
                                        </div>
                                        <div class="right-content">
                                            <a href="<?php echo e(route('admin.frontend.new.user')); ?>" class="btn btn-info"> <?php echo e(__('Add New')); ?></a>
                                        </div>
                                    </div>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-delete')): ?>
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
                                    <?php endif; ?>
                                    <div class="data-tables datatable-primary table-wrap">
                                        <table class="text-center">
                                            <thead class="text-capitalize">
                                            <tr>
                                                <th class="no-sort">
                                                    <div class="mark-all-checkbox">
                                                        <input type="checkbox" class="all-checkbox">
                                                    </div>
                                                </th>
                                                <th><?php echo e(__('ID')); ?></th>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th><?php echo e(__('Email')); ?></th>
                                                <th><?php echo e(__('Designation')); ?></th>
                                                <th><?php echo e(__('Username')); ?></th>
                                                <th><?php echo e(__('Image')); ?></th>
                                                <th><?php echo e(__('Action')); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__currentLoopData = $all_user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.bulk-delete-checkbox','data' => ['id' => $data->id]]); ?>
<?php $component->withName('bulk-delete-checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?></td>
                                                    <td><?php echo e($data->id); ?></td>
                                                    <td><?php echo e($data->name); ?></td>
                                                    <td><?php echo e($data->email); ?> <?php if($data->email_verified == 1): ?> <i class="fas fa-check-circle text-success"></i> <?php endif; ?></td>
                                                    <td><?php echo e($data->designation); ?></td>
                                                    <td><?php echo e($data->username); ?></td>
                                                    <td>
                                                        <?php
                                                            $brand_img = get_attachment_image_by_id($data->image,null,true);
                                                        ?>
                                                        <?php if(!empty($brand_img)): ?>
                                                            <div class="attachment-preview">
                                                                <div class="thumbnail">
                                                                    <div class="centered">
                                                                        <img class="avatar user-thumb" src="<?php echo e($brand_img['img_url']); ?>" alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php  $img_url = $brand_img['img_url']; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-delete')): ?>
                                                          <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.delete-popover','data' => ['url' => route('admin.frontend.delete.user',$data->id)]]); ?>
<?php $component->withName('delete-popover'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.frontend.delete.user',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                                        <?php endif; ?>

                                                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-edit')): ?>
                                                        <a href="#"
                                                           data-id="<?php echo e($data->id); ?>"
                                                           data-username="<?php echo e($data->username); ?>"
                                                           data-imageid="<?php echo e($data->image); ?>"
                                                           data-image="<?php echo e($img_url); ?>"
                                                           data-name="<?php echo e($data->name); ?>"
                                                           data-email="<?php echo e($data->email); ?>"
                                                           data-phone="<?php echo e($data->phone); ?>"
                                                           data-address="<?php echo e($data->address); ?>"
                                                           data-state="<?php echo e($data->state); ?>"
                                                           data-city="<?php echo e($data->city); ?>"
                                                           data-zipcode="<?php echo e($data->zipcode); ?>"
                                                           data-country="<?php echo e($data->country); ?>"
                                                           data-designation="<?php echo e($data->designation); ?>"
                                                           data-description="<?php echo e($data->description); ?>"
                                                           data-facebook_url="<?php echo e($data->facebook_url); ?>"
                                                           data-twitter_url="<?php echo e($data->twitter_url); ?>"
                                                           data-instagram_url="<?php echo e($data->instagram_url); ?>"
                                                           data-linkedin_url="<?php echo e($data->linkedin_url); ?>"
                                                           data-email_verified="<?php echo e($data->email_verified); ?>"
                                                           data-toggle="modal"
                                                           data-target="#user_edit_modal"
                                                           class="btn btn-primary btn-sm mb-3 mr-1 user_edit_btn"
                                                        >
                                                            <i class="ti-pencil"></i>
                                                        </a>

                                                            <a class="btn btn-success btn-sm mb-3 text-white user_permission_btn"
                                                               data-toggle="modal"
                                                               data-target="#user_permission_modal"
                                                               data-id="<?php echo e($data->id); ?>"
                                                               data-auto_post_approval="<?php echo e($data->auto_post_approval); ?>"
                                                               data-is_banned="<?php echo e($data->is_banned); ?>"
                                                               data-post_permission="<?php echo e($data->post_permission); ?>"
                                                           >
                                                                <?php echo e(__('Permissions')); ?>

                                                            </a>

                                                        <a href="#"
                                                           data-id="<?php echo e($data->id); ?>"
                                                           data-toggle="modal"
                                                           data-target="#user_change_password_modal"
                                                           class="btn btn-info btn-sm mb-3 mr-1 user_change_password_btn"
                                                        >
                                                            <?php echo e(__("Change Password")); ?>

                                                        </a>
                                                        <form action="<?php echo e(route('admin.all.frontend.user.email.status')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" value="<?php echo e($data->id); ?>" name="user_id">
                                                            <input type="hidden" value="<?php echo e($data->email_verified); ?>" name="email_verified">
                                                            <button type="submit" class="btn btn-sm <?php if($data->email_verified == 1): ?>  btn-dark <?php else: ?> btn-warning <?php endif; ?>">
                                                                <?php if($data->email_verified == 1): ?> <?php echo e(__('Enable Email Verify')); ?> <?php else: ?> <?php echo e(__('Disable Email Verify')); ?> <?php endif; ?>
                                                            </button>
                                                        </form>
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
                        <!-- Primary table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('User Details Edit')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="<?php echo e(route('admin.frontend.user.update')); ?>" id="user_edit_modal_form" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="name"><?php echo e(__('Name')); ?></label>
                            <input type="text" class="form-control"  id="name" name="name" placeholder="<?php echo e(__('Enter name')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email"><?php echo e(__('Email')); ?></label>
                            <input type="text" class="form-control"  id="email" name="email" placeholder="<?php echo e(__('Email')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone"><?php echo e(__('Phone')); ?></label>
                            <input type="text" class="form-control"  id="phone" name="phone" placeholder="<?php echo e(__('Phone')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="country"><?php echo e(__('Country')); ?></label>
                            <?php echo get_country_field('country','country','form-control'); ?>

                        </div>
                        <div class="form-group">
                            <label for="state"><?php echo e(__('State')); ?></label>
                            <input type="text" class="form-control"  id="state" name="state" placeholder="<?php echo e(__('State')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="city"><?php echo e(__('City')); ?></label>
                            <input type="text" class="form-control"  id="city" name="city" placeholder="<?php echo e(__('City')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="zipcode"><?php echo e(__('Zipcode')); ?></label>
                            <input type="text" class="form-control"  id="zipcode" name="zipcode" placeholder="<?php echo e(__('Zipcode')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="address"><?php echo e(__('Address')); ?></label>
                            <input type="text" class="form-control"  id="address" name="address" placeholder="<?php echo e(__('Address')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone"><?php echo e(__('Designation')); ?></label>
                            <input type="text" class="form-control"  id="designation" name="designation" placeholder="<?php echo e(__('Designation')); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email"><?php echo e(__('Facebook URL')); ?></label>
                            <input type="text" class="form-control"  id="facebook_url" name="facebook_url" >
                        </div>

                        <div class="form-group">
                            <label for="email"><?php echo e(__('Twitter URL')); ?></label>
                            <input type="text" class="form-control"  id="twitter_url" name="twitter_url" >
                        </div>

                        <div class="form-group">
                            <label for="email"><?php echo e(__('Instagram URL')); ?></label>
                            <input type="text" class="form-control"  id="instagram_url" name="instagram_url">
                        </div>

                        <div class="form-group">
                            <label for="email"><?php echo e(__('Linkedin URL')); ?></label>
                            <input type="text" class="form-control"  id="linkedin_url" name="linkedin_url" >
                        </div>

                        <div class="form-group">
                            <label for="phone"><?php echo e(__('Description')); ?></label>
                            <textarea class="form-control" name="description" id="description" cols="5"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image"><?php echo e(__('Image')); ?></label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" id="edit_image" name="image" value="">
                                <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="<?php echo e(__('Select Team Image')); ?>" data-modaltitle="<?php echo e(__('Upload Team Image')); ?>" data-toggle="modal" data-target="#media_upload_modal">
                                    <?php echo e(__('Upload Image')); ?>

                                </button>
                            </div>
                            <small><?php echo e(__('Recommended image size 270x280')); ?></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Save changes')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_change_password_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Change Admin Password')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form action="<?php echo e(route('admin.frontend.user.password.change')); ?>" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password"><?php echo e(__('Password')); ?></label>
                            <input type="password" class="form-control" name="password" placeholder="<?php echo e(__('Enter Password')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation"><?php echo e(__('Confirm Password')); ?></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="<?php echo e(__('Confirm Password')); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Change Password')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="user_permission_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('User Permission Settings')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>

                <form action="<?php echo e(route('admin.frontend.user.permission.settings')); ?>" method="post" enctype="multipart/form-data" id="user_permission_form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="user_permission_id" name="id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo e(__('Auto Approval')); ?></label>
                                        <label class="switch">
                                            <input type="checkbox" name="auto_post_approval" class="auto_post_approval">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('Banned User')); ?></label>
                                        <label class="switch">
                                            <input type="checkbox" name="is_banned" class="is_banned">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__('Post Permission')); ?></label>
                                        <label class="switch">
                                            <input type="checkbox" name="post_permission" class="post_permission">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button id="update" type="submit" class="btn btn-primary"><?php echo e(__('Save Changes')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.markup','data' => []]); ?>
<?php $component->withName('media.markup'); ?>
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

<?php $__env->startSection('script'); ?>

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

    <script>
        (function($){
        "use strict";
        $(document).ready(function() {
            $(document).on('click','.user_change_password_btn',function(e){
                e.preventDefault();
                var el = $(this);
                var form = $('#user_password_change_modal_form');
                form.find('#ch_user_id').val(el.data('id'));
            });

            $(document).on('click','.user_permission_btn',function(e){
               e.preventDefault();
                var form = $('#user_permission_form');
                var el = $(this);
                var user_id = el.data('id');

                var auto_post_approval = el.data('auto_post_approval');
                var is_banned = el.data('is_banned');
                var post_permission = el.data('post_permission');

                $('#user_permission_id').val(user_id);

                if(auto_post_approval === 1){
                    $('.auto_post_approval').prop('checked',true);
                }else{
                    $('.auto_post_approval').prop('checked',false);
                }

                if(is_banned === 1){
                    $('.is_banned').prop('checked',true);
                }else{
                    $('.is_banned').prop('checked',false);
                }

                if(post_permission === 1){
                    $('.post_permission').prop('checked',true);
                }else{
                    $('.post_permission').prop('checked',false);
                }

            });

            $(document).on('click','.user_edit_btn',function(e){
                e.preventDefault();
                var form = $('#user_edit_modal_form');
                var el = $(this);
                form.find('#user_id').val(el.data('id'));
                form.find('#name').val(el.data('name'));
                form.find('#username').val(el.data('username'));
                form.find('#email').val(el.data('email'));
                form.find('#phone').val(el.data('phone'));
                form.find('#state').val(el.data('state'));
                form.find('#city').val(el.data('city'));
                form.find('#zipcode').val(el.data('zipcode'));
                form.find('#address').val(el.data('address'));
                form.find('#designation').val(el.data('designation'));
                form.find('#description').val(el.data('description'));

                var image = el.data('image');
                var imageid = el.data('imageid');

                form.find('#facebook_url').val(el.data('facebook_url'));
                form.find('#twitter_url').val(el.data('twitter_url'));
                form.find('#instagram_url').val(el.data('instagram_url'));
                form.find('#linkedin_url').val(el.data('linkedin_url'));
                form.find('#country option[value="'+el.data('country')+'"]').attr('selected',true);

                if(imageid != ''){
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="'+image+'" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(imageid);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });
            $(document).on('click','#bulk_delete_btn',function (e) {
                e.preventDefault();
                var bulkOption = $('#bulk_option').val();
                var allCheckbox =  $('.bulk-checkbox:checked');
                var allIds = [];
                allCheckbox.each(function(index,value){
                    allIds.push($(this).val());
                });
                if(allIds != '' && bulkOption == 'delete'){
                    $(this).text('<?php echo e(__('Deleting...')); ?>');
                    $.ajax({
                        'type' : "POST",
                        'url' : "<?php echo e(route('admin.all.frontend.user.bulk.action')); ?>",
                        'data' : {
                            _token: "<?php echo e(csrf_token()); ?>",
                            ids: allIds
                        },
                        success:function (data) {
                            location.reload();
                        }
                    });
                }

            });
            $('.all-checkbox').on('change',function (e) {
                e.preventDefault();
                var value = $('.all-checkbox').is(':checked');
                var allChek = $(this).parent().parent().parent().parent().parent().find('.bulk-checkbox');
                //have write code here fr
                if( value == true){
                    allChek.prop('checked',true);
                }else{
                    allChek.prop('checked',false);
                }
            });
        });
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
    })(jQuery);
        
    </script>
    <script src="<?php echo e(asset('assets/backend/js/dropzone.js')); ?>"></script>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.js','data' => []]); ?>
<?php $component->withName('media.js'); ?>
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

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/backend/frontend-user/all-user.blade.php ENDPATH**/ ?>