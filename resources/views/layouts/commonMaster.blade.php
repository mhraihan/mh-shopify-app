<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}"
      data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
  <title>{{ \Osiset\ShopifyApp\Util::getShopifyConfig('app_name') }}</title>
  @yield('styles')
  <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}"/>
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}"/>


  <!-- Include Styles -->
  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('layouts/sections/scriptsIncludes')
</head>

<body>


<!-- Layout Content -->
@yield('layoutContent')
<!--/ Layout Content -->

@if(\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled') && \Osiset\ShopifyApp\Util::useNativeAppBridge())
  <script
    src="{{config('shopify-app.appbridge_cdn_url') ?? 'https://unpkg.com'}}/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
  <script
    @if(\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled'))
      data-turbolinks-eval="false"
    @endif
  >
    var AppBridge = window['app-bridge'];
    var actions = AppBridge.actions;
    var utils = AppBridge.utilities;
    var createApp = AppBridge.default;
    var app = createApp({
      apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name ) }}",
      host: "{{ \Request::get('host') }}",
      forceRedirect: true,
    });
  </script>

  @include('shopify-app::partials.token_handler')
  @include('shopify-app::partials.flash_messages')
@endif
<!-- Include Scripts -->
@include('layouts/sections/scripts')
@yield('scripts')
</body>

</html>
