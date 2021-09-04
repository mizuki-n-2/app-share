@extends('layouts.app')

@section('content')

{{-- プロフィール編集 --}}
<div class="container my-5">
  <h2 class="text-center mb-5">プロフィール編集</h2>

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
      <label for="name" class="font-weight-bold">ユーザー名</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
    </div>

    <div class="form-group">
      <label for="image" class="font-weight-bold">プロフィール画像 <span class="text-danger">※2MBまで</span></label>
      <input type="file" class="form-control-file" name="image" id="image">
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" value="checked" id="default" name="default" checked>
      <label class="form-check-label text-primary" for="default">
        プロフィール画像は変更しない
      </label>
    </div>

    <div class="form-group">
      <label for="profile" class="font-weight-bold">プロフィール</label>
      <textarea class="form-control" id="profile" name="profile">{{ $user->profile }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary btn-lg btn-block">編集する</button>

  </form>

</div>

@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection