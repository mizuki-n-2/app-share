@extends('layouts.app')

@section('content')
<div class="container my-5">
  {{-- ユーザー情報 --}}
  <div class="row align-items-center">
    <div class="col-3">
      @if ($user->image === null)
      <img src="/image/default.png" class="profile-img">
      @else
      <img src="{{ asset('/storage/image/'.$user->image) }}" class="profile-img">
      @endif
    </div>
    <div class="col-5">
      <p class="user-name">{{ $user->name }}</p>
      <p>{!! nl2br(e($user->profile)) !!}</p>
      <a href="{{ route('edit') }}">プロフィール編集</a>
    </div>
  </div>

  {{-- ナビ --}}
  <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="post-tab" data-toggle="tab" href="#post" role="tab" aria-controls="post"
        aria-selected="true">投稿一覧</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="like-tab" data-toggle="tab" href="#like" role="tab" aria-controls="profile"
        aria-selected="false">いいねした投稿</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    {{-- 投稿一覧 --}}
    <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
      <div class="container">
        <div class="row">
          @if ($posts->count() === 0)
          <div class="col-12 mx-auto mt-5 text-center">
            <p class="mb-5">投稿したアプリはありません</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">アプリを投稿する</a>
          </div>
          @endif

          @foreach ($posts as $post)
          <div class="col-4">
            <div class="card mt-5">
              @if ($post->image === null)
              <img src="/image/no-img.png" class="bd-placeholder-img card-img-top" height="200">
              @else
              <img src="{{ asset('/storage/image/'.$post->image) }}" class="bd-placeholder-img card-img-top"
                height="200">
              @endif
              <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <div class="d-flex justify-content-between">
                  <a href="{{ route('posts.show',['post' => $post->id]) }}" class="btn btn-primary">詳細へ</a>
                  @if ($post->is_liked_by_auth_user())
                  <a href="{{ route('unlike', ['id' => $post->id]) }}" class="btn btn-success"><i class="far fa-thumbs-up"></i> いいね！
                    <span>{{ $post->likes->count() }}</span></a>
                  @else
                  <a href="{{ route('like', ['id' => $post->id]) }}" class="btn btn-danger"><i class="far fa-thumbs-up"></i> いいね！
                    <span>{{ $post->likes->count() }}</span></a>
                  @endif
                  <form action="{{ route('posts.destroy',['post' => $post->id]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">削除</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          {{ $posts->links() }}
        </div>
      </div>
    </div>

    {{-- いいねした投稿 --}}
    <div class="tab-pane fade" id="like" role="tabpanel" aria-labelledby="like-tab">
      <div class="container">
        <div class="row">
          @if ($favorite_posts->count() === 0)
          <div class="col-12 mx-auto mt-5 text-center">
            <p class="mb-5">いいねした投稿はありません</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">アプリを投稿する</a>
          </div>
          @endif

          @foreach ($favorite_posts as $favorite_post)
          <div class="col-4">
            <div class="card mt-5">
              @if ($favorite_post->image === null)
              <img src="/image/no-img.png" class="bd-placeholder-img card-img-top" height="200">
              @else
              <img src="{{ asset('/storage/image/'.$favorite_post->image) }}" class="bd-placeholder-img card-img-top"
                height="200">
              @endif
              <div class="card-body">
                <h5 class="card-title">{{ $favorite_post->title }}</h5>
                <div class="d-flex justify-content-between">
                  <a href="{{ route('posts.show',['post' => $favorite_post->id]) }}" class="btn btn-primary">詳細へ</a>
                  @if ($favorite_post->is_liked_by_auth_user())
                  <a href="{{ route('unlike', ['id' => $favorite_post->id]) }}" class="btn btn-success"><i class="far fa-thumbs-up"></i> いいね！
                    <span>{{ $favorite_post->likes->count() }}</span></a>
                  @else
                  <a href="{{ route('like', ['id' => $favorite_post->id]) }}" class="btn btn-danger"><i class="far fa-thumbs-up"></i> いいね！
                    <span>{{ $favorite_post->likes->count() }}</span></a>
                  @endif
                  <form action="{{ route('posts.destroy',['post' => $favorite_post->id]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">削除</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          {{ $favorite_posts->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection