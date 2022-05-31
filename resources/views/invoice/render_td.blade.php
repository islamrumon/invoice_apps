<tr class="row-{{$row}}">
    <td scope="row">{{$row}}</td>
    <td><input type="text" name="name[]" class="form-control" required placeholder="Ex:Product name"></td>
    <td><input type="number" step="0.01" min="1" required name="price[]" id="price-{{$row}}" class="form-control" placeholder="Ex:70.90"></td>
    <td><input type="number" step="0.01" min="1" required name="quantity[]" id="quantity-{{$row}}" class="form-control" placeholder="Ex:7"></td>
    <td><input type="number" step="0.01" min="1" required  id="subtotal-{{$row}}" readonly class="form-control" placeholder="Ex:70.90"></td>
  </tr>