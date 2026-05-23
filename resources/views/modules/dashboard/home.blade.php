@extends('layouts.main')

@section('titulo', $titulo)
@section('paginaTitulo', $paginaTitulo)
@section('paginaSubtitulo', $paginaSubtitulo)

@section('contenido')

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <!-- <div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="row row-cols-1">
            <div class="overflow-hidden d-slider1 ">
                <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-01" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="90" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24"  viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Total Sales</p>
                            <h4 class="counter">$560K</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-02" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Total Profit</p>
                            <h4 class="counter">$185K</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-03" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Total Cost</p>
                            <h4 class="counter">$375K</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-04" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24px"  viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Revenue</p>
                            <h4 class="counter">$742K</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1100">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-05" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="50" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24px"  viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Net Income</p>
                            <h4 class="counter">$150K</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1200">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-06" class="text-center circle-progress-01 circle-progress circle-progress-info" data-min-value="0" data-max-value="100" data-value="40" data-type="percent">
                            <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Today</p>
                            <h4 class="counter">$4600</h4>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1300">
                    <div class="card-body">
                        <div class="progress-widget">
                            <div id="circle-progress-07" class="text-center circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="30" data-type="percent">
                            <svg class="card-slie-arrow icon-24 " width="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                            </svg>
                            </div>
                            <div class="progress-detail">
                            <p  class="mb-2">Members</p>
                            <h4 class="counter">11.2M</h4>
                            </div>
                        </div>
                    </div>
                </li>
                </ul>
                <div class="swiper-button swiper-button-next"></div>
                <div class="swiper-button swiper-button-prev"></div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <!-- Ciudadanos reincidentes -->
        <div class="col-md-12 col-lg-6">
            <div class="card" data-aos="fade-up" data-aos-delay="900">
                <div class="flex-wrap card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Ciudadanos reincidentes</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-ciudadanos-reincidentes" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Denuncias frecuentes -->
        <div class="col-md-12 col-lg-6">
            <div class="card" data-aos="fade-up" data-aos-delay="1000">
                <div class="flex-wrap card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Frecuencias de las Denuncias</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-denuncias-frecuentes" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Sectores frecuentes -->
        <div class="col-md-12 col-lg-6">
            <div class="card" data-aos="fade-up" data-aos-delay="1100">
                <div class="flex-wrap card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Sectores frecuentes</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-sectores-frecuentes" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/echart/echarts.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script> -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Casos más frecuentes
        

        // 2. Ciudadanos reincidentes
        const chartReincidentes = echarts.init(document.querySelector('#chart-ciudadanos-reincidentes'));
        chartReincidentes.setOption({
            tooltip: { trigger: 'item' },
            legend: { top: '5%', left: 'center' },
            series: [{
                name: 'Reincidencias',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                itemStyle: { borderRadius: 10, borderColor: '#fff', borderWidth: 2 },
                label: { show: false, position: 'center' },
                emphasis: { label: { show: true, fontSize: 20, fontWeight: 'bold' } },
                labelLine: { show: false },
                data: [
                    { value: 1048, name: '1 vez' },
                    { value: 735, name: '2 veces' },
                    { value: 580, name: '3 veces' },
                    { value: 484, name: '4+ veces' }
                ]
            }]
        });

        // 3. Denuncias frecuentes
        const chartDenuncias = echarts.init(document.querySelector('#chart-denuncias-frecuentes'));
        chartDenuncias.setOption({
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']
            },
            yAxis: { type: 'value' },
            series: [{
                data: [120, 200, 150, 80, 70, 110, 130],
                type: 'line',
                smooth: true,
                areaStyle: {},
                itemStyle: { color: '#08b1ba' }
            }]
        });

        // 4. Sectores frecuentes
        const chartSectores = echarts.init(document.querySelector('#chart-sectores-frecuentes'));
        chartSectores.setOption({
            tooltip: { trigger: 'item' },
            series: [{
                name: 'Sectores',
                type: 'pie',
                radius: '50%',
                data: [
                    { value: 40, name: 'Sector Norte' },
                    { value: 30, name: 'Sector Sur' },
                    { value: 20, name: 'Sector Este' },
                    { value: 10, name: 'Sector Oeste' }
                ],
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        });

        window.addEventListener('resize', () => {
            chartReincidentes.resize();
            chartDenuncias.resize();
            chartSectores.resize();
        });
    });
</script>
@endpush
