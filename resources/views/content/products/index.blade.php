@extends('layouts/contentNavbarLayout')

@section('title', 'All Products')

@section('content')
  <div class="row">
   @include("_partials.productTable",["title" => "All Products"])
  </div>
@endsection
