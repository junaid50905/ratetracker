<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * welcome
     */
    public function welcome()
    {
        $exchange_rates = ExchangeRate::orderBy('id', 'desc')->get();
        $images = Content::where('type', 'image')->orderBy('id', 'desc')->get();
        $videos = Content::where('type', 'video')->orderBy('id', 'desc')->get();
        $currency = Content::where('type', 'currency')->first();
        $exchange_rate_duration = $currency ? $currency->duration : 1000;
        return view('ui.frontend.welcome', compact('exchange_rates', 'images', 'exchange_rate_duration', 'videos'));
    }
}
