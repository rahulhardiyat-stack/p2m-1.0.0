@extends('adminlte::page')
@section('title', 'Tambah Artikel')
@section('content_header')
<div class="d-flex justify-content-between"><h1>Tambah Artikel</h1><a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
@stop
@section('content')
@php $categories = \App\Models\Category::all(); $tags = \App\Models\Tag::all(); @endphp
@include('admin.posts.form')
@stop
