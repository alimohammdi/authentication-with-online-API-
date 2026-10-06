<?php

use App\Livewire\Auth\AuthForm;
use App\Livewire\Comment;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',AuthForm::class);
Route::get('/comments',Comment::class)->name('comments');
