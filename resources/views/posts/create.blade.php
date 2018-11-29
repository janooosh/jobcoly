@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form method="POST" action="{{route('posts.store')}}">
        @csrf
        <div class="form-group">
            {{route('posts.create')}}
        </div>
    </form>      
@endsection