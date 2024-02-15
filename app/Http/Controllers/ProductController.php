<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Gallery;

class ProductController extends Controller
{
    public function index(){

        $products   = Product::latest()->paginate(3);

        $onPageProduct = count($products);
        $onPageQty = 0;
        $onPagePrice = 0;

        foreach($products as $product){
            $onPageQty      += $product -> qty;
            $onPagePrice    += $product -> price;
        }

        $totalProduct   = Product::count('title');
        $totalQty       = Product::sum('qty');
        $totalPrice     = Product::sum('price');

        return view('product', compact('products'))

        ->with('totalQty',$totalQty)
        ->with('totalPrice', $totalPrice )
        ->with('totalProduct',$totalProduct)
        ->with('onPageProduct',$onPageProduct)
        ->with('onPageQty',$onPageQty)
        ->with('onPagePrice',$onPagePrice);
        
    }

    public function store(Request $request){

        

        $request -> validate([

            'title'        =>'required|max:50',
            'description'  =>'required|max:200',
            'price'        =>'required|numeric',
            'qty'          =>'required|numeric',
            'image'        =>'required',

        ]);

        if($request->hasFile('image'))
        {
            
            $file          = $request->file('image');
            $extention     = $file->getClientOriginalExtension();
            $filename      =uniqid(). '.' .$extention;
            $file ->move('uploads/',$filename);


        }


        if($request->hasFile('gallery_image'))
        {
            $galleryFiles = $request->file('gallery_image');


            foreach($galleryFiles as $galleryFile){

            $extention     = $galleryFile->getClientOriginalExtension();
            $galleryFileName      =uniqid(). '.' .$extention;
            $galleryFile->move('uploads/gallery/',$galleryFileName);

            $galleryFileNames[] = $galleryFileName;

            }


        }

        Product::create([

            'title'       => $request -> input('title'),
            'description' => $request -> input('description'),
            'price'       => $request -> input('price'),
            'qty'         => $request -> input('qty'),
            'image'       => $filename,
            'gallery_image'=>$galleryFileNames,

        ]);

        return back();


        
    }

    public function update(Request $request, $id){

        $request -> validate([

            'title'        =>'required|max:50',
            'description'  =>'required|max:200',
            'price'        =>'required|numeric',
            'qty'          =>'required|numeric',

        ]);

        if($request->hasFile('image'))
        {
            $file          = $request->file('image');
            $extention     = $file->getClientOriginalExtension();
            $filename      = uniqid(). '.' .$extention;
            $file ->move('uploads/',$filename);

            

            Product::where('id',$id)->update([

            'image'   => $filename,

            ]);
        }

        if($request->hasFile('gallery_image'))
        {
            $files = $request->file('gallery_image');

            foreach($files as $file){
                
                $extention     = $file->getClientOriginalExtension();
                $filename      =uniqid(). '.' .$extention;
                $file ->move('uploads/gallery/',$filename);
                }
        }

        Product::where('id',$id)->update([

            'title'       => $request -> input('title'),
            'description' => $request -> input('description'),
            'price'       => $request -> input('price'),
            'qty'         => $request -> input('qty'),
            

        ]);

        return back();
        
    }

    public function delete($id){

        $results = Cart::where('product_id',$id)->get();

        if(count($results)>0){

            return back()->with('error', 'Item added in cart, Can not be deleted');


        }
        else{

            Product::destroy($id);
            return back();
        }  
    }

    public function search(Request $request){

        $search = $request->input('search');

        $results = Product::where('title' , 'LIKE' , '%' .$search. '%' )
                        ->orwhere('description' , 'LIKE' , '%' .$search. '%' )->paginate(3);

        $totalProduct  = Product::count('title');
        $totalQty      = Product::sum('qty');
        $totalPrice    = Product::sum('price');

        $onPageQty=0;
        $onPagePrice=0;
        $onPageProduct = count($results);

        foreach($results as $result){
            $onPageQty      += $result -> qty;
            $onPagePrice    += $result -> price;
        }

        return view('product' , ['products'=>$results])
             ->with('totalQty',$totalQty)
             ->with('totalPrice', $totalPrice )
             ->with('totalProduct',$totalProduct)
             ->with('onPageProduct',$onPageProduct)
             ->with('onPageQty',$onPageQty)
             ->with('onPagePrice',$onPagePrice);
        
    }

    public function cart(Request $request, $id){

        $product = Product::find($id);

        $maxValue = $product->qty;

        $request -> validate([

            'qty'          =>'required|numeric|max:'.$maxValue,

             ]);

        

        

        

        $cartProduct = new Cart();

        $cartProduct->product_id  = $product->id;
        $cartProduct->title       = $product->title;
        $cartProduct->description = $product->description;
        $cartProduct->price       = $product->price;
        $cartProduct->qty         = $request->input('qty');
        $cartProduct->stockQty    = $product->qty;
        $cartProduct->image       = $product->image;

        $cartProduct->save();

        $newQty = $maxValue-$request->input('qty');

        Product::where('id', $id)->update([

            'qty' => $newQty,

        ]);
        

        return back();

    }

    public function cartpage(){

        $cartProducts = Cart::latest()->get();

        $totalCartProduct   = Cart::count('title');
        $totalCartQty       = Cart::sum('qty');

        $totalCartPrice =0;

        foreach( $cartProducts as $cartProduct ){

            $productPrice    = $cartProduct->price * $cartProduct->qty;
            $totalCartPrice += $productPrice;

        }
        

        return view('cart' , compact('cartProducts'))
             ->with('totalCartProduct',$totalCartProduct)
             ->with('totalCartQty',$totalCartQty)
             ->with('totalCartPrice',$totalCartPrice);
    }

    public function cartQtyUpdate(Request $request, $product_id){


        $carts = Cart::where('product_id',$product_id)->get();

        foreach($carts as $cart){

            $maxValue = $cart->stockQty;

            $existingCartQty = $cart->qty;

        }

        $request->validate([

            'cartQty' =>'required|numeric|max:'.$maxValue,

        ]);

        $product = Product::find($product_id);
        $stockQty = $product->qty;
        $inputQty = $request->input('cartQty');


        if($existingCartQty < $inputQty){

            $diff = $inputQty - $existingCartQty;

            $newQty = $stockQty - $diff;

            Product::where('id' , $product_id)->update([

                'qty' => $newQty

            ]);

        }
        else{

            $diff =  $existingCartQty - $inputQty;

            $newQty = $stockQty + $diff;

            Product::where('id' , $product_id)->update([

                'qty' => $newQty

            ]);

        }

        Cart::where('product_id',$product_id)->update([

        'qty'       => $inputQty,

        ]);

        return back();
    }

    public function cartProductDelete($id){

       $cartProduct = Cart::find($id);

       $product_id = $cartProduct ->product_id;

       $cartQty = $cartProduct->qty;

       $product = Product::find($product_id);

       $productQty = $product->qty;

       $newQty = $cartQty + $productQty;

       

       Product::where('id', $product_id )->update([

       'qty' => $newQty,
       
       ]);

        Cart::destroy($id);

        return back();
    }

    public function productDetails($id){

        $product_id = $id;

        $product     = Product::find($id);

        $orderProducts  = Cart::where('product_id', $product_id)->get();

        $totalOrder = 0;

        foreach($orderProducts as $orderProduct){

            $totalOrder = $orderProduct->qty;
        }

        return view('productDetails', compact('product'))->with('totalOrder', $totalOrder);
    }

    public function purchased()
    {

    }

    
}
