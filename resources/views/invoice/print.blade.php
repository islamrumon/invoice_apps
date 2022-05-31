<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body print_section">
                    <div class="row">
                    
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Invoice Number</label>
                                <input type="text" readonly class="form-control" value="{{$invoice->invoice_number}}" name="invoice_number">
                                
                              </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail2" class="form-label">Invoice Date</label>
                                <input type="text" readonly value="{{ date('Y-m-d g:i A', strtotime($invoice->order_date))}}" class="form-control"   name="invoice_date">
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Billing Address</label>
                                <textarea class="form-control" name="billing_address" readonly>{!! $invoice->billing_address !!}</textarea>
                                
                              </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail2" class="form-label">Shipping Address</label>
                                <textarea class="form-control  " name="shipping_address" readonly>{!! $invoice->shipping_address !!}</textarea>
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
                              </tr>
                            </thead>
                            <tbody id="appendHere">
                               @foreach($invoice->items as $item) 
                              <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><input type="text" readonly class="form-control" required value="{{$item->product_name}}"></td>
                                <td>
                                    <input type="number"  value="{{$item->price}}" readonly  class="form-control">
                                </td>
                                <td>
                                    <input type="number"  readonly value="{{$item->quantity}}" class="form-control">
                                </td>
                                <td>
                                    <input type="number" value="{{$item->sub_total}}" readonly class="form-control" >
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                    </div>
    
                    <div class="row">
                        <div class="col-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Discount amount</label>
                            <input type="number" value="{{$invoice->discount_amount}}" class="form-control" id="discount" readonly  >
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                               
                                <div class="card-body">
                                    <h5 class="card-title">Sub total <span class="sub_total_amount">{{number_format($invoice->sub_total_amount)}}</span></h5>
                                    <h5 class="card-title">Discount <span class="discount_amount">{{number_format($invoice->discount_amount)}}</span></h5>
                                  <h5 class="card-title">Total amount <span class="total_amount">{{number_format($invoice->total_amount)}}</span></h5>
                            
                                  
                                </div>
                              </div>
                        </div>
                    </div>
    
                
                   
                </div>
              </div>
        </div>
    </div>
    <script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
  <script>
      $(document).ready(function (){
          window.print();
      });
  </script>
</html>