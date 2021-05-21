@extends('layouts.app')

@section('content')
<div class="container my-5">
  {{-- ユーザー情報 --}}
  <div class="d-md-flex justify-content-center align-items-center">
    <div class="mx-5">
      @if ($user->image === null)
      <img src="/image/default.png" class="profile-img d-block mx-auto">
      @else
      <img src="{{ $user->image }}" class="profile-img d-block mx-auto">
      @endif
    </div>
    <div class="mx-5 text-md-left text-center">
      <p class="user-name">{{ $user->name }}</p>
      <p>{!! nl2br(e($user->profile)) !!}</p>
      @if ($user->id === Auth::id())
      <a href="{{ route('edit', ['id' => $user->id]) }}" class="btn btn-outline-secondary"><i
          class="fas fa-user-edit"></i> プロフィール編集</a>
      @else
      @if ($user->is_followed_by_auth_user())
      <a href="{{ route('unfollow', ['id' => $user->id]) }}" class="btn btn-success">フォロー中</a>
      @else
      <a href="{{ route('follow', ['id' => $user->id]) }}" class="btn btn-outline-success">フォローする</a>
      @endif
      @endif
    </div>
  </div>

  {{-- ナビ --}}
  <ul class="nav nav-tabs nav-fill mt-5" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="post-tab" data-toggle="tab" href="#post" role="tab" aria-controls="post"
        aria-selected="true">投稿 <span>{{ count($posts) }}</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="like-tab" data-toggle="tab" href="#like" role="tab" aria-controls="like"
        aria-selected="false">いいねした投稿 <span>{{ count($like_posts) }}</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="follow-tab" data-toggle="tab" href="#follow" role="tab" aria-controls="follow"
        aria-selected="false">フォロー <span>{{ count($follow_users) }}</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="follower-tab" data-toggle="tab" href="#follower" role="tab" aria-controls="follower"
        aria-selected="false">フォロワー <span>{{ count($followed_users) }}</span></a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">

    {{-- 投稿一覧 --}}
    <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
      <div class="row">

        {{-- 投稿がなかった場合 --}}
        @if ($posts->count() === 0)
        <div class="col-12 mx-auto mt-5 text-center">
          <p class="mb-5">投稿したアプリはありません</p>
          @if ($user->id === Auth::id())
          <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">アプリを投稿する</a>
          @endif
        </div>
        @endif

        {{-- 投稿があった場合 --}}
        @foreach ($posts as $post)
        <div class="col-lg-4 col-md-6">

          {{-- カード --}}
          <div class="card mt-5">
            @if ($post->image === null)
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
                <a href="{{ route('unlike', ['id' => $post->id]) }}" class="btn btn-success"><i
                    class="far fa-thumbs-up"></i>
                  <span>{{ $post->likes->count() }}</span></a>
                @else
                <a href="{{ route('like', ['id' => $post->id]) }}" class="btn btn-secondary"><i
                    class="far fa-thumbs-up"></i>
                  <span>{{ $post->likes->count() }}</span></a>
                @endif

              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

    {{-- いいねした投稿 --}}
    <div class="tab-pane fade" id="like" role="tabpanel" aria-labelledby="like-tab">
      <div class="row">

        {{-- いいねした投稿がなかった場合 --}}
        @if (count($like_posts) === 0)
        <div class="col-12 mx-auto mt-5 text-center">
          <p class="mb-5">いいねした投稿はありません</p>
        </div>
        @endif

        {{-- いいねした投稿があった場合 --}}
        @foreach ($like_posts as $like_post)
        <div class="col-lg-4 col-md-6">

          {{-- カード --}}
          <div class="card mt-5">
            @if ($like_post->image === null)
            <img src="/image/no-img.png" class="bd-placeholder-img card-img-top" height="200">
            @else
            <img src="{{ $like_post->image }}" class="bd-placeholder-img card-img-top"
              height="200">
            @endif
            <div class="card-body">
              <h5 class="card-title">{{ $like_post->title }}</h5>
              <div class="d-flex justify-content-between">

                {{-- 詳細 --}}
                <a href="{{ route('posts.show',['post' => $like_post->id]) }}" class="btn btn-outline-info">詳細へ</a>

                {{-- いいね --}}
                @if ($like_post->is_liked_by_auth_user())
                <a href="{{ route('unlike', ['id' => $like_post->id]) }}" class="btn btn-success"><i
                    class="far fa-thumbs-up"></i>
                  <span>{{ $like_post->likes->count() }}</span></a>
                @else
                <a href="{{ route('like', ['id' => $like_post->id]) }}" class="btn btn-secondary"><i
                    class="far fa-thumbs-up"></i>
                  <span>{{ $like_post->likes->count() }}</span></a>
                @endif

              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

    {{-- フォロー --}}
    <div class="tab-pane fade" id="follow" role="tabpanel" aria-labelledby="follow-tab">
      <div class="row">

        {{-- フォローがいなかった場合 --}}
        @if (count($follow_users) === 0)
        <div class="col-12 mx-auto mt-5 text-center">
          <p class="mb-5">フォローしているユーザーはいません</p>
        </div>
        @endif

        {{-- フォローがいた場合 --}}
        @foreach ($follow_users as $follow_user)
        <div class="col-12">

          {{-- カード --}}
          <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                @if ($follow_user->image === null)
                <img src="/image/default.png" class="follow-img">
                @else
                <img src="{{ $follow_user->image }}" class="follow-img">
                @endif
                <p class="ml-4 follow-name my-auto">
                  <a href="{{ route('profile', ['id' => $follow_user->id]) }}">{{ $follow_user->name }}</a>
                </p>
              </div>

              @if($follow_user->id !== Auth::id())
              <div>
                @if ($follow_user->is_followed_by_auth_user())
                <a href="{{ route('unfollow', ['id' => $follow_user->id]) }}" class="btn btn-success">フォロー中</a>
                @else
                <a href="{{ route('follow', ['id' => $follow_user->id]) }}" class="btn btn-outline-success">フォローする</a>
                @endif
              </div>
              @endif

            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

    {{-- フォロワー --}}
    <div class="tab-pane fade" id="follower" role="tabpanel" aria-labelledby="follower-tab">
      <div class="row">

        {{-- フォロワーがいなかった場合 --}}
        @if (count($followed_users) === 0)
        <div class="col-12 mx-auto mt-5 text-center">
          <p class="mb-5">フォローされたユーザーはいません</p>
        </div>
        @endif

        {{-- フォロワーがいた場合 --}}
        @foreach ($followed_users as $followed_user)
        <div class="col-12">

          {{-- カード --}}
          <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                @if ($followed_user->image === null)
                <img src="/image/default.png" class="follow-img">
                @else
                <img src="{{ $followed_user->image }}" class="follow-img">
                @endif
                <p class="ml-4 follow-name my-auto">
                  <a href="{{ route('profile', ['id' => $followed_user->id]) }}">{{ $followed_user->name }}</a>
                </p>
              </div>

              @if($followed_user->id !== Auth::id())
              <div>
                @if ($followed_user->is_followed_by_auth_user())
                <a href="{{ route('unfollow', ['id' => $followed_user->id]) }}" class="btn btn-success">フォロー中</a>
                @else
                <a href="{{ route('follow', ['id' => $followed_user->id]) }}" class="btn btn-outline-success">フォローする</a>
                @endif
              </div>
              @endif
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>

  </div>

</div>
@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection