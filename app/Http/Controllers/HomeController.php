<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::where('available', true)->take(3)->get();
        return view('home', compact('rooms'));
    }
}