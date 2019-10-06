@extends('layouts.app')

@section('content')
<main class="normal-pages">
    <div class="container">
        <div class="row justify-content-center">
            <users :datas="{{ json_encode($users) }}"></users>
        </div>
    </div>
</main>
@endsection