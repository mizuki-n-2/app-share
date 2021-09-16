@extends('layouts.app')

@section('content')

{{-- アプリ一覧 --}}
<div class="container my-5">
  <h2 class="text-center">投稿一覧</h2>
  <div class="btn-group" role="group">
    <a href="{{ route('posts.index', ['orderBy' => 'new']) }}" class="btn btn-primary">新着順</a>
    <a href="{{ route('posts.index', ['orderBy' => 'popular']) }}" class="btn btn-primary">人気順</a>
  </div>
  <div class="row">
    @if ($posts->count() === 0)
    <div class="col-10 mx-auto mt-5 text-center">
      <p class="mb-5">投稿されたアプリはありません</p>
      <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">アプリを投稿する</a>
    </div>
    @endif
    @foreach ($posts as $post)
    <div class="col-lg-4 col-md-6">
      <div class="card my-4">
        @if (empty($post->image))
        <img src="/image/no-img.png" class="bd-placeholder-img card-img-top" height="200">
        @else
        <img src="{{ $post->image }}" class="bd-placeholder-img card-img-top" height="200">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ $post->title }}</h5>
          <div class="d-flex justify-content-between">
            {{-- 詳細 --}}
            <a href="{{ route('posts.show',['post' => $post->id]) }}" class="btn btn-outline-info">詳細へ</a>
  
            {{-- いいね --}}
            @if ($post->is_liked_by_auth_user())
            <a href="{{ route('unlike', ['id' => $post->id]) }}" class="btn btn-success">
              <i class="far fa-thumbs-up"></i>
              <span>{{ $post->likes->count() }}</span>
            </a>
            @else
            <a href="{{ route('like', ['id' => $post->id]) }}" class="btn btn-secondary">
              <i class="far fa-thumbs-up"></i>
              <span>{{ $post->likes->count() }}</span>
            </a>
            @endif
          </div>

        </div>
      </div>
    </div>
    @endforeach
    <div class="col-10 mx-auto text-center">
      {{ $posts->links('vendor.pagination.bootstrap-4') }}
    </div>
  </div>
</div>

@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection