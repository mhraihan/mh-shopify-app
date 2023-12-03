@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
  @include('_partials.shop')
  <div class="row">
   @include("_partials.collectionTable" , ["title" => "Recent Collections"])
   @include("_partials.productTable")
  </div>
@endsection
