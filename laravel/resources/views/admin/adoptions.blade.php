@extends('layouts.app')

@section('title', 'Adoptions')

@php
    $filters = [
        null => 'All',
        'pending' => 'Awaiting approval',
        'reserved' => 'Reserved',
        'completed' => 'Adopted',
        'cancelled' => 'Cancelled',
    ];
    $chartTotal = array_sum($chart['data'] ?? []);
@endphp

@section('content')
    <div class="container page">
        @include('admin._nav')

        <header class="page-header">
            <div>
                <h1 class="page-header__title">Adoptions</h1>
                <p class="page-header__subtitle">Approve or cancel requests and reservations.</p>
            </div>
        </header>

        <nav class="admin-filter" aria-label="Filter by status">
            <div class="segmented segmented--scroll">
                @foreach ($filters as $value => $label)
                    @php $current = ($status ?? null) === ($value === '' ? null : $value); @endphp
                    <a class="segmented__btn {{ $current ? 'active' : '' }}"
                        href="{{ route('admin.adoptions', $value ? ['status' => $value] : []) }}"
                        @if ($current) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </div>
        </nav>

        @if ($adoptions->isEmpty())
            <div class="empty-state">
                <i class="bi bi-clipboard-check empty-state__icon" aria-hidden="true"></i>
                @if ($status)
                    <h2 class="empty-state__title">No adoptions with this status</h2>
                    <p class="empty-state__text">Try another filter or show all adoptions.</p>
                    <a class="btn btn-quiet" href="{{ route('admin.adoptions') }}">Show all</a>
                @else
                    <h2 class="empty-state__title">No adoptions yet</h2>
                    <p class="empty-state__text">Requests and reservations from customers will appear here.</p>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="table table-hover" id="adoptions-table">
                    <thead>
                        <tr>
                            <th scope="col">Pet</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Requested</th>
                            <th scope="col">Status</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adoptions as $adoption)
                            @php
                                $pet = $adoption->pet;
                                $customer = $adoption->user;
                            @endphp
                            <tr data-status-url="{{ route('admin.adoptions.updateStatus', $adoption) }}">
                                <td>
                                    @if ($pet)
                                        <div class="cell-pet">
                                            <img class="cell-thumb" src="{{ $pet->photo_url }}" alt="" loading="lazy">
                                            <a href="{{ route('pets.show', $pet) }}">{{ $pet->name }}</a>
                                        </div>
                                    @else
                                        <span class="cell-muted">Deleted pet</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($customer)
                                        <div>{{ $customer->name }} {{ $customer->last_name }}</div>
                                        <div class="cell-muted">{{ $customer->email }}</div>
                                    @else
                                        <span class="cell-muted">Deleted user</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    @if ($adoption->created_at)
                                        <time datetime="{{ $adoption->created_at->toIso8601String() }}">{{ $adoption->created_at->format('j M Y') }}</time>
                                    @else
                                        <span class="cell-muted">–</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-dot" data-status="{{ $adoption->status }}">{{ $adoption->status_label }}</span>
                                    @if ($adoption->status === 'reserved' && $adoption->expires_at)
                                        <div class="cell-muted" data-status-note>
                                            {{ $adoption->expires_at->isPast() ? 'Expired' : 'Expires' }}
                                            <time datetime="{{ $adoption->expires_at->toIso8601String() }}"
                                                title="{{ $adoption->expires_at->format('j M Y, H:i') }}">{{ $adoption->expires_at->diffForHumans() }}</time>
                                        </div>
                                    @elseif ($adoption->status === 'completed' && $adoption->adoption_date)
                                        <div class="cell-muted">on {{ $adoption->adoption_date->format('j M Y') }}</div>
                                    @endif
                                </td>
                                <td class="table-actions">
                                    @if ($adoption->isActive())
                                        <button type="button" class="btn btn-quiet btn-sm" data-adoption-status="completed">Approve</button>
                                        <button type="button" class="btn btn-quiet btn-sm" data-adoption-status="cancelled">Cancel</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $adoptions->links('layouts.partials.pagination') }}
        @endif

        <section class="panel admin-chart" aria-labelledby="chart-title">
            <h2 class="panel__title" id="chart-title">Adoptions per month</h2>
            @if ($chartTotal > 0)
                <p class="cell-muted mb-3">Completed adoptions over the last 12 months: {{ $chartTotal }}</p>
                <div class="chart-box">
                    <canvas id="adoptions-chart" aria-hidden="true"></canvas>
                </div>
                <table class="visually-hidden">
                    <caption>Completed adoptions per month</caption>
                    <thead><tr><th scope="col">Month</th><th scope="col">Adoptions</th></tr></thead>
                    <tbody>
                        @foreach ($chart['labels'] as $i => $label)
                            <tr><th scope="row">{{ $label }}</th><td>{{ $chart['data'][$i] ?? 0 }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="cell-muted mb-0">No completed adoptions in the last 12 months.</p>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    @if ($chartTotal > 0)
        <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var PS = window.PetShelter;

            /* ---- Approve / cancel (JSON) ---- */
            var table = document.getElementById('adoptions-table');
            if (table && PS) {
                table.addEventListener('click', function (e) {
                    var btn = e.target.closest('[data-adoption-status]');
                    if (!btn || btn.disabled) return;
                    var row = btn.closest('tr');
                    var status = btn.getAttribute('data-adoption-status');
                    if (status === 'cancelled' && !window.confirm('Cancel this adoption request?')) return;

                    var buttons = row.querySelectorAll('[data-adoption-status]');
                    var setBusy = function (busy) {
                        buttons.forEach(function (b) { b.disabled = busy; });
                        btn.setAttribute('aria-busy', busy ? 'true' : 'false');
                    };
                    setBusy(true);

                    PS.fetchJson(row.getAttribute('data-status-url'), { body: { status: status } })
                        .then(function (res) {
                            var data = res.data || {};
                            if (res.ok && data.success) {
                                PS.setStatus(row.querySelector('.status-dot'), data.status, data.label);
                                var note = row.querySelector('[data-status-note]');
                                if (note) note.remove();
                                buttons.forEach(function (b) { b.remove(); });
                                showToast(data.status === 'completed' ? 'Adoption approved.' : 'Adoption cancelled.', 'success');
                            } else {
                                setBusy(false);
                                showToast(data.message || 'Could not update the status. Please try again.', 'error');
                            }
                        })
                        .catch(function () {
                            setBusy(false);
                            showToast('Network error. Please try again.', 'error');
                        });
                });
            }

            /* ---- Chart ---- */
            var canvas = document.getElementById('adoptions-chart');
            if (!canvas || !window.Chart) return;

            var chartData = @json($chart);
            var html = document.documentElement;
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function token(name, fallback) {
                var value = getComputedStyle(html).getPropertyValue(name).trim();
                return value || fallback;
            }
            function colors() {
                return {
                    bar: token('--ps-primary', token('--bs-primary', '#2F5D50')),
                    barHover: token('--ps-primary-hover', token('--bs-primary', '#2F5D50')),
                    grid: token('--ps-border', 'rgba(0,0,0,.1)'),
                    text: token('--ps-muted', '#5E655F'),
                    tipBg: token('--ps-text', '#1E2420'),
                    tipText: token('--ps-bg', '#FFFFFF')
                };
            }

            var c = colors();
            Chart.defaults.font.family = token('--ps-font-body', Chart.defaults.font.family);
            Chart.defaults.font.size = 13;

            var chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Adoptions',
                        data: chartData.data,
                        backgroundColor: c.bar,
                        hoverBackgroundColor: c.barHover,
                        borderRadius: 4,
                        borderSkipped: 'start',
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: reduceMotion ? false : { duration: 400 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            displayColors: false,
                            backgroundColor: c.tipBg,
                            titleColor: c.tipText,
                            bodyColor: c.tipText,
                            callbacks: {
                                label: function (ctx) {
                                    var n = ctx.parsed.y;
                                    return n + (n === 1 ? ' adoption' : ' adoptions');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { color: c.grid },
                            ticks: { color: c.text, maxRotation: 0, autoSkip: true }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: c.grid },
                            border: { display: false },
                            ticks: { color: c.text, precision: 0 }
                        }
                    }
                }
            });

            // Re-colour when the theme changes (app.js sets data-bs-theme on <html>).
            new MutationObserver(function () {
                var n = colors();
                var ds = chart.data.datasets[0];
                ds.backgroundColor = n.bar;
                ds.hoverBackgroundColor = n.barHover;
                chart.options.scales.x.border.color = n.grid;
                chart.options.scales.x.ticks.color = n.text;
                chart.options.scales.y.grid.color = n.grid;
                chart.options.scales.y.ticks.color = n.text;
                chart.options.plugins.tooltip.backgroundColor = n.tipBg;
                chart.options.plugins.tooltip.titleColor = n.tipText;
                chart.options.plugins.tooltip.bodyColor = n.tipText;
                chart.update('none');
            }).observe(html, { attributes: true, attributeFilter: ['data-bs-theme'] });
        });
    </script>
@endpush
