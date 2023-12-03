<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function greetByTime(): string
  {
    $currentTime = now();
    $hour = $currentTime->hour;

    if ($hour >= 5 && $hour < 12) {
      $greeting = 'Good morning';
    } elseif ($hour >= 12 && $hour < 18) {
      $greeting = 'Good afternoon';
    } else {
      $greeting = 'Good evening';
    }

    return $greeting;
  }

  public function __invoke(Request $request): View
  {
    $shop = Auth::user();
    $shopApi = $shop->api()->rest('GET', '/admin/shop.json')['body']['shop'];
    $domain = $shop->getDomain()->toNative();
    return view('content.shop.index', ['greeting' => $this->greetByTime(), 'domain' => $domain, 'shop' => $shopApi]);
  }
}
