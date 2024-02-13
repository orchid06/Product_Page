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
    <title>Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/tables/table-1/assets/css/table-1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap 5 CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css'>
    <!-- Font Awesome CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>

<body>
    <div class="container mt-5">
        <h3> Products</h3>
        <div class="mb=3">
            <div class="row-3">
                <div class="container">
                    <div class="row">
                        <div class="col-11">
                            <!-- Button trigger modal -->

                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inputModal">
                                Add New Product
                            </button>

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
                                                        <label for="qty" class="form-label">Quantity :</label>
                                                        <input type="text" class="form-control" name="qty" id="qty" placeholder="Quantity" value="{{old('qty')}}">
                                                    </div>

                                                    <div class="form-row">
                                                        <label for="image" class="form-label">Product Image :</label>
                                                        <input class="form-control" type="file" name="image" id="image">
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

        <section style="background-color: #eee;">
            <div class="container py-5">

                @if(count($products)>0)

                <div class="row justify-content-center">

                    @foreach($products as $product)

                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="card" style="border-radius: 15px;">
                            <div class="bg-image hover-overlay ripple ripple-surface ripple-surface-light" data-mdb-ripple-color="light">
                                <img src="uploads/{{$product->image}}" style="border-top-left-radius: 15px; border-top-right-radius: 15px;" class="img-fluid" alt="Laptop" />
                                <a href="#!">
                                    <div class="mask"></div>
                                </a>
                            </div>
                            <div class="card-body pb-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-dark">{{$product->title}}</p>
                                        <p class="small text-muted">{{$product->description}}</p>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <div class="card-body pb-0">
                                <div class="d-flex justify-content-between">
                                    <p class="text-dark">Price :{{$product->price}}</p>
                                </div>
                                <p class="small text-muted">Available :{{$product->qty}} </p>
                            </div>
                            <hr class="my-0" />
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 mb-1">
                                    <a href="#!" class="text-dark fw-bold">Edit</a>
                                    <button type="button" class="btn btn-danger">Delete</button>
                                    <button type="button" class="btn btn-primary">Add to cart</button>
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
        </section>
    </div>
</body>

</html>