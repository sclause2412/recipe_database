@extends('errors::minimal')

@section('title', __('Unknown'))
@section('code', $exception->getStatusCode())
@section('message', $exception->getMessage() ?: __('Unknown'))
