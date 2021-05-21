@extends('layouts.app')

@section('content')

{{-- 投稿詳細 --}}
<div class="container">

  <div class="row">

    <div class="col-10 mx-auto">
      @if ($post->image !== null)
      <img src="{{ $post->image }}" class="show-img">
      @else
      <img src="/image/no-img.png" class="show-img">
      @endif
    </div>


    <div class="col-10 mx-auto my-4">
      <h2 class="font-weight-bold text-center">{{ $post->title }}</h2>
    </div>

    <div class="col-10 mx-auto">
      <h5>○内容</h5>
      <p class="ml-4">{!! nl2br(e($post->content)) !!}</p>
    </div>

    <div class="col-10 mx-auto">
      <h5>○URL</h5>
      @if ($post->url !== null)
      <p class="ml-4"><a href="{{ $post->url }}">{{ $post->url }}</a></p>
      @else
      <p class="ml-4">URLはありません</p>
      @endif
    </div>

    <div class="col-10 mx-auto mt-3">
      <p>投稿者：<a href="{{ route('profile', ['id' => $post->user_id]) }}">{{ $post->user->name }}</a></p>
      <p>投稿日：{{ $post->created_at->format('Y/m/d') }}</p>
    </div>

    <div class="col-10 mx-auto my-3">
      <div class="d-flex justify-content-end">
        {{-- 編集 --}}
        @if ($post->user_id === Auth::id())
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
          <i class="fas fa-edit"></i>
        </a>
        @endif

        {{-- 削除 --}}
        @if ($post->user_id == Auth::id())
        <form action="{{ route('posts.destroy',['post' => $post->id]) }}" method="POST">
          @method('DELETE')
          @csrf
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i>
          </button>
        </form>
        @endif

        {{-- いいね --}}
        @if ($post->is_liked_by_auth_user())
        <a href="{{ route('unlike', ['id' => $post->id]) }}" class="btn btn-success"><i class="far fa-thumbs-up"></i>
          <span>{{ $post->likes->count() }}</span></a>
        @else
        <a href="{{ route('like', ['id' => $post->id]) }}" class="btn btn-secondary"><i class="far fa-thumbs-up"></i>
          <span>{{ $post->likes->count() }}</span></a>
        @endif

        {{-- コメント --}}
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModalCenter">
          <i class="far fa-comment"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">コメント作成</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form action="{{ route('comment.store', ['id' => $post->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                  <textarea class="form-control" name="comment" required></textarea>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">コメントする</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mx-auto mt-3 validation-msg">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    {{-- コメント一覧 --}}
    <div class="col-10 mx-auto my-3">
      <div class="card">
        <div class="card-header text-center">
          コメント <span>{{ $comments->count() }}</span>
        </div>
        <ul class="list-group list-group-flush">
          @if ($comments->count() === 0)
          <li class="list-group-item">
            <p class="text-center mt-3">コメントはありません</p>
          </li>
          @else
          @foreach ($comments as $comment)
          <li class="list-group-item">
            <a href="{{ route('profile', ['id' => $comment->user_id]) }}">
              {{ $comment->user->name }}
            </a>
            <p class="ml-4">{!! nl2br(e($comment->comment)) !!}</p>
          </li>
          @endforeach
          @endif
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection