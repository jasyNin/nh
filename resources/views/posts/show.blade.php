@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container posts-show" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-9">
            <div class="post-card">
            <div class="card border-0">
                <div class="card-body p-4">
                        <x-post-card :post="$post" :hideType="true" :isShowPage="true" />
                        <div class="mt-4">
                            <x-comments-section :post="$post" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .posts-show .post-card .card {
        margin-bottom: 0;
    }
    .posts-show .post-card .card-body {
        padding-bottom: 0 !important;
    }
    .posts-show .comments-section {
        margin-top: 0 !important;
        padding-top: 0;
    }
    .posts-show .comment-form textarea {
        width: 100%;
        min-height: 60px;
    }
    .posts-show .comment-form .position-relative {
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/post.js') }}"></script>
@endpush

<x-modals :posts="[$post]" />
@endsection