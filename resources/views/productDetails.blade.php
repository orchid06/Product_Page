<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card text-black">

                        <div class="ecommerce-gallery" data-mdb-zoom-effect="true" data-mdb-auto-height="true">
                            <div class="row py-3 shadow-5">
                                <div class="col-12 mb-1">
                                    <div class="lightbox">
                                        <img src="{{url('/uploads').'/'.$product->image}}" alt="Gallery image 1" class="ecommerce-gallery-main-img active w-100" />
                                    </div>
                                </div>
                                @if($product->gallery_image!=null)
                                @foreach($product->gallery_image as $galleryImage)
                                <div class="col-3 mt-1">
                                    <img src="{{url('/uploads/gallery').'/'.$galleryImage}}"  alt="Gallery image 1" class="active w-100" />
                                </div>
                                @endforeach
                                @else

                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h5 class="card-title">{{$product->title}}</h5>
                                <p class="text-muted mb-4">{{$product->description}}</p>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between">
                                    <span>Price :</span><span>{{$product->price}}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>In Stock :</span><span>{{$product->qty}}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Order :</span><span>{{$totalOrder}}</span>
                                </div>


                                <div class="pt-5">
                                    <h6 class="mb-0"><a href="{{route('index')}}" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</body>

</html>