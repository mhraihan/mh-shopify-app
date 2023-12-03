<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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

  public function index(): View
  {
    $shop = Auth::user();
    $shopApi = $shop->api()->rest('GET', '/admin/shop.json')['body']['shop'];
    $domain = $shop->getDomain()->toNative();
    $query = <<<'GRAPHQL'
        {
            collections(first: 10, sortKey: UPDATED_AT, reverse: true) {
                edges {
                    node {
                        id
                        title
                        descriptionHtml
                        productsCount
                        image {
                                id
                                src
                            }
                    }
                }
            }
            products(first: 10, sortKey: UPDATED_AT, reverse: true) {
                edges {
                    node {
                           id
                          title
                          status
                          descriptionHtml
                          featuredImage {
                            id
                            src
                          }
                    }
                }
            }
        }
    GRAPHQL;


    $response = $shop->api()->graph($query);
//    dd($response);
    return view('content.dashboard.index', ['greeting' => $this->greetByTime(), "response" => $response, 'domain' => $domain, 'shop' => $shopApi]);
  }
}
