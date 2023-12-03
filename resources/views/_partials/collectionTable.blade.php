<div class="col-lg-12 mb-4 col-md-12 order-0">
  <div class="card">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-header">{{ $title ?? 'Recent Collections' }}</h5>
      <a href="{{ URL::tokenRoute ('collections.create') }}" class="btn btn-primary btn-link mx-5">Create Collection</a>
    </div>
    <div class="table-responsive text-nowrap">
      @if (isset($response['body']['data']['collections']['edges']))
        @php
          $data = $response['body']['data']['collections']['edges'];
        @endphp
        @if (count($data) > 0)
          <table class="table table-hover table-borderless">
            <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Products</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            @foreach ($data as $collection)
              @php
                $id = explode('/',  $collection['node']['id'])[4];
                $description = $collection['node']['descriptionHtml'];
              @endphp
              <tr>
                <td>
                  <a href="{{ URL::tokenRoute('collections.show', ['collection' => $id]) }}">
                    @if($collection['node']['image'])
                      <img src="{{ $collection['node']['image']['src']}}&width=50&height=50" class="border p-2 rounded"
                           alt="{{$collection['node']['title']}}" width="50" height="50" loading="lazy">
                    @else
                      <img src="{{asset('assets/img/products/placeholder.png')}}" class="border p-2 rounded"
                           alt="{{$collection['node']['title']}}" width="50" height="50" loading="lazy">
                    @endif
                    <span class="fw-medium ml-1">{{$collection['node']['title']}}</span>
                  </a>
                </td>
                <td>
                @if(!empty($description))
                  <div class="description truncated" data-full-description="{{ $description }}">
                    {{ substr(strip_tags($description), 0, 40) }}...
                    <a href="{{ URL::tokenRoute('collections.show', ['collection' => $id]) }}" class="pl-1 read-more">Read more</a>
                  </div>
                @endif
                </td>
                <td>
                  <span class="badge bg-label-primary me-1">
                    <a href="{{ URL::tokenRoute('collections.show', ['collection' => $id]) }}">{{$collection['node']['productsCount']}} Products</a>
                  </span>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                        class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item"
                         href="{{ URL::tokenRoute('collections.show', ['collection' => $id]) }}"><i
                          class="bx bx-show-alt me-1"></i>
                        View</a>
                      <a class="dropdown-item"
                         href="{{ URL::tokenRoute('collections.edit', ['collection' => $id]) }}"><i
                          class="bx bx-edit-alt me-1"></i>
                        Edit</a>
                      <form class="d-inline dropdown-item"
                            action="{{ route('collections.destroy', ['collection' => $id]) }}"
                            method="POST">
                        @sessionToken
                        @method('DELETE')

                        <button type="submit" class="btn text-danger px-0"
                                onclick="return confirm('Are you sure?')">
                          <i class="bx bx-trash me-1"></i>
                          Remove Collection
                        </button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning d-flex" role="alert">
                <span class="badge badge-center rounded-pill bg-warning border-label-warning p-3 me-2"><i
                    class="bx bx-wallet fs-6"></i></span>
            <div class="d-flex flex-column ps-1">
              <h6 class="alert-heading d-flex align-items-center mb-1">Sorry!</h6>
              <span>There is no collections available</span>
            </div>
          </div>
        @endif
      @else
        {{-- Handle the case where the expected data is not present --}}
        <div class="alert alert-danger d-flex" role="alert">
              <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i
                  class="bx bx-store fs-6"></i></span>
          <div class="d-flex flex-column ps-1">
            <h6 class="alert-heading d-flex align-items-center mb-1">Error!!</h6>
            <span>Something went wrong!</span>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
