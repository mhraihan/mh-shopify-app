@extends('layouts/contentNavbarLayout')

@section('title', 'Products - Create')

@section('content')
  @include("_partials.breadcrumbs",['parent' => "Products",'child' => 'Create Product'])
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body">
          <form method="post" action="{{ route('products.store') }}">
            @sessionToken
            <div class="mb-3">
              <label class="form-label" for="basic-default-title">Title</label>
              <input type="text" class="form-control" id="basic-default-title" name="title" required
                     value="{{ old('title','') }}" placeholder="Product title"/>
              @error('title')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label" for="basic-default-message">Description</label>
              <textarea id="basic-default-message" class="form-control" required name="description"
                        placeholder="Product Description">{{ old('description','') }}</textarea>
              @error('description')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
