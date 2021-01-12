@extends('back::layouts.abstract')

@section('header')
    @component('back::atomicdesign.components.header.desktop', [
        'util' => 'Fichiers',
        'route' => 'admin',
        'package' => 'media',
        'label' => __('Media')
    ])
        <akk-media-bar></akk-media-bar>
    @endcomponent
@stop

@section('content')
    <div class="inner h-100 d-flex">
        <div class="position-relative" style="flex:1">
            <akk-media-gallery :from-index="true" class="position-absolute top-0 left-0 w-100 h-100"></akk-media-gallery>
        </div>
        <akk-media-side></akk-media-side>
    </div>
    <akk-media-add></akk-media-add>
    @component('back::atomicdesign.atoms.toast')@endcomponent
@endsection
