@extends('layouts.dashboard')



<!-- STYLE-->
<link href="{{ asset('css/home.css') }}" rel="stylesheet">

<head>
    <script type="application/javascript" src="http://code.highcharts.com/highcharts.js"></script>
</head>

@section('content')
    <div class="row">
        @if (session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="col-6">
            <p class="text-left"><i class="fas fa-tachometer-alt"></i> > Dashboard</p>
            <a href=" {{ route('dashboard') }} " class="btn btn-secondary  ">
                <i class="fas fa-redo"></i> Muat Ulang
            </a>
            <a href="{{ route('dashboard.pdf') }}" class="btn btn-primary  ">
                <i class="fas fa-file-download"></i> Download Panduan Website
            </a>
        </div>

        {{-- PENCARIAN BERDASARKAN TANGGAL --}}
        <div class="col-6">
            <form action="{{ route('dashboard.search') }}" method="POST" autocomplete="off">
                @csrf
                <label for="start_date">Tanggal Mulai</label>
                {{-- {{$start_date}} --}}
                <label for="end_date" style="margin-left: 35%">Tanggal Selesai</label>
                {{-- {{$end_date}} --}}
                
                <div class="input-group">
                    <input class="form-control" name="start_date" id="start_date" data-provide="datepicker" data-date-format="dd/M/yyyy">
                    <input class="form-control" name="end_date" id="end_date" data-provide="datepicker" data-date-format="dd/M/yyyy">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- CARD --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card card-1">
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-9">
                            <div class="text-left align-middle" style="font-size: 40px;">
                                {{ $count_propel }}
                                <br><span style="font-size: 15px">Total Propel</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-right">
                                <span class="align-top" style="opacity: 0.5">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer text-center" style="padding: 0px; background:black; opacity: 0.3">
                    <small class="text" style="font-size: 15px;"> <a href="#">More info <i
                                class="fas fa-arrow-circle-right"></i></a></small>
                </div> --}}
            </div>
        </div>


        <div class="col-md-4">
            <div class="card card-2">
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-4">
                            <div class="text-left align-middle" style="font-size: 40px;">
                                {{ $count_proposal }}
                                <br><span style="font-size: 15px">Total Proposal</span>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="text-right">
                                <span class="align-top" style="opacity: 0.5">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer text-center" style="padding: 0px; background:black; opacity: 0.3">
                    <small class="text" style="font-size: 15px;"> <a href="#">More info <i
                                class="fas fa-arrow-circle-right"></i></a></small>
                </div> --}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-3">
                <div class="card-body" style="padding: 1rem;">
                    <div class="row">
                        <div class="col-4">
                            <div class="text-left align-middle" style="font-size: 40px;">
                                {{ $count_lpj }}
                                <br><span style="font-size: 15px">Total LPJ</span>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="text-right">
                                <span class="align-top" style="opacity: 0.5">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer text-center" style="padding: 0px; background:black; opacity: 0.3">
                    <small class="text" style="font-size: 15px;"> <a href="#">More info <i
                                class="fas fa-arrow-circle-right"></i></a></small>
                </div> --}}
            </div>
        </div>

        {{-- CARD KPI --}}
        <div class="row " style="margin-top: 1%; margin-left: 2px;">
            <div class="col-lg-6">
                <div class="card card-4" style="background-color:chocolate">
                    <div class="card-body" style="padding: 0.5rem;">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-left align-middle" style="font-size: 35px;">
                                    {{ $kpi1 }}
                                    <br><span style="font-size: 13px">LPJ yang disediakan tepat waktu</span>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-right">
                                    <span class="align-top" style="opacity: 0.5">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-footer text-center" style="padding: 0px; background:black; opacity: 0.3">
                        <small class="text" style="font-size: 15px;"> <a href="#">More info <i
                                    class="fas fa-arrow-circle-right"></i></a></small>
                    </div> --}}
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-4" style="background-color:chocolate">
                    <div class="card-body" style="padding: 0.5rem;">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-left align-middle" style="font-size: 35px;">
                                    {{ $totalKPI4}}
                                    <br><span style="font-size: 13px">Jadwal Kegiatan yang <br> sesuai</span>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="text-right">
                                    <span class="align-top" style="opacity: 0.5">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-footer text-center" style="padding: 0px; background:black; opacity: 0.3">
                        <small class="text" style="font-size: 15px;"> <a href="#">More info <i
                                    class="fas fa-arrow-circle-right"></i></a></small>
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- PIE CHART STATUS --}}

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4" style="margin-top: 2%;">
                    <div class="card-body">
                        <div id="pieChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4" style="margin-top: 2%;">
                    <div class="card-body">
                        <div id="pieChart2"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART PROPOSAL BIDANG DAN LPJ --}}
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="bidangProposal"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="bidangLPJ"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART TIMPEL PROPOSAL DAN LPJ --}}
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="timpelProposal"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="timpelLPJ"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
    <script>
        $("input").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD")
                .format(this.getAttribute("data-date-format"))
            )
        }).trigger("change")
    </script>

    {{-- PIE CHART STATUS PROPEL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var status_review = JSON.parse('<?php echo json_encode($status_review_ulang); ?>');
            var status_ditunda = JSON.parse('<?php echo json_encode($status_ditunda); ?>');
            var status_disetujui = JSON.parse('<?php echo json_encode($status_disetujui); ?>');
            var status_proposal = JSON.parse('<?php echo json_encode($status_proposal); ?>');
            var status_lpj = JSON.parse('<?php echo json_encode($status_lpj); ?>');
            var drilldown_ditunda = JSON.parse('<?php echo json_encode($drilldown_ditunda); ?>');
            var drilldown_review = JSON.parse('<?php echo json_encode($drilldown_review); ?>');
            var drilldown_disetujui = JSON.parse('<?php echo json_encode($drilldown_disetujui); ?>');
            var drilldown_proposal = JSON.parse('<?php echo json_encode($drilldown_proposal); ?>');
            var drilldown_lpj = JSON.parse('<?php echo json_encode($drilldown_lpj); ?>');
            const chart = Highcharts.chart('pieChart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Status Program Pelayanan'
                },
                subtitle: {
                    text: 'Klik kolom Status untuk melihat data Bidang'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true
                    },
                    point: {
                        valueSuffix: 'a'
                    }
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y:.f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.f}</b> of total<br/>'
                },

                series: [{
                    name: "Status",
                    colorByPoint: true,
                    data: [{
                            name: "Ditunda",
                            y: status_ditunda,
                            drilldown: "Ditunda"
                        },
                        {
                            name: "Review Ulang",
                            y: status_review,
                            drilldown: "Review Ulang"
                        },
                        {
                            name: "Disetujui",
                            y: status_disetujui,
                            drilldown: "Disetujui"
                        },
                        {
                            name: "Proposal",
                            y: status_proposal,
                            drilldown: "Proposal"
                        },
                        {
                            name: "LPJ",
                            y: status_lpj,
                            drilldown: "LPJ"
                        },
                    ]
                }],
                drilldown: {
                    series: [{
                            name: "Ditunda",
                            id: "Ditunda",
                            data: drilldown_ditunda
                        },
                        {
                            name: "Review Ulang",
                            id: "Review Ulang",
                            data: drilldown_review
                        },
                        {
                            name: "Disetujui",
                            id: "Disetujui",
                            data: drilldown_disetujui
                        },
                        {
                            name: "Proposal",
                            id: "Proposal",
                            data: drilldown_proposal
                        },
                        {
                            name: "LPJ",
                            id: "LPJ",
                            data: drilldown_lpj
                        },
                    ]
                }
            });

        });
    </script>

    {{-- PIE CHART KPI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var anggaran_surplus = JSON.parse('<?php echo json_encode($kpi2); ?>');
            var anggaran_defisit = JSON.parse('<?php echo json_encode($kpi3); ?>');

            const chart = Highcharts.chart('pieChart2', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Persentase Kelebihan dan Kekurangan Anggaran'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        colors: [
                            '#28B463',
                            '#E74C3C'
                        ],
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        },
                    }
                },
                series: [{
                    name: 'Persentase',
                    colorByPoint: true,
                    data: [{
                            name: 'Deviasi Positif',
                            y: anggaran_surplus
                        },
                        {
                            name: 'Deviasi Negatif',
                            y: anggaran_defisit
                        }
                    ]
                }]
            })
        });
    </script>

    {{-- CHART BIDANG PROPOSAL --}}
    <script>
        var data_bidang = JSON.parse('<?php echo json_encode($data_proposal_bidang['bidang']); ?>');
        var data_disetujui = JSON.parse('<?php echo json_encode($data_proposal_bidang['disetujui']); ?>');
        var data_tidakDisetujui = JSON.parse('<?php echo json_encode($data_proposal_bidang['tidakDisetujui']); ?>');

        document.addEventListener('DOMContentLoaded', function() {
            const chart = Highcharts.chart('bidangProposal', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Data Proposal per Bidang'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: data_bidang,
                    crosshair: true,
                    labels: {
                        style: {
                            color: '#000000',
                            fontSize: '13px'

                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'disetujui',
                        data: data_disetujui
                    },
                    {
                        name: 'belum disetujui',
                        data: data_tidakDisetujui
                    }
                ]
            });
        });
    </script>

    {{-- CHART BIDANG LPJ --}}
    <script>
        var data_bidang_lpj = JSON.parse('<?php echo json_encode($data_lpj_bidang['bidang']); ?>');
        var data_disetujui_lpj = JSON.parse('<?php echo json_encode($data_lpj_bidang['disetujui']); ?>');
        var data_tidakDisetujui_lpj = JSON.parse('<?php echo json_encode($data_lpj_bidang['tidakDisetujui']); ?>');

        document.addEventListener('DOMContentLoaded', function() {
            const chart = Highcharts.chart('bidangLPJ', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Data LPJ per Bidang'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: data_bidang_lpj,
                    crosshair: true,
                    labels: {
                        style: {
                            color: '#000000',
                            fontSize: '13px'

                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'disetujui',
                        data: data_disetujui_lpj
                    },
                    {
                        name: 'belum disetujui',
                        data: data_tidakDisetujui_lpj
                    }
                ]
            });
        });
    </script>

    {{-- CHART TIMPEL PROPOSAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var data_chart = JSON.parse('<?php echo json_encode($data_proposal_per_bidang); ?>');
            var data_drilldown = JSON.parse('<?php echo json_encode($data_timpel_per_bidang); ?>');
            const chart = Highcharts.chart('timpelProposal', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Data Proposal per TP'
                },
                subtitle: {
                    text: 'Klik kolom Bidang untuk melihat data Tim Pelayanan'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#000000',
                            fontSize: '12px'

                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:f}</b> of total<br/>'
                },

                series: [{
                    name: "Bidang",
                    colorByPoint: true,
                    data: data_chart
                }],
                drilldown: {
                    series: data_drilldown
                }
            });
        });
    </script>

    {{-- CHART TIMPEL LPJ --}}
    <script>
        var data_chart = JSON.parse('<?php echo json_encode($data_lpj_timpel); ?>');
        var data_drilldown = JSON.parse('<?php echo json_encode($data_timpel_lpj_per_bidang); ?>');

        document.addEventListener('DOMContentLoaded', function() {
            const chart = Highcharts.chart('timpelLPJ', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Data LPJ per TP'
                },
                subtitle: {
                    text: 'Klik kolom bidang untuk melihat data Tim Pelayanan'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#000000',
                            fontSize: '12px'

                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:f}</b> of total<br/>'
                },

                series: [{
                    name: "Bidang",
                    colorByPoint: true,
                    data: data_chart
                }],
                drilldown: {
                    series: data_drilldown
                }
            });
        });
    </script>
@endsection
