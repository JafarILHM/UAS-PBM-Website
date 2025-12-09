<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['message' => 'Login route dummy.'], 200);
});

Route::get('/', function () {
    return view('index');
});

Route::get('/charts-chartjs', function () {
    return view('charts-chartjs');
});

Route::get('/icons-feather', function () {
    return view('icons-feather');
});

Route::get('/maps-google', function () {
    return view('maps-google');
});

Route::get('/pages-blank', function () {
    return view('pages-blank');
});

Route::get('/pages-profile', function () {
    return view('pages-profile');
});

Route::get('/pages-sign-in', function () {
    return view('pages-sign-in');
});

Route::get('/pages-sign-up', function () {
    return view('pages-sign-up');
});

Route::get('/ui-buttons', function () {
    return view('ui-buttons');
});

Route::get('/ui-cards', function () {
    return view('ui-cards');
});

Route::get('/ui-forms', function () {
    return view('ui-forms');
});

Route::get('/ui-typography', function () {
    return view('ui-typography');
});