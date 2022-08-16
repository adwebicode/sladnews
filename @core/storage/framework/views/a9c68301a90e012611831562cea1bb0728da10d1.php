<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .plus-btn{
            padding: 7px 10px;
        }
        .chart-title.small{
            font-size: 16px;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
       $statistics = [
           ['title' => 'Total Admin','value' => $total_admin, 'icon' => 'user-secret','data_url'=> $data_url['admin']],
           ['title' => 'Total User','value' => $total_user, 'icon' => 'user','data_url'=> $data_url['user']],
           ['title' => 'Total Blogs','value' => $blog_count, 'icon' => 'blog','data_url'=> $data_url['blog']],
           ['title' => 'Total Polls','value' => $all_poll, 'icon' => 'poll','data_url'=> $data_url['poll']],
           ['title' => 'Total Advertisement','value' => $total_advertisement, 'icon' => 'ad','data_url'=> $data_url['advertisement']],
           ['title' => 'Total Subscriber','value' => $total_subscriber, 'icon' => 'envelope-square','data_url'=> $data_url['subscriber']],
       ];

    ?>

    <div class="main-content-inner">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">

                    <div class="col-lg-4">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title small text-center"><?php echo e(__("Site Device Views Last 30 Days")); ?></h2>
                            <div>
                                <canvas id="visited_device_show"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title small text-center"><?php echo e(__("Operating System Views Last 30 Days")); ?></h2>
                            <div>
                                <canvas id="visited_os_show"></canvas>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title small text-center"><?php echo e(__("As Viewers Browser Last 30 Days")); ?></h2>
                            <div>
                                <canvas id="visited_browser_show"></canvas>
                            </div>
                        </div>
                    </div>


                    <?php $__currentLoopData = $statistics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4 mt-5 mb-3">
                            <div class="card card-hover">
                                <div class="dash-box text-white">
                                    <h1 class="dash-icon">
                                        <i class="ti-<?php echo e($data['icon']); ?> mb-1 font-16"></i>
                                    </h1>
                                    <div class="dash-content">
                                        <h5 class="mb-0 mt-1"><?php echo e($data['value']); ?></h5>
                                        <small class="font-light"><?php echo e(__($data['title'])); ?></small>
                                        <a href="<?php echo e($data['data_url'] ?? ''); ?>" class="text-white btn btn-info btn-sm ml-3 plus-btn"><i class="ti-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    <div class="col-lg-6">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title"><?php echo e(__("Most viewed url in last 30 Days")); ?> <?php echo e(date('Y')); ?></h2>
                            <canvas id="visited_url_show"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title"><?php echo e(__(" Browser Data Chart View last 30 Days")); ?> <?php echo e(date('Y')); ?></h2>
                            <canvas id="visited_url_show_two"></canvas>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/backend/js/chart.js')); ?>"></script>
    <script>

        //By Visited URL
        $.ajax({
            url: '<?php echo e(route('admin.home.visited.url.data')); ?>',
            type: 'POST',
            async: false,
            data: {
                _token : "<?php echo e(csrf_token()); ?>"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;

                new Chart(
                    document.getElementById('visited_url_show'),
                    {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: '<?php echo e(__('View Raised')); ?>',
                                 backgroundColor:  '#039cda',
                                 borderColor: '#039cda',
                                 data: chartdata,
                                 barThickness: 15,
                                 hoverBackgroundColor: '#fc3c68',
                                 borderRadius: 5,
                                 hoverBorderColor: '#fc3c68',
                                minBarLength: 50,
                                indexAxis: "x",
                                pointStyle: 'star',
                            }],
                        }

                    }
                );
            }
        });

        //By Visited URL Two
        $.ajax({
            url: '<?php echo e(route('admin.home.chart.data.by.browser')); ?>',
            type: 'POST',
            async: false,
            data: {
                _token : "<?php echo e(csrf_token()); ?>"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;

                new Chart(
                    document.getElementById('visited_url_show_two'),
                    {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: '<?php echo e(__('View Raised')); ?>',
                                backgroundColor:  '#fc3c68',
                                borderColor: '#fc3c68',
                                data: chartdata,
                                barThickness: 15,
                                hoverBackgroundColor: '#039cda',
                                borderRadius: 5,
                                hoverBorderColor: '#039cda',
                                minBarLength: 50,
                                indexAxis: "x",
                                pointStyle: 'star',
                            }],
                        }

                    }
                );
            }
        });

        //By Device
        $.ajax({
            url: '<?php echo e(route('admin.home.chart.data.by.device')); ?>',
            type: 'POST',
            async: false,
            data: {
                _token : "<?php echo e(csrf_token()); ?>"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;
                new Chart(
                    document.getElementById('visited_device_show'),
                    {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: chartdata,
                                backgroundColor: ['#3c60c2', ],
                                hoverOffset: 4
                            }]
                        }
                    }
                );
            }
        });



        //By OS
        $.ajax({
            url: '<?php echo e(route('admin.home.chart.data.by.os')); ?>',
            type: 'POST',
            async: false,
            data: {
                _token : "<?php echo e(csrf_token()); ?>"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;
                new Chart(
                    document.getElementById('visited_os_show'),
                    {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: chartdata,
                                backgroundColor: ['#039cda',],
                                hoverOffset: 4
                            }]
                        }
                    }
                );
            }
        });

        //By Browser
        $.ajax({
            url: '<?php echo e(route('admin.home.chart.data.by.browser')); ?>',
            type: 'POST',
            async: false,
            data: {
                _token : "<?php echo e(csrf_token()); ?>"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;

                new Chart(
                    document.getElementById('visited_browser_show'),
                    {
                        type: 'doughnut',
                        data: {
                            labels:labels,
                            datasets: [{
                                label: '<?php echo e(__('Browsers')); ?>',
                                 backgroundColor: ['#7c5f94'],
                                hoverOffset: 4,
                                data: chartdata,
                            }]
                        }
                    }
                );
            }
        });



    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xgenxchi/public_html/laravel/katerio/beta/@core/resources/views/backend/admin-home.blade.php ENDPATH**/ ?>