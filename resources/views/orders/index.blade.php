@extends('layouts.app')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger"> {{$errors->first()}}</div>
    @endif
<div class="row">
  <div class="offset-md-10 col-md-2">
      @if($active == 1)
          <a href="{{ route('orders.create') }}" class="btn btn-primary btn-block">+ New Order</a>
      @else

      @endif

  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Cost</th>
          <th scope="col">Customer</th>
          <th scope="col" colspan="3" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <th scope="row">{{ $order->id }}</th>
            <td>{{ $order->title }}</td>
            <td>{{ $order->description }}</td>
            <td>{{ $order->cost }}</td>
            <td>{{ $order->first_name }} {{ $order->last_name }}</td>

              @if($order->status == 1)
            <td><a href="{{ route('orders.edit', $order) }}">[Edit]</a></td>
              <td><a href="{{ route('orders.cancel', $order) }}">[Cancel]</a></td>
            <td><a href="#" onclick="event.preventDefault(); document.getElementById('delete-customer-{{ $order->id }}-form').submit();">[Delete]</a></td>

            <form id="delete-customer-{{ $order->id }}-form" action="{{ route('orders.destroy', $order) }}" method="POST" style="display: none;">
                @method('DELETE')
                @csrf
            </form>
              @else
                  <td><a href="{{ route('orders.edit', $order) }}">[Edit]</a></td>
                  <td><a href="{{ route('orders.enable', $order) }}">[Enable]</a></td>
              @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    {{ $orders->links() }}
  </div>
</div>

@stop
