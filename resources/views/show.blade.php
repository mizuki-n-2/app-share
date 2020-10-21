@extends('layouts.app')

@section('content')

{{-- 投稿作成 --}}
<div class="container my-5">
  
  <div class="row">
    <div class="col-10 mx-auto">
      <a href="{{ url()->previous() }}">＜もどる</a>
      <h2 class="font-weight-bold text-center">{{ $post->title }}</h2>
    </div>
        
    <div class="col-10 mx-auto mt-5">
      <h4>内容</h4>
      <p>{!! nl2br(e($post->content)) !!}</p>
    </div>
    <div class="col-10 mx-auto">
      <h4>URL</h4>
      @if ($post->url !== null)
      <p><a href="{{ $post->url }}">{{ $post->url }}</a></p>     
      @else
      <p>URLはありません</p>
      @endif
    </div>
    <div class="col-10 mx-auto mt-3">
      <p>投稿者：<a href="">{{ $post->user->name }}</a></p>
      <p>投稿日：{{ $post->created_at->format('Y/m/d') }}</p>
    </div>
    @if ($post->image !== null)
    <div class="col-10 mx-auto">
      <img src="{{ asset('/storage/image/'.$post->image) }}" width="100%">
    </div>
    @endif

  </div> 
</div>

@endsection