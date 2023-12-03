@extends('layouts/contentNavbarLayout')

@section('title', 'Collections - All')

@section('content')
  <div class="row">
   @include("_partials.collectionTable",["title" => "All Collections"])
  </div>
@endsection
