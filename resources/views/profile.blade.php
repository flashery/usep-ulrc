@extends('layouts.app')

@section('content')
<main class="normal-pages">
    <div class="container">
        <div class="row justify-content-center">
            <profile :p_user="{{json_encode($user)}}"></profile>
        </div>
    </div>
</main>
@endsection