@extends('adminlte::page')

@section('title', 'Detail Pesan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-envelope-open-text me-2"></i>Detail Pesan</h1>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center text-primary border" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <h3 class="profile-username text-center">{{ $contactMessage->name }}</h3>
                    <p class="text-muted text-center mb-1"><i class="fas fa-envelope me-1"></i> {{ $contactMessage->email }}</p>
                    
                    @if($contactMessage->phone)
                        <p class="text-muted text-center"><i class="fas fa-phone me-1"></i> {{ $contactMessage->phone }}</p>
                    @endif
                    
                    <ul class="list-group list-group-unbordered mb-3 mt-4">
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right badge {{ $contactMessage->is_read ? 'badge-success' : 'badge-warning' }}">
                                {{ $contactMessage->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Waktu</b> <span class="float-right">{{ $contactMessage->created_at ? $contactMessage->created_at->format('d M Y H:i') : '-' }}</span>
                        </li>
                    </ul>
                    
                    <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block"><i class="fas fa-trash me-1"></i> Hapus Pesan</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-header pb-0 border-bottom-0">
                    <h3 class="font-weight-bold">{{ $contactMessage->subject }}</h3>
                </div>
                <div class="card-body">
                    <div class="mailbox-read-message p-3 bg-light rounded" style="min-height: 200px;">
                        {!! nl2br(e($contactMessage->message)) !!}
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="mailto:{{ $contactMessage->email }}?subject=RE: {{ $contactMessage->subject }}" class="btn btn-primary">
                        <i class="fas fa-reply me-1"></i> Balas via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
