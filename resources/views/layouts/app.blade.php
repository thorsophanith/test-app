<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US"  lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('includes.head')

<body>

@include('includes.header')


 @yield('content')


    @include('includes.footer')


