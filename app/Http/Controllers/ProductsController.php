<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $shop = Auth::user();
    $query = <<<'GRAPHQL'
        {
            products(first: 50, sortKey: UPDATED_AT, reverse: true) {
                edges {
                    node {
                        id
                        title
                        descriptionHtml
                        status
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
    return view('content.products.index', ["response" => $response]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('content.products.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
  {
    return $this->extracted($request);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $productId = 'gid://shopify/Product/' . $id;
    // GraphQL query
    $query = <<<GRAPHQL
    query {
      product(id: "{$productId}") {
        id
        title
        descriptionHtml
        status
        featuredImage {
             id
             src
        }
      }
    }
    GRAPHQL;
    $shop = Auth::user();
    $response = $shop->api()->graph($query);
    return view('content.products.show', ["response" => $response]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $productId = 'gid://shopify/Product/' . $id;
    // GraphQL query
    $query = <<<GRAPHQL
    query {
      product(id: "{$productId}") {
        id
        title
        descriptionHtml
      }
    }
    GRAPHQL;
    $shop = Auth::user();
    $response = $shop->api()->graph($query);
    //    dd($response);
    return view('content.products.edit', ["response" => $response]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    return $this->extracted($request, $id);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $shop = Auth::user();
    // Replace with the actual product ID
    $productId = 'gid://shopify/Product/' . $id;

    // GraphQL mutation
    $query = <<<GRAPHQL
    mutation {
      productDelete(input:{id: "{$productId}"}) {
        deletedProductId
        userErrors {
          field
          message
        }
      }
    }
    GRAPHQL;

    // Execute the mutation
    $shop->api()->graph($query);
    // Process the response as needed
    $redirectUrl = getRedirectRoute('products.index');
    return redirect($redirectUrl);
  }

  /**
   * @param Request $request
   * @return \Illuminate\Contracts\Foundation\Application|Application|RedirectResponse|Redirector
   */
  public function extracted(Request $request, string $id = null): RedirectResponse|\Illuminate\Contracts\Foundation\Application|Redirector|Application
  {
    $shop = Auth::user();
    // Validate the request data
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'description' => 'required',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }

    if ($request->isMethod('put') || $request->isMethod('patch')) {
      // GraphQL query to create a new product
      $productId = 'gid://shopify/Product/' . $id;
      $query = <<<GRAPHQL
        mutation {
            productUpdate(input: {
                 id: "{$productId}",
                title: "{$request->input('title')}",
                descriptionHtml: "{$request->input('description')}",
            }) {
                product {
                    id
                    title
                    descriptionHtml
                }
                userErrors {
                    field
                    message
                }
            }
        }
      GRAPHQL;
      $shop->api()->graph($query);
      $redirectUrl = getRedirectRoute('products.show', ['product' => $id]);
    } else {
      // GraphQL query to create a new product
      $query = <<<GRAPHQL
        mutation {
            productCreate(input: {
                title: "{$request->input('title')}",
                descriptionHtml: "{$request->input('description')}",
            }) {
                product {
                    id
                    title
                }
                userErrors {
                    field
                    message
                }
            }
        }
      GRAPHQL;
      $shop->api()->graph($query);
      $redirectUrl = getRedirectRoute('products.index');
    }

    return redirect($redirectUrl);
  }
}
