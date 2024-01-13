@props(['pagetitle' => ''])
<x-app-layout :pagetitle="$pagetitle">
    <x-slot name="title">{{ $title ?? '' }}</x-slot>
    <x-slot name="subtitle">{{ $subtitle ?? '' }}</x-slot>
    <x-slot name="nav">{{ $nav ?? '' }}</x-slot>
    {{ $slot }}
</x-app-layout>
