<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Gallery;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {

        $products = Product::latest()->paginate(3);


        $onPageQty = $onPagePrice = 0;
        $onPageProduct = count($products);


        foreach ($products as $product) {
            $onPageQty   += $product->qty;
            $onPagePrice += $product->price;
        }


        $totalProduct = Product::count();
        $totalQty     = Product::sum('qty');
        $totalPrice   = Product::sum('price');


        return view('product', compact(
            'products',
            'totalQty',
            'totalPrice',
            'totalProduct',
            'onPageQty',
            'onPageProduct',
            'onPagePrice'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|max:50',
            'description'     => 'required|max:200',
            'price'           => 'required|numeric',
            'qty'             => 'required|numeric',
            'image'           => 'required|image',
            'gallery_image.*' => 'image',
            'discount'        => 'numeric|nullable',
            'type'            => 'nullable|in:1,2',
            'discountPrice'   => 'max:price|nullable',
        ]);


        $image = $request->file('image');
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move('uploads/', $imageName);


        $galleryFileNames = [];

        if ($request->hasFile('gallery_image')) {
            foreach ($request->file('gallery_image') as $galleryImage) {
                $galleryImageName = uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move('uploads/gallery/', $galleryImageName);
                $galleryFileNames[] = $galleryImageName;
            }
        }


        $price    = $request->input('price');
        $discount = $request->input('discount');

        $discountType    = $request->input('type') == 1
            ? "%"
            : "৳";

        $discountedPrice = $request->input('type') == 1
            ? $price - ($price * $discount * (1 / 100))
            : $price - $discount;


        Product::create([
            'title'           => $request->input('title'),
            'description'     => $request->input('description'),
            'price'           => $price,
            'qty'             => $request->input('qty'),
            'image'           => $imageName,
            'discount'        => $discount,
            'discountType'    => $discountType,
            'discountedPrice' => $discountedPrice,
            'gallery_image'   => $galleryFileNames,
        ]);

        return back()->with('success', 'Data stored successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|max:50',
            'description' => 'required|max:200',
            'price'       => 'required|numeric|gt:-1',
            'qty'         => 'required|numeric',
            'discount'    => 'nullable|lte:price|gt:-1|numeric',
        ]);

        $product = Product::findOrFail($id);


        $discount     = $request->input('discount', 0);
        $price        = $request->input('price');
        $discountType = $request->input('type');

        $discountedPrice = $discountType == 1
            ? $price - ($price * $discount * (1 / 100))
            : $price - $discount;

        $discountType    = $discountType == 1
            ? "%"
            : "৳";

        $filename         = $product->image;
        $galleryFileNames = $product->gallery_image;

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename  = uniqid() . '.' . $extention;
            $file->move('uploads/', $filename);
        }

        if ($request->hasFile('gallery_image')) {
            foreach ($request->file('gallery_image') as $galleryFile) {
                $extention       = $galleryFile->getClientOriginalExtension();
                $galleryFileName = uniqid() . '.' . $extention;
                $galleryFile->move('uploads/gallery/', $galleryFileName);
                $galleryFileNames[] = $galleryFileName;
            }
        }

        $product->update([
            'title'           => $request->input('title'),
            'description'     => $request->input('description'),
            'price'           => $price,
            'qty'             => $request->input('qty'),
            'discount'        => $discount,
            'discountType'    => $discountType,
            'discountedPrice' => $discountedPrice,
            'image'           => $filename,
            'gallery_image'   => $galleryFileNames,
        ]);

        return back()->with('success', 'Product Updated');
    }

    public function delete($id)
    {

        $cartItems = Cart::where('product_id', $id)->get();

        if ($cartItems->isNotEmpty()) {
            return back()->with('error', 'This item is added in cart and can not be deleted.');
        }

        $product = Product::find($id);

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }


        $imagePath = public_path("uploads/{$product->image}");
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }

        foreach ($product->gallery_image as $galleryFileName) {
            $galleryPath = public_path("uploads/gallery/{$galleryFileName}");
            if (file_exists($galleryPath)) {
                @unlink($galleryPath);
            }
        }


        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $products = Product::where('title', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%")->paginate(3);

        $totalProduct  = Product::count();
        $totalQty      = Product::sum('qty');
        $totalPrice    = Product::sum('price');

        $onPageQty     = $products->sum('qty');
        $onPagePrice   = $products->sum('price');
        $onPageProduct = $products->count();

        return view('product', compact(
            'products',
            'totalQty',
            'totalPrice',
            'totalProduct',
            'onPageProduct',
            'onPageQty',
            'onPagePrice'
        ));
    }

    public function cart(Request $request, $id)
    {
        $existingCartItem = Cart::where('product_id', $id)->first();

        if ($existingCartItem) {
            return back()->with('error', 'Item already added in cart. You can increase the quantity in the cart.');
        }

        $product = Product::find($id);

        $maxQty = $product->qty;

        $request->validate([
            'qty' => 'required|numeric|max:' . $maxQty,
        ]);

        $productPrice = $product->discountedPrice ?? $product->price;

        $cartProduct = new Cart();
        $cartProduct->product_id  = $product->id;
        $cartProduct->title       = $product->title;
        $cartProduct->description = $product->description;
        $cartProduct->price       = $productPrice;
        $cartProduct->qty         = $request->input('qty');
        $cartProduct->stockQty    = $maxQty;
        $cartProduct->image       = $product->image;
        $cartProduct->save();

        $newQty = $maxQty - $request->input('qty');
        $product->update(['qty' => $newQty]);

        return back()->with('success', 'Item added to cart successfully.');
    }

    public function cartpage()
    {
        $cartProducts = Cart::latest()->get();

        $totalCartProduct = $cartProducts->count();
        $totalCartQty     = $cartProducts->sum('qty');

        $totalCartPrice = $cartProducts->sum(function ($cartProduct) {
            return $cartProduct->price * $cartProduct->qty;
        });

        return view('cart', compact('cartProducts', 'totalCartProduct', 'totalCartQty', 'totalCartPrice'));
    }

    public function cartQtyUpdate(Request $request, $product_id)
    {
        $cart = Cart::where('product_id', $product_id)->first();

        if (!$cart) {
            return back()->with('error', 'Cart item not found.');
        }

        $maxValue        = $cart->stockQty;
        $existingCartQty = $cart->qty;

        $request->validate([
            'cartQty' => 'required|numeric|max:' . $maxValue,
        ]);

        $inputQty  = $request->input('cartQty');
        $product   = Product::find($product_id);
        $stockQty  = $product->qty;

        $qtyDifference = $existingCartQty - $inputQty;

        if ($qtyDifference > 0) {

            $newQty = $stockQty + $qtyDifference;
        } elseif ($qtyDifference < 0) {

            $newQty = $stockQty - abs($qtyDifference);
        } else {

            $newQty = $stockQty;
        }


        $product->update(['qty' => $newQty]);


        $cart->update(['qty' => $inputQty]);

        return back()->with('success', 'Quantity updated successfully.');
    }

    public function cartProductDelete($id)
    {

        $cartProduct = Cart::find($id);


        if (!$cartProduct) {
            return back()->with('error', 'Cart item not found.');
        }


        $product_id = $cartProduct->product_id;
        $cartQty    = $cartProduct->qty;
        $product    = Product::find($product_id);


        if (!$product) {
            return back()->with('error', 'Product not found.');
        }


        $newQty = $cartQty + $product->qty;

        $product->update(['qty' => $newQty]);

        $cartProduct->delete();

        return back()->with('success', 'Item deleted successfully.');
    }

    public function productDetails($id)
    {
        
        $product = Product::find($id);

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $orderProducts = Cart::where('product_id', $id)->get();

        $totalOrder = $orderProducts->sum('qty');

        $galleryImages = $product->gallery_image;

        return view('productDetails', compact('product', 'galleryImages', 'totalOrder'));
    }


    public function purchased()
    {
    }
}
