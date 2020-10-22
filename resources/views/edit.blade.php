@extends('layouts.app')

@section('content')

{{-- プロフィール編集 --}}
<div class="container my-5">
  <h2 class="text-center mb-5">プロフィール編集</h2>
  <a href="{{ url()->previous() }}">＜もどる</a>

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div class="form-group">
      <label for="name" class="font-weight-bold">ユーザー名 <span class="text-white bg-danger">必須</span></label>
      <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
    </div>

    <div class="form-group">
      <label for="image" class="font-weight-bold">プロフィール画像</label>
      <input type="file" class="form-control-file" id="image" name="image">
    </div>

    <div class="form-group">
      <label for="profile" class="font-weight-bold">プロフィール</label>
      <textarea class="form-control" id="profile" name="profile">{{ $user->profile }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block">編集する</button>

  </form>

</div>

@endsection