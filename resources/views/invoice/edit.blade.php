@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-2"></div>
    <div class="col-8">

        <div class="float-left">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{route('invoices.print',$invoice->id)}}" class="btn btn-primary" >Print</a>
                <a href="{{route('invoices.pdf',$invoice->id)}}" class="btn btn-primary">Download Pdf</a>
              </div>
            </div>

            
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Invoice update</h5>
              <hr>
              <form action="{{route('invoices.update')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$invoice->id}}">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Invoice Number</label>
                            <input type="text" value="{{$invoice->invoice_number}}" readonly class="form-control  @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" required name="invoice_number">

                            @error('invoice_number')
                            <br>
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            
                          </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail2" class="form-label">Invoice Date</label>
                            <input type="text" value="{{ date('m/d/Y', strtotime($invoice->order_date))}}" class="form-control @error('invoice_date') is-invalid @enderror" id="exampleInputEmail2" required name="invoice_date">
                            @error('invoice_date')
                            <br>
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Billing Address</label>
                            <textarea class="form-control @error('billing_address') is-invalid @enderror" name="billing_address" required>{!! $invoice->billing_address !!}</textarea>
                            @error('billing_address')
                            <br>
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                          </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail2" class="form-label">Shipping Address</label>
                            <textarea class="form-control  @error('shipping_address') is-invalid @enderror" name="shipping_address" required>{!! $invoice->shipping_address !!}</textarea>
                            @error('shipping_address')
                            <br>
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                          </div>
                    </div>
                </div>
                <div class="border border-primery">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product name</th>
                            <th scope="col">Unit price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Sub Total</th>
                            <th scope="col">
                                <button type="button" class="btn btn-primary" onclick="appendTd()">Add Item</button>
                            </th>
                          </tr>
                        </thead>
                        <tbody id="appendHere">
                            @foreach($invoice->items as $item) 
                            @php
                             $row =     $loop->index;
                            @endphp
                          <tr class="row-{{$row}}">
                            <td scope="row">{{$row}}</td>
                            <td><input type="text" name="name[]" value="{{$item->product_name}}" class="form-control" required placeholder="Ex:Product name"></td>
                            <td><input type="number" step="0.01" min="1" value="{{$item->price}}" required name="price[]" id="price-{{$row}}" class="form-control price" placeholder="Ex:70.90"></td>
                            <td><input type="number" step="0.01" min="1" value="{{$item->quantity}}" required name="quantity[]" id="quantity-{{$row}}" class="form-control quantity" placeholder="Ex:7"></td>
                            <td><input type="number" step="0.01" min="1" value="{{$item->sub_total}}" required  id="subtotal-{{$row}}" readonly class="form-control" placeholder="Ex:70.90"></td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>

                <div class="row">
                    <div class="col-6">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Discount amount</label>
                        <input type="number" value="{{$invoice->discount_amount}}" class="form-control" id="discount" name="discount_amount"  >
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                           
                            <div class="card-body">
                                <h5 class="card-title">Sub total <span class="sub_total_amount">{{number_format($invoice->sub_total_amount)}}</span></h5>
                                <h5 class="card-title">Discount <span class="discount_amount">{{number_format($invoice->discount_amount)}}</span></h5>
                              <h5 class="card-title">Total amount <span class="total_amount">{{number_format($invoice->total_amount)}}</span></h5>
                        
                                <p class="card-text">Here is the payabol amount</p>
                              <a href="javascript:void(0)" class="btn btn-primary" onclick="calculate()" >Calculate again</a>
                            </div>
                          </div>
                    </div>
                </div>

            
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
    </div>
    <div class="col-2"></div>

</div>
    
@endsection

@section('script')

<script>

    function appendTd(){
        var url = "{{route('append.td')}}";
        $.ajax({
            url:url,
            method:'get',
            success: function (data){
                $('#appendHere').append(data.view);
                calculate();
            }
        })
        // alert(url);
    }


  

    
    function calculate(){
        var subTotal = 0.0;
        var toal=0.0;
        var discount = $('#discount').val();
        $('#appendHere tr').each(function(data) {
           var index = data;
           var unitPrice = $('#price-'+index).val();
           var quantity = $('#quantity-'+index).val();
           var sub = (unitPrice * quantity);
           $('#subtotal-'+index).val(sub);
           subTotal += sub;
        });

        //here update the amount
        total = subTotal - discount; 
        $('.sub_total_amount').empty().append(subTotal);
        $('.discount_amount').empty().append(discount);
        $('.total_amount').empty().append(total);
    }

   
</script>
    
@endsection