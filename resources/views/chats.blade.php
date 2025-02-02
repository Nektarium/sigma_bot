@extends('layouts.app')

@section('title', 'Список чатов')

@section('content')
    <h1 class="mb-4">Список чатов</h1>

    @if($chats->count())
       <ul class="list-group">
           @foreach($chats as $chat)
             <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('chat.show', $chat->id) }}" class="text-decoration-none">
                  Чат ID: {{ $chat->id }}
                </a>
                <span class="badge bg-secondary">
                  {{ $chat->updated_at->diffForHumans() }}
                </span>
             </li>
           @endforeach
       </ul>
    @else
       <div class="alert alert-info mt-4">Нет чатов.</div>
    @endif
@endsection
