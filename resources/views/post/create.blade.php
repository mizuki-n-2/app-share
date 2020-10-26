@extends('layouts.app')

@section('content')

{{-- 作成 --}}
<div class="container my-5">
  <h2 class="text-center mb-5">投稿作成</h2>

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label for="title" class="font-weight-bold">タイトル</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="form-group">
      <label for="image" class="font-weight-bold">画像 <span class="text-danger">※2MBまで</span></label>
      <input type="file" class="form-control-file" id="image" name="image">
    </div>
    
    <div class="form-group">
      <label for="url" class="font-weight-bold">URL(Githubなど)</label>
      <input type="text" class="form-control" id="url" name="url" placeholder="公開したいURLを書いてください">
    </div>

    <div class="form-group">
      <label for="content" class="font-weight-bold">内容</label>
      <textarea class="form-control" id="content" name="content" rows="10" placeholder="このアプリの内容や注目ポイントなどを自由に書いてください" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block">投稿する</button>

  </form>

</div>

@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection