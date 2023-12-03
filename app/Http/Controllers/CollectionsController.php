<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CollectionsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $shop = Auth::user();
    $query = <<<'GRAPHQL'
        {
            collections(first: 50, sortKey: UPDATED_AT, reverse: true) {
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
        }
    GRAPHQL;
    $response = $shop->api()->graph($query);
    return view('content.collections.index', ["response" => $response]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('content.collections.create');
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
    $collectionId = 'gid://shopify/Collection/' . $id;
    // GraphQL query
    $query = <<<GRAPHQL
    query {
      collection(id: "{$collectionId}") {
        id
        title
        productsCount
        descriptionHtml
        image {
          id
          src
        }
         products(first: 15, sortKey: TITLE, reverse: true) {
              edges {
                  node {
                      id
                      title
                       status
                        featuredImage {
                          id
                          src
                        }
                  }
              }
          }
      }
    }
    GRAPHQL;
    $shop = Auth::user();
    $response = $shop->api()->graph($query);
    return view('content.collections.show', ["response" => $response]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $collectionId = 'gid://shopify/Collection/' . $id;
    // GraphQL query
    $query = <<<GRAPHQL
    query {
      collection(id: "{$collectionId}") {
        id
        title
        descriptionHtml
      }
    }
    GRAPHQL;
    $shop = Auth::user();
    $response = $shop->api()->graph($query);
//    dd($response);
    return view('content.collections.edit', ["response" => $response]);
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
    // Replace with the actual collection ID
    $collectionId = 'gid://shopify/Collection/' . $id;

    // GraphQL mutation
    $query = <<<GRAPHQL
    mutation {
      collectionDelete(input:{id: "{$collectionId}"}) {
        deletedCollectionId
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
     $redirectUrl = getRedirectRoute('collections.index');
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
      // GraphQL query to create a new collection
      $collectionId = 'gid://shopify/Collection/' . $id;
      $query = <<<GRAPHQL
        mutation {
            collectionUpdate(input: {
                 id: "{$collectionId}",
                title: "{$request->input('title')}",
                descriptionHtml: "{$request->input('description')}",
            }) {
                collection {
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
      $redirectUrl = getRedirectRoute('collections.show', ['collection' => $id]);
    } else {
      // GraphQL query to create a new collection
      $query = <<<GRAPHQL
        mutation {
            collectionCreate(input: {
                title: "{$request->input('title')}",
                descriptionHtml: "{$request->input('description')}",
            }) {
                collection {
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
      $redirectUrl = getRedirectRoute('collections.index');
    }

    return redirect($redirectUrl);
  }
}
