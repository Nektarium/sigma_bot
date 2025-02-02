@extends('layouts.app')

@section('title', "Чат {$chat->id}")

@section('content')
    <h1 class="mb-4">Чат ID: {{ $chat->id }}</h1>

    @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
    @endif

    <h2 class="mt-4">Сообщения</h2>
    @if($chat->messages->count())
      <ul class="list-group mb-4">
        @foreach($chat->messages as $message)
           <li class="list-group-item">
             <strong>
                {{ $message->is_from_guest ? 'Гость:' : 'Вы:' }}
             </strong>
             {{ $message->content }}
             <span class="text-muted float-end">{{ $message->created_at->format('d.m.Y H:i') }}</span>
           </li>
        @endforeach
      </ul>
    @else
      <div class="alert alert-info">Нет сообщений в этом чате.</div>
    @endif

    <h2>Отправить ответ</h2>
    <form action="{{ route('chat.reply', $chat->id) }}" method="post" class="mb-4">
         @csrf
         <div class="mb-3">
             <textarea name="content" class="form-control" rows="3" placeholder="Введите сообщение" required></textarea>
         </div>
         <button type="submit" class="btn btn-primary">Отправить</button>
    </form>

    <a href="{{ route('chat.index') }}" class="btn btn-secondary">&larr; Вернуться к списку чатов</a>
@endsection
