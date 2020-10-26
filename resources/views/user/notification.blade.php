@extends('layouts.app')

@section('content')
<div class="container my-5">
  <h2 class="text-center mb-5">通知一覧</h2>
  <div class="row">

    {{-- 通知がなかった場合 --}}
    @if (count($notifications) === 0)
    <div class="col-12 mx-auto mt-5 text-center">
      <p>通知はありません</p>
    </div>
    @endif

    {{-- 通知があった場合 --}}
    <div class="col-12">

      {{-- カード --}}
      @foreach ($notifications as $notification)
      <div class="card">
        <ul class="list-group list-group-flush">
          @if ($notification->like_post_title === null && $notification->comment_post_title === null)
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <span>{{ $notification->by_user_name }}にフォローされました</span>
            <form action="{{ route('notification.delete', ['id' => $notification->id]) }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-primary">OK</button>
            </form>
          </li>
          @elseif($notification->like_post_title !== null)
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <span>{{ $notification->by_user_name }}が{{ $notification->like_post_title }}にいいねしました</span>
            <form action="{{ route('notification.delete', ['id' => $notification->id]) }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-primary">OK</button>
            </form>
          </li>
          @else
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <span>{{ $notification->by_user_name }}が{{ $notification->comment_post_title }}にコメントしました</span>
            <form action="{{ route('notification.delete', ['id' => $notification->id]) }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-primary">OK</button>
            </form>
          </li>
          @endif
        </ul>
      </div>
      @endforeach
    </div>

  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/main.js') }}"></script>
@endsection