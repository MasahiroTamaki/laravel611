@php
  $title = __('User') . ': ' . $user->name;
@endphp
@extends('layouts.my')
@section('content')
<div class="container">
  <h1>{{ $title }}</h1>

  {{-- 編集・削除ボタン --}}
  <div>
    <a href="{{ url('user/'.$user->id.'edit') }}" class="btn btn-primary">
      {{ __('Edit') }}
    </a>
    {{-- 削除ボタンはコンポーネントを呼び出す --}}
    @component('components.btn-del')
      @slot('title', 'users') <!-- @slot(変数名, パラメータ) -->
      @slot('id, $user->id')
    @endcomponent
  </div>

  {{-- ユーザー1件の情報 --}}
  <dl class="row">
    <dt class="col-md-2">{{ __('ID') }}</dt>
    <dd class="col-md-10">{{ $user->id }}</dd>
    <dt class="col-md-2">{{ __('Name') }}</dt>
    <dd class="col-md-10">{{ $user->name }}</dd>
    <dt class="col-md-2">{{ __('E-Mail Address') }}</dt>
    <dd class="col-md-10">{{ $user->email }}</dd>
  </dl>
</div>
@endsection