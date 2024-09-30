<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Api\GetShows;
use App\Actions\Api\GetShowsEvent;
use App\Actions\Api\GetEventsPlace;
use App\Actions\Api\PostEventsReserve;

Route::get('/shows', GetShows::class);
Route::get('/shows/{showId}/events', GetShowsEvent::class);
Route::get('/events/{eventId}/places', GetEventsPlace::class);
Route::post('/events/{eventId}/reserve', PostEventsReserve::class);
