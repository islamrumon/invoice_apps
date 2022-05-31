@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date</th>
                    <th scope="col">Total</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $item)
                    <tr>
                        <th>{{$loop->index+1}}</th>
                        <td>{{$item->invoice_number}}</td>
                        <td>
                          {{date('Y-m-d g:i A', strtotime($item->order_date))}}</td>
                        <td>{{$item->total_amount}}</td>
                        <td>
                          <a class="nav-link" href="{{route('invoices.edit',$item->id)}}">Edit</a>
                          <a class="nav-link" href="{{route('invoices.show',$item->id)}}">Show</a>
                          <a class="nav-link" href="{{route('invoices.print',$item->id)}}"  target="_blank">Print</a>
                          <a class="nav-link" href="{{route('invoices.pdf',$item->id)}}" target="_blank">Pdf</a>
                        </td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
    </div>
    <div class="col-2"></div>

</div>
    
@endsection

@section('script')
    
@endsection