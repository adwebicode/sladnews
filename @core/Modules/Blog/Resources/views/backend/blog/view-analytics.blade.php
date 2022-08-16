@extends('backend.admin-master')
@section('site-title')
    {{__('Blog Analytics ') }}
@endsection

@section('content')
  <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">

                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Analytics Data : ') }} <span class="text-primary">{{ $blog->title}}</span> </h4>

                            </div>
                            <div class="header-title d-flex">
                                <div class="btn-wrapper-inner">
                                    <a href="{{route('admin.blog')}}" class="btn btn-info"> {{__('Go Back')}}</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="chart-wrapper margin-top-40">
                                    <h2 class="chart-title">{{__("Views in last 30 Days")}} {{date('Y')}}</h2>
                                    <canvas id="view_data"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/chart.js')}}"></script>
<script>

        $.ajax({
            url: '{{route('admin.blog.view.data.monthly')}}',
            type: 'POST',
            async: false,
            data: {
                _token : "{{csrf_token()}}"
            },
            success: function (data) {
                console.log(data)
                labels = data.labels;
                let chartdata = data.data;

                new Chart(
                    document.getElementById('view_data'),
                    {
                        type: 'bar',
                        data: {
                            labels:labels,
                            datasets: [{
                                label: '{{__('View Raised')}}',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                data: chartdata,
                            }]
                        }
                    }
                );
            }
        });

</script>
@endsection
