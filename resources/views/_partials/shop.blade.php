<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="d-flex row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">
              {{ $greeting }}
              @if(isset($shop['shop_owner']))
              <span>, {{ $shop['shop_owner'] }}</span>
              @endif
            </h5>
            <h5 class="card-title text-secondary">Shop Name: {{ $shop['name'] }}</h5>
            <h5 class="card-title text-body-secondary">Shop ID: {{ $shop['id'] }}</h5>

          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140"
                 alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                 data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
