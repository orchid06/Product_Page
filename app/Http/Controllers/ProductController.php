<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{

    public function index(): View
    {
        $products = Product::with(['carts'])->withCount(['carts'])->latest()->get();

        $totalQty = $totalPrice = 0;

        $products = $products->map(function (Product $product) use (&$totalQty, &$totalPrice) {
            $cartQty     = $product->carts->sum('qty');
            $totalQty   += $product->qty + $cartQty;
            return $product;
        });

        $totalProduct = Product::count();

        return view('product', compact('products', 'totalQty', 'totalProduct'));
    }


    public function uploadImage(mixed $file): string
    {
        $imageName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move('uploads/', $imageName);
        return $imageName;
    }

    public function uploadGalleryImages($files)
    {
        $galleryFileNames = [];

        foreach ($files as $galleryImage) {
            $galleryImageName = uniqid() . '.' . $galleryImage->getClientOriginalExtension();
            $galleryImage->move('uploads/gallery/', $galleryImageName);
            $galleryFileNames[] = $galleryImageName;
        }

        return $galleryFileNames;
    }

    public function deleteFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }


    public function store(Request $request): RedirectResponse
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


        $imageName            = $this->uploadImage($request->file('image'));

        $galleryFileNames = $request->hasFile('gallery_image') ? $this->uploadGalleryImages($request->file('gallery_image'))
            : null;


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
            'gallery_image'   => $galleryFileNames ?? null,
        ]);

        return back()->with('success', 'Data stored successfully');
    }

    public function update(Request $request, int $id)
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

        $discountedPrice = $discountType == 1 ? $price - ($price * $discount * (1 / 100))
            : $price - $discount;

        $discountType    = $discountType == 1 ? "%"
            : "৳";


        $imageName = $request->hasFile('image')
            ? $this->uploadImage($request->file('image'))
            : $product->image;

        $galleryFileNames = $request->hasFile('gallery_image')
            ? $this->uploadGalleryImages($request->file('gallery_image'))
            : $product->gallery_image;

        $product->update([
            'title'           => $request->input('title'),
            'description'     => $request->input('description'),
            'price'           => $price,
            'qty'             => $request->input('qty'),
            'discount'        => $discount,
            'discountType'    => $discountType,
            'discountedPrice' => $discountedPrice,
            'image'           => $imageName ?? $product->image,
            'gallery_image'   => $galleryFileNames ?? $product->galery_image,
        ]);

        return back()->with('success', 'Product Updated');
    }

    public function delete($id)
    {

        $product = Product::findOrfail($id);

        $cartItems = Cart::where('product_id', $product->id)->count();

        if ($cartItems > 0) {
            return back()->with('error', 'This item is added in cart and can not be deleted.');
        }




        $imagePath = public_path("uploads/{$product->image}");
        $this->deleteFile($imagePath);

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

        return view('product', compact(
            'products',
            'totalQty',
            'totalPrice',
            'totalProduct',
        ));
    }

    public function cart(Request $request, $id)
    {


        $product = Product::findOrfail($id);

        $request->validate([
            'qty' => 'required|numeric|gt:0|max:' . $product->qty,
        ]);

        $existingCartItem = Cart::where('product_id', $product->id)->first();

        if ($existingCartItem) {
            $existingCartItem->update(['qty' => $existingCartItem->qty + $request->input('qty')]);
        } else {
            $productPrice = $product->discountedPrice ?? $product->price;

            Cart::create([
                'product_id'  => $product->id,
                'qty'         => $request->input('qty'),
                'price'       => $product->price,

            ]);
        }

        $product->decrement('qty', $request->input('qty'));


        return back()->with('success', 'Item added to cart successfully.');
    }

    public function cartpage()
    {
        $cartProducts = Cart::with('product')->latest()->get();

        $totalCartProduct = $cartProducts->count();
        $totalCartQty     = $cartProducts->sum('qty');
        $totalCartPrice   = $cartProducts->sum(function ($cartProduct) {
            return $cartProduct->price * $cartProduct->qty;
        });

        return view('cart', compact('cartProducts', 'totalCartProduct', 'totalCartQty', 'totalCartPrice'));
    }


    public function cartQtyUpdate(Request $request, $product_id)
    {
        $cart     = Cart::where('product_id', $product_id)->firstOrfail();
        $product  = $cart->product;


        $maxValue        = $product->qty;
        $existingCartQty = $cart->qty;

        $request->validate([
            'cartQty' => 'required|numeric|max:' . $maxValue,
        ]);

        $inputQty  = $request->input('cartQty');

        $stockQty  = $product->qty;

        $qtyDifference = $existingCartQty - $inputQty;

        $newQty = $stockQty;

        if ($qtyDifference > 0) {
            $newQty = $stockQty + $qtyDifference;
        } elseif ($qtyDifference < 0) {
            $newQty = $stockQty - abs($qtyDifference);
        }


        $product->update(['qty' => $newQty]);


        $cart->update(['qty' => $inputQty]);

        return back()->with('success', 'Quantity updated successfully.');
    }


    public function cartProductDelete($id)
    {
        $cartProduct = Cart::findOrFail($id);

        $product = $cartProduct->product;

        
        $product->update(['qty' => $product->qty + $cartProduct->qty]);

        $cartProduct->delete();

        return back()->with('success', 'Item deleted successfully.');
    }


    public function productDetails($id)
    {

        $product = Product::findOrfail($id);


        $orderProducts = Cart::where('product_id', $id)->get();

        $totalOrder = $orderProducts->sum('qty');

        return view('productDetails', compact('product', 'totalOrder'));
    }


    public function purchased()
    {
    }
}
