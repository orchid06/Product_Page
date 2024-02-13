<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;

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
            $filename      =time(). '.' .$extention;
            $file ->move('uploads/',$filename);


        }

        Product::create([

            'title'       => $request -> input('title'),
            'description' => $request -> input('description'),
            'price'       => $request -> input('price'),
            'qty'         => $request -> input('qty'),
            'image'       => $filename,


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
            $filename      =time(). '.' .$extention;
            $file ->move('uploads/',$filename);

            Product::where('id',$id)->update([

            'image'   => $filename,

            ]);
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


        }
        else{

            Product::destroy($id);
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

    public function cartProductDelete($id){

        Cart::destroy($id);

        return back();
    }

    public function cartQtyUpdate(Request $request, $id){

        $product = Product::find($id);

        $maxValue = $product->qty;

        $request->validate([

            'cartQty' =>'required|numeric|max:'.$maxValue,

        ]);

        Cart::where('id',$id)->update([

        'qty'       => $request -> input('cartQty'),
        ]);

        return back();
    }

    
}
