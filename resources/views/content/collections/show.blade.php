@extends('layouts/contentNavbarLayout')

@section('title', 'Collections - Create')

@section('content')
  @php
    $collection = $response['body']['data']['collection'];
    $id = explode('/',  $collection['id'])[4];
  @endphp
  @include("_partials.breadcrumbs",['parent' => "Collection",'child' =>  $collection['title'] ])
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        @if($collection['image'])
          <img class="card-img-top object-fit-cover"
               src="{{$collection['image']['src']}}&height=350"
               loading="lazy"
               height="350"
               alt="{{ $collection['title'] }}">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ $collection['title'] }}</h5>
          @if(!empty($collection['descriptionHtml']))
            <div class="card-content">
              {!!  $collection['descriptionHtml'] !!}
            </div>
          @endif

        </div>

        <div class="card-body">
          <a href="{{ URL::tokenRoute('collections.edit',['collection' => $id]) }}"
             class="card-link btn btn-outline-secondary">Edit Collection</a>
          <form action="{{ route('collections.destroy', ['collection' => $id]) }}" method="POST"
                class="d-inline-block px-4">
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
  <div class="row">
    @php
      $collectionProducts = $response['body']['data']['collection']['products'];
    @endphp
    @include("_partials.productTable",["title" => "Recent Products",'collectionProducts' => $collectionProducts])
  </div>
@endsection
