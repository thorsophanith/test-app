<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('admin.includes.head')

                                <body class="body">
                                <div id="wrapper">
                                <div id="page" class="">
                                <div class="layout-wrap">

@include('admin.includes.header')

                                <div class="section-content-right">

@include('admin.includes.nav')

@yield('content')

@include('admin.includes.footer')
                    </div>

                </div>
            </div>
        </div>
    </div>