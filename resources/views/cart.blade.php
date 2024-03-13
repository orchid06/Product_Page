<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css'>
</head>

<body>
  <section class="h-100 h-custom" style="background-color: #d2c9ff;">
    <div class="container py-5 h-100">
      @if(session()->has('success'))
      <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
      </div>
      @endif

      
      <div class="row d-flex justify-content-center align-items-center h-100">

        <div class="col-12">
          <div class="card card-registration card-registration-2" style="border-radius: 15px;">
            <div class="card-body p-0">
              <div class="row g-0">
                <div class="col-lg-8">
                  <div class="p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                      <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                      <h6 class="mb-0 text-muted"></h6>
                    </div>
                    @if(count($cartProducts)>0)
                    @foreach($cartProducts as $cartProduct)
                    <hr class="my-4">

                    <div class="row mb-4 d-flex justify-content-between align-items-center">
                      <div class="col-md-2 col-lg-2 col-xl-2">
                        <img src="uploads/{{$cartProduct->product->image}}" class="img-fluid rounded-3" alt="Cotton T-shirt">
                      </div>
                      <div class="col-md-3 col-lg-3 col-xl-3">
                        <h6 class="text-muted">{{$cartProduct->product->description}}</h6>
                        <h6 class="text-black mb-0">{{$cartProduct->product->title}}</h6>
                      </div>

                      <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                        <form action="{{route('cartQty.update' ,['product_id'=>$cartProduct->product_id])}}" method="post">
                          @csrf
                          <div class="row">


                            <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                              <i class="fas fa-minus"></i>
                            </button>

                            <input id="form1" min="1" max="{{$cartProduct->product->qty}}" name="cartQty" value="{{$cartProduct->qty}}" type="number" class="form-control form-control-sm" />

                            <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                              <i class="fas fa-plus"></i>
                            </button>

                            <button type="submit" class="btn btn-light">Add</button>


                          </div>
                        </form>
                      </div>

                      <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                        <h6 class="mb-0"> ৳ {{$cartProduct->price}}</h6>
                      </div>
                      <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                        <a href="{{route('cartProduct.delete' , ['id'=>$cartProduct->id])}}" type="submit" class="btn btn-danger">Delete</button></a>
                      </div>
                    </div>
                    @endforeach
                    @else
                    <h3>No item Added to Cart </h3>
                    @endif

                    <div class="pt-5">
                      <h6 class="mb-0"><a href="{{route('index')}}" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 bg-grey">
                  <div class="p-5">
                    <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                      <h5 class="text-muted">{{$totalCartProduct}} items </h5>
                      <h5 class="text-muted"> Quantity: {{$totalCartQty}} </h5>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-5">
                      <h5 class="text-uppercase">Total price: </h5>
                      <h5> {{$totalCartPrice}} ৳</h5>
                    </div>

                    <a href="{{route('product.purchased')}}" type="button" class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">Check Out</button></a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      
    </div>
  </section>
</body>

</html>