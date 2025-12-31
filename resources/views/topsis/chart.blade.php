@extends('layouts.app')

@section('title', 'Visualisasi Nilai Preferensi TOPSIS')

@section('content')
<div class="container-fluid py-4 topsis-dark">

    <div class="card border-0 shadow-sm">
        {{-- HEADER --}}
        <div class="card-header border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1 d-flex align-items-center page-title">
                        <span class="icon-circle">
                            <i class="fas fa-chart-bar text-primary"></i>
                        </span>
                        <span>Chart Nilai Preferensi TOPSIS</span>
                    </h5>
                    <small class="page-subtitle">
                        Visualisasi kedekatan alternatif terhadap solusi ideal (nilai 0–1).
                    </small>
                </div>

                <a href="{{ route('motor.topsis') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">
            <div style="height:420px;">
                <canvas id="rankingChart"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>

<script>
    const labels = @json($rankingCollection->pluck('nama_motor'));
    const values = @json($rankingCollection->pluck('nilai'));

    // warna bar: Top 1–3 beda
    const barColors = values.map((_, i) => {
        if (i === 0) return 'rgba(250, 204, 21, 0.9)';   // gold
        if (i === 1) return 'rgba(203, 213, 225, 0.9)'; // silver
        if (i === 2) return 'rgba(251, 146, 60, 0.9)';  // bronze
        return 'rgba(59, 130, 246, 0.7)';               // default
    });

    const borderColors = values.map((_, i) => {
        if (i === 0) return '#eab308';
        if (i === 1) return '#94a3b8';
        if (i === 2) return '#ea580c';
        return '#3b82f6';
    });

    const ctx = document.getElementById('rankingChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Preferensi TOPSIS',
                data: values,
                backgroundColor: barColors,
                borderColor: borderColors,
                borderWidth: 1.5,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 10 }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#020617',
                    titleColor: '#f8fafc',
                    bodyColor: '#e5e7eb',
                    borderColor: '#334155',
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => ` Nilai: ${ctx.raw.toFixed(4)}`
                    }
                },
                annotation: {
                    annotations: {
                        idealLine: {
                            type: 'line',
                            yMin: 1,
                            yMax: 1,
                            borderColor: '#22c55e',
                            borderWidth: 2,
                            borderDash: [6, 6],
                            label: {
                                enabled: true,
                                content: 'Solusi Ideal (1.0)',
                                position: 'end',
                                backgroundColor: '#22c55e',
                                color: '#022c22',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10 }
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    min: 0,
                    max: 1,
                    ticks: {
                        color: '#94a3b8',
                        stepSize: 0.1
                    },
                    grid: {
                        color: 'rgba(255,255,255,.06)'
                    }
                }
            }
        }
    });
</script>
@endsection
