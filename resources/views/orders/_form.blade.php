@if(!empty($successMsg))
    <div class="alert alert-success"> {{ $successMsg }}</div>
@endif



@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div><br />
@endif

<div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Order Title</label>
          <input type="text" name="order_name" class="form-control" value="{{ old('order_name', $order->title) }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Order Description</label>
          <input type="text" name="order_description" class="form-control" value="{{ old('order_description', $order->description) }}">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Order Cost</label>
          <input type="text" name="order_cost" class="form-control" value="{{ old('order_cost', $order->cost) }}">
        </div>
      </div>
    </div>
    <div class="row" style="align-items: center">
        <div class="col-md-6">
            <div class="form-group">
                <label id="customer_label">Customer</label>
                <select name="customer_id"  searchable="Search here.." class="form-control">
                    @foreach($customers as $customer)
                        <option   @if($customer->id == $order->customer_id) selected @else  @endif class="form-control" value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="dropdown">
                <button class="btn btn-lg btn-secondary dropdown-toggle" type="button"
                        id="dropdownMenu1" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                    <span class="caret">Tags</span>
                </button>
                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                    @foreach($tags as $tag)
                        <li>
                            <label style="display: flex">
                                <input type="checkbox" name='tags[]'  @if(in_array($tag->id,$order_tags_arr)) checked @else  @endif value="{{$tag->id}}"> {{$tag->name}}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>



