<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        h3 {
            text-align: center
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/tables/table-1/assets/css/table-1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap 5 CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css'>
    <!-- Font Awesome CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <style>
        a:link {
            color: black;
            text-decoration: none;
        }

        a:hover {
            color: black;
            text-decoration: underline;
        }

        a:active {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h3> Products </h3>
        @if(session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{session()->get('error')}}
        </div>
        @endif

        <!-- /resources/views/post/create.blade.php -->


        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{session()->get('success')}}
        </div>
        @endif

        <!-- Create Post Form -->
        <div class="mb=3">
            <div class="row-3">
                <div class="container">
                    <div class="row">
                        <div class="col-11">
                            <!-- Button trigger modal -->
                            <div class="row">
                                <div class="col">

                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inputModal">
                                        Add New Product
                                    </button>

                                </div>
                                <div class="col-2 text-righ">
                                    <a href="{{route('cart.page')}}" type="button" class="btn btn-outline-success">
                                        My Cart
                                        </button><i class="bi bi-cart"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="col text-end">
                                    <form action="{{route('product.search')}}" method="get">
                                        @csrf

                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search product" name="search" id="search">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="mt-3">

                        <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title text center" id="exampleModalLabel">Add New product</h6>
                                    </div>
                                    <form action="{{route('product.store')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="container">
                                                <div class="row">

                                                    <div class="form-row">
                                                        <label for="title" class="form-label">Product Name :</label>
                                                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter Name" value="{{old('title')}}">
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="description" class="form-label">Product description :</label>
                                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description">{{old('description')}}</textarea>
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="price" class="form-label">Price :</label>
                                                        <input type="text" class="form-control" name="price" id="price" placeholder="BDT" value="{{old('price')}}">
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="discount" class="form-label">Discount :</label>
                                                        <input type="text" class="form-control" name="discount" id="discount" placeholder="Discount" value="{{old('discount')}}">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="type" id="flat" value="0">
                                                            <label class="form-check-label" for="flat">
                                                                ৳
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="type" id="percentage" checked value="1">
                                                            <label class="form-check-label" for="percentage">
                                                                %
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="qty" class="form-label">Quantity :</label>
                                                        <input type="text" class="form-control" name="qty" id="qty" placeholder="Quantity" value="{{old('qty')}}">
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="image" class="form-label">Product Image :</label>
                                                        <input class="form-control" type="file" id="image" name="image">
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="gallery_image" class="form-label">Gallery Image:</label>
                                                        <input class="form-control" type="file" id="gallery_image" name="gallery_image[]" multiple>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Cards-->
        <div class="mb-3">
            <div class="container">
                @if(count($products)>0)
                <div class="row">
                    @foreach( $products as $product )
                    <div class="col-4">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <img src="uploads/{{$product->image}}" style="width:250px; height:150px;">
                                <h6 class="card-subtitle mb-1 text-muted"></h6>
                                <a href="{{route('product.page', ['id'=>$product->id])}}" target="_blank">
                                    <h5>{{$product->title}}</h5>
                                </a>
                                <p class="card-text">{{$product->description}}</p>
                                <h6 class="card-title">Price : {{$product->price}} BDT</h6>
                                <h6 class="card-title mb-1 text-muted">In Stock: {{$product->qty}}</h6>
                                <h6 class="card-title mb-1 text-muted">Discount: {{$product->discount}} {{$product->discountType}}</h6>
                                <h6 class="card-title mb-1 text-muted">Discounted Price : {{$product->discountedPrice}}</h6>
                                <!--Card Buttons-->
                                <h6 class="card-title" style="text-align:right;">

                                    <form action="{{route('product.cart', ['id'=>$product->id])}}" method="post">
                                        @csrf

                                        <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">

                                        </button>

                                        <input id="form1" min="1" name="qty" id='qty' max="{{$product->qty}}" value="1" type="number" class="form-control form-control-sm" />

                                        <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">

                                        </button>

                                        <div class="col">

                                            <button type="submit" class="btn btn-outline-success">
                                                Add To Cart
                                            </button>

                                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editModal{{$product->id}}">
                                                Edit
                                            </button>

                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{$product->id}}">
                                                Delete
                                            </button>

                                        </div>

                                    </form>



                                </h6>
                                <!--edit modal-->
                                <div class="mt-3">

                                    <div class="modal fade" id="editModal{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title text-center" id="exampleModalLabel">Update product</h6>
                                                </div>
                                                <form action="{{route('product.update' ,['id'=>$product->id])}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="container">
                                                            <div class="row">

                                                                <div class="form-row">
                                                                    <label for="title" class="form-label">Product Name :</label>
                                                                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter Name" value="{{$product->title}}">
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="description" class="form-label">Product description :</label>
                                                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description">{{$product->description}}</textarea>
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="price" class="form-label">Price :</label>
                                                                    <input type="text" class="form-control" name="price" id="price" placeholder="BDT" value="{{$product->price}}">
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="discount" class="form-label">Discount :</label>
                                                                    <input type="text" class="form-control" name="discount" id="discount" placeholder="Discount" value="{{$product->discount}}">

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="type" id="flat" value="1">
                                                                        <label class="form-check-label" for="flat">
                                                                            ৳
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="type" id="percentage" checked value="0">
                                                                        <label class="form-check-label" for="percentage">
                                                                            %
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="qty" class="form-label">Quantity :</label>
                                                                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Quantity" value="{{$product->qty}}">
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="image" class="form-label">Product Image :</label>
                                                                    <input class="form-control" type="file" name="image" id="image">
                                                                </div>

                                                                <div class="form-row">
                                                                    <label for="gallery_image" class="form-label">Gallery Image:</label>
                                                                    <input class="form-control" type="file" id="gallery_image" name="gallery_image[]" multiple>
                                                                </div>



                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--delete modal-->
                                <div class="mt-3">

                                    <div class="modal fade" id="deleteModal{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title text-center" id="exampleModalLabel">Confirm Deletation</h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="container">

                                                        ...Are you sure you want to delete <strong>{{$product->title}} ?</strong>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="{{route('product.delete' , ['id'=>$product->id])}}" type="submit" class="btn btn-danger">Delete</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <h3>No Product Found </h3>
                @endif

            </div>
            <div class="mt-4">
                
            </div>
        </div>

        <!-- Table 1 - On this page -->
        <section class="py-3 py-md-5">
            <div class="container">
                <div class="row">

                    <div class="col">
                        <div class="card widget-card border-light shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title widget-card-title mb-4">On this Page</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                                        <thead>
                                            <tr>
                                                <th>Total Product</th>
                                                <th>Total Quantity</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-1">{{$products->count()}}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-1">{{$products->sum('qty')}}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-1">{{$products->sum('price')}}</h6>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table 1 - Inventory -->
                    <div class="col">
                        <div class="card widget-card border-light shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title widget-card-title mb-4">Inventory</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                                        <thead>
                                            <tr>
                                                <th>Total Product</th>
                                                <th>Total Quantity</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-1">{{$totalProduct}}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-1">{{$totalQty}}</h6>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>