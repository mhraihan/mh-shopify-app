@extends('layouts/contentNavbarLayout')

@section('title', 'Product - Create')

@section('content')
  @php
    $product = $response['body']['data']['product'];
    $id = explode('/',  $product['id'])[4];
  @endphp
  @include("_partials.breadcrumbs",['parent' => "Product",'child' => $product['title'] ])
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        @if($product['featuredImage'])
          <img class="card-img-top object-fit-cover"
               src="{{$product['featuredImage']['src']}}&height=350"
               loading="lazy"
               height="350"
               alt="{{ $product['title'] }}">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ $product['title'] }}</h5>
          @if(!empty($product['descriptionHtml']))
            <div class="card-content">
               {!! $product['descriptionHtml'] !!}
            </div>
          @endif

        </div>

        <div class="card-body">
          <a href="{{ URL::tokenRoute('products.edit',['product' => $id]) }}"
             class="card-link btn btn-outline-secondary">Edit Product</a>
          <form action="{{ route('products.destroy', ['product' => $id]) }}" method="POST"
                class="d-inline-block px-4">
            @sessionToken
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger card-link" onclick="return confirm('Are you sure?')">
              Remove Product
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
