@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid pt-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm mb-0">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0 font-weight-bold text-primary"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin</h3>
                    <span class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> {{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($stats['published_posts']) }}</h3>
                    <p>Artikel Dipublikasi</p>
                </div>
                <div class="icon"><i class="fas fa-newspaper"></i></div>
                <a href="{{ route('admin.posts.index') }}" class="small-box-footer">Kelola Artikel <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['today_visitors']) }}</h3>
                    <p>Pengunjung Hari Ini</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="#" class="small-box-footer">Bulan Ini: {{ number_format($stats['month_visitors']) }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['total_lecturers']) }}</h3>
                    <p>Dosen Aktif</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <a href="{{ route('admin.lecturers.index') }}" class="small-box-footer">Kelola Dosen <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($stats['unread_messages']) }}</h3>
                    <p>Pesan Belum Dibaca</p>
                </div>
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <a href="{{ route('admin.contact-messages.index') }}" class="small-box-footer">Lihat Pesan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Visitor Chart --}}
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Kunjungan 30 Hari Terakhir</h3>
                </div>
                <div class="card-body">
                    <canvas id="visitorChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="col-lg-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Aksi Cepat</h3>
                </div>
                <div class="card-body p-2">
                    @can('create posts')
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-block btn-flat btn-success mb-2">
                        <i class="fas fa-plus mr-2"></i> Tulis Artikel Baru
                    </a>
                    @endcan
                    @can('create announcements')
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-block btn-flat btn-warning mb-2">
                        <i class="fas fa-bullhorn mr-2"></i> Buat Pengumuman
                    </a>
                    @endcan
                    @can('create events')
                    <a href="{{ route('admin.events.create') }}" class="btn btn-block btn-flat btn-info mb-2">
                        <i class="fas fa-calendar-plus mr-2"></i> Tambah Agenda
                    </a>
                    @endcan
                    @can('upload media')
                    <a href="{{ route('admin.media.index') }}" class="btn btn-block btn-flat btn-secondary mb-2">
                        <i class="fas fa-upload mr-2"></i> Upload Media
                    </a>
                    @endcan
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-block btn-flat btn-outline-primary">
                        <i class="fas fa-eye mr-2"></i> Lihat Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Posts --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-newspaper mr-2"></i>Artikel Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-list"></i> Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <tbody>
                        @forelse($recentPosts as $post)
                            <tr>
                                <td class="pl-3">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-dark">{{ Str::limit($post->title, 40) }}</a>
                                    <br><small class="text-muted">{{ $post->user->name }} &bull; {{ $post->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-right pr-3">
                                    @if($post->status === 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($post->status === 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @else
                                        <span class="badge badge-warning">Archived</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-muted py-3">Belum ada artikel</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Popular Posts --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire mr-2"></i>Artikel Populer</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <tbody>
                        @forelse($popularPosts as $i => $post)
                            <tr>
                                <td class="pl-3">
                                    <span class="badge badge-primary mr-1">{{ $i + 1 }}</span>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-dark">
                                        {{ Str::limit($post->title, 40) }}
                                    </a>
                                </td>
                                <td class="text-right pr-3">
                                    <span class="text-muted"><i class="fas fa-eye"></i> {{ number_format($post->views) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Events & Recent Messages --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Agenda Mendatang</h3>
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingEvents as $event)
                        <div class="d-flex align-items-center border-bottom px-3 py-2">
                            <div class="text-center bg-primary text-white rounded p-2 mr-3" style="min-width:50px">
                                <div style="font-size:1.2rem;font-weight:bold">{{ $event->start_date->format('d') }}</div>
                                <div style="font-size:0.7rem">{{ $event->start_date->format('M') }}</div>
                            </div>
                            <div>
                                <a href="{{ route('admin.events.edit', $event) }}" class="text-dark font-weight-bold">{{ Str::limit($event->title, 40) }}</a>
                                <br><small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location ?? 'Lokasi TBD' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-3">Tidak ada agenda mendatang</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-envelope mr-2"></i>Pesan Masuk Terbaru</h3>
                    @if($unreadMessages > 0)
                        <div class="card-tools">
                            <span class="badge badge-danger">{{ $unreadMessages }} belum dibaca</span>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        @forelse($recentMessages as $msg)
                            <tr class="{{ !$msg->is_read ? 'font-weight-bold' : '' }}">
                                <td class="pl-3">
                                    <a href="{{ route('admin.contact-messages.show', $msg) }}" class="text-dark">
                                        {{ $msg->name }} <small class="text-muted">- {{ $msg->subject }}</small>
                                    </a>
                                    <br><small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-right pr-3">
                                    @if(!$msg->is_read)
                                        <span class="badge badge-warning">Baru</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-muted py-3">Belum ada pesan</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
.small-box .icon { font-size: 70px; }
.small-box:hover .icon { font-size: 80px; }
</style>
@stop

@section('js')
<script>
// Visitor Chart
const ctx = document.getElementById('visitorChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pengunjung',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(60,141,188,0.2)',
            borderColor: 'rgba(60,141,188,1)',
            borderWidth: 2,
            pointRadius: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } },
            x: { ticks: { maxRotation: 45 } }
        }
    }
});

// Toastr flash messages
@if(session('success'))
    toastr.success("{{ session('success') }}");
@endif
@if(session('error'))
    toastr.error("{{ session('error') }}");
@endif
</script>
@stop
