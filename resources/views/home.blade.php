@extends('layouts.app')

@section('content')
{{-- eyecatch --}}
<div class="eyecatch">
    <p class="catch-copy">あなたのアプリを<br>共有しよう</p>
</div>

{{-- アプリ一覧 --}}
<div class="container my-5">
    <h2 class="text-center">投稿一覧</h2>
    <div class="row">
        @if ($posts->count() === 0)
        <div class="col-10 mx-auto mt-5 text-center">
            <p class="mb-5">投稿されたアプリはありません</p>
        </div>
        @endif
        @foreach ($posts as $post)
        <div class="col-4">
            <div class="card my-5">
                @if ($post->image === null)
                <img src="/image/no-img.png" class="bd-placeholder-img card-img-top" height="200">
                @else
                <img src="{{ asset('/storage/image/'.$post->image) }}" class="bd-placeholder-img card-img-top"
                    height="200">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>

                    {{-- 詳細 --}}
                    <a href="{{ route('posts.show',['post' => $post->id]) }}" class="btn btn-primary">詳細へ</a>

                    {{-- いいね --}}
                    @if ($post->is_liked_by_auth_user())
                    <a href="{{ route('unlike', ['id' => $post->id]) }}" class="btn btn-success"><i
                            class="far fa-thumbs-up"></i> いいね！ <span>{{ $post->likes->count() }}</span></a>
                    @else
                    <a href="{{ route('like', ['id' => $post->id]) }}" class="btn btn-secondary"><i
                            class="far fa-thumbs-up"></i> いいね！ <span>{{ $post->likes->count() }}</span></a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-10 mx-auto text-center">
            {{ $posts->links('vendor.pagination.bootstrap-4') }}
        </div>
        <div class="col-10 mx-auto text-center">
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">アプリを投稿する</a>
        </div>
    </div>
</div>

@endsection