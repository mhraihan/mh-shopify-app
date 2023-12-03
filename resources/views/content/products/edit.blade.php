@extends('layouts/contentNavbarLayout')

@section('title', 'Product - Edit')
 @php
    $product = $response['body']['data']['product'];
    $id = explode('/',  $product['id'])[4];
  @endphp
@section('content')
  @include("_partials.breadcrumbs",['parent' => "Product",'child' => 'Edit ' . $product['title']])
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body">
          <form method="post" action="{{ route('products.update',['product' => $id]) }}">
            @method('PUT')
            @sessionToken
            <div class="mb-3">
              <label class="form-label" for="basic-default-title">Title</label>
              <input type="text" class="form-control" id="basic-default-title" name="title" required
                     value="{{ old('title',$product['title']) }}" placeholder="Product title"/>
              @error('title')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label" for="basic-default-message">Description</label>
              <textarea id="basic-default-message" class="form-control" required name="description"
                        placeholder="Product Description">{{ old('description',$product['descriptionHtml']) }}</textarea>
              @error('description')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
          </form>
          <form class="my-4" action="{{ route('products.destroy', ['product' => $id]) }}" method="POST">
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
