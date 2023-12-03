@extends('layouts/contentNavbarLayout')

@section('title', 'Collections - Edit')
 @php
    $collection = $response['body']['data']['collection'];
    $id = explode('/',  $collection['id'])[4];
  @endphp
@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body">
          <form method="post" action="{{ route('collections.update',['collection' => $id]) }}">
            @method('PUT')
            @sessionToken
            <div class="mb-3">
              <label class="form-label" for="basic-default-title">Title</label>
              <input type="text" class="form-control" id="basic-default-title" name="title" required
                     value="{{ old('title',$collection['title']) }}" placeholder="Collection title"/>
              @error('title')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label" for="basic-default-message">Description</label>
              <textarea id="basic-default-message" class="form-control" required name="description"
                        placeholder="Collection Description">{{ old('description',$collection['descriptionHtml']) }}</textarea>
              @error('description')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
          </form>
          <form class="my-4" action="{{ route('collections.destroy', ['collection' => $id]) }}" method="POST">
            @sessionToken
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger card-link" onclick="return confirm('Are you sure?')">
              Remove Collection
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
