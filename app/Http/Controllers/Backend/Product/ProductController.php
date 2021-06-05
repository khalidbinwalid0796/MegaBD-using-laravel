<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin\Subcategory;
use App\Model\Admin\Category;
use DB;
use Image;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
    	$product=DB::table('products')
        ->join('categories','products.category_id','categories.id')
        ->join('brands','products.brand_id','brands.id')
        ->select('products.*','categories.category_name','brands.brand_name')
        ->get();
        return view('backend.pages.product.index',compact('product'));

    }

    public function create()
    {
    	$category=DB::table('categories')->get();
    	$brand=DB::table('brands')->get();
    	return view('backend.pages.product.create',compact('category','brand'));
    }

  //subcategory collect by ajax request
    public function GetSubcat($category_id)
    {
    	$cat = DB::table("subcategories")->where("category_id",$category_id)->get();
        return response()->json($cat);
    }

    public function store(Request $request)
    {    

        $this->validate($request, [
          //'product_name'  => 'required|unique:products',
          //'product_code'  => 'required|unique:products',
          //'product_quantity'  => 'required|unique:products',
          //'category_id'  => 'required|unique:products',
          //'subcategory_id'  => 'required|unique:products',
          //'brand_id'  => 'required|unique:products',
          //'product_size'  => 'required|unique:products',
          //'product_color'  => 'required|unique:products',
          //'selling_price'  => 'required|unique:products',
         // 'product_details'  => 'required|unique:products',
         // 'video_link'  => 'required|unique:products',
         // 'image_one'  => 'required|image',
         // 'image_two'  => 'nullable|image',
         // 'image_three'  => 'nullable|image',
        ],
        [
         // 'image_one.required'  => 'Please provide a valid image with .jpg, .png, .gif, .jpeg exrension in main thumbnail..',
         // 'image_two.image'  => 'Please provide a valid image with .jpg, .png, .gif, .jpeg exrension..',
         // 'image_three.image'  => 'Please provide a valid image with .jpg, .png, .gif, .jpeg exrension..',
        ]);

        $data=array();
    	$data['product_name']=$request->product_name;
    	// $data['product_slug']=Str::slug($request->product_name, '-');
    	$data['product_code']=$request->product_code;
    	$data['product_quantity']=$request->product_quantity;
    	$data['category_id']=$request->category_id;
    	$data['subcategory_id']=$request->subcategory_id;
    	$data['brand_id']=$request->brand_id;
    	$data['product_size']=$request->product_size;
    	$data['product_color']=$request->product_color;
    	$data['selling_price']=$request->selling_price;
    	$data['product_details']=$request->product_details;
    	$data['video_link']=$request->video_link;
    	$data['main_slider']=$request->main_slider;
    	$data['hot_deal']=$request->hot_deal;
    	$data['best_rated']=$request->best_rated;
    	$data['trend']=$request->trend;
    	$data['mid_slider']=$request->mid_slider;
    	$data['hot_new']=$request->hot_new;
        $data['buyone_getone']=$request->buyone_getone;
    	$data['status']=1;

    	$image_one=$request->image_one;
    	$image_two=$request->image_two;
    	$image_three=$request->image_three;



    if($image_one && $image_two && $image_three){

            $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
                Image::make($image_one)->resize(230,300)->save('public/products/'.$image_one_name);
                $data['image_one']='public/products/'.$image_one_name;

            $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
                Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
                $data['image_two']='public/products/'.$image_two_name; 

            $image_three_name= uniqid().'.'.$image_three->getClientOriginalExtension();
                Image::make($image_three)->resize(230,300)->save('public/products/'.$image_three_name);
                $data['image_three']='public/products/'.$image_three_name; 
                
                $product=DB::table('products')->insert($data);
                    $notification=array(
                     'messege'=>'Successfully Product Inserted ',
                     'alert-type'=>'success'
                    );
                return Redirect()->back()->with($notification);   
        }
        if($request->has('image_one') && $request->has('image_two')){
            
           $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->resize(230,300)->save('public/products/'.$image_one_name);
           $data['image_one']='public/products/'.$image_one_name;
            

           $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
           $data['image_two']='public/products/'.$image_two_name;

           $product=DB::table('products')->insert($data);
            $notification=array(
                     'messege'=>'Image One and Two Inserted ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
           


        }
   
		if($request->has('image_one')) {

           $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->resize(300,300)->save('public/products/'.$image_one_name);
           $data['image_one']='public/products/'.$image_one_name;
           $product=DB::table('products')->insert($data);
            $notification=array(
                     'messege'=>'Image One Inserted ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);


        }if($request->has('image_two')) {

           $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
           $data['image_two']='public/products/'.$image_two_name;
           $product=DB::table('products')->insert($data);
            $notification=array(
                     'messege'=>'Image Two Inserted ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);

        }if($request->has('image_three')) {

           $image_three_name= uniqid().'.'.$image_three->getClientOriginalExtension();
           Image::make($image_three)->resize(230,300)->save('public/products/'.$image_three_name);
           $data['image_three']='public/products/'.$image_three_name;
           $product=DB::table('products')->insert($data);
            $notification=array(
                     'messege'=>'Image Three Inserted ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);

        }

    }

    public function Inactive($id)
    {
         DB::table('products')->where('id',$id)->update(['status'=> 0]);
         $notification=array(
                     'messege'=>'Successfully Product Inactive ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);  
    }

    public function Active($id)
    {
         DB::table('products')->where('id',$id)->update(['status'=> 1]);
         $notification=array(
                     'messege'=>'Successfully Product Aactive ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);
    }

    public function DeleteProduct($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        $image1=$product->image_one;
        $image2=$product->image_two;
        $image3=$product->image_three;
        unlink($image1);
        unlink($image2);
        unlink($image3);
        DB::table('products')->where('id',$id)->delete();
        $notification=array(
                     'messege'=>'Successfully Product Deleted ',
                     'alert-type'=>'success'
                    );
         return Redirect()->back()->with($notification);

    }

    public function ViewProduct($id)
    {
        $product=DB::table('products')
                ->join('categories','products.category_id','categories.id')
                ->join('brands','products.brand_id','brands.id')
                ->join('subcategories','products.subcategory_id','subcategories.id')
                ->select('products.*','categories.category_name','brands.brand_name','subcategories.subcategory_name')
                ->where('products.id',$id)
                ->first();
        return view('backend.pages.product.view',compact('product'));

    }

    public function EditProduct($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        return view('backend.pages.product.edit',compact('product'));
    }

    public function UpdateProductWithoutPhoto(Request $request,$id)
    {



        $data=array();
        $data['product_name']=$request->product_name;
        $data['product_code']=$request->product_code;
        $data['product_quantity']=$request->product_quantity;
        $data['category_id']=$request->category_id;
        $data['discount_price']=$request->discount_price;
        $data['subcategory_id']=$request->subcategory_id;
        $data['brand_id']=$request->brand_id;
        $data['product_size']=$request->product_size;
        $data['product_color']=$request->product_color;
        $data['selling_price']=$request->selling_price;
        $data['product_details']=$request->product_details;
        $data['video_link']=$request->video_link;
        $data['main_slider']=$request->main_slider;
        $data['hot_deal']=$request->hot_deal;
        $data['best_rated']=$request->best_rated;
        $data['trend']=$request->trend;
        $data['mid_slider']=$request->mid_slider;
        $data['hot_new']=$request->hot_new;
        $data['buyone_getone']=$request->buyone_getone;
        
        $update=DB::table('products')->where('id',$id)->update($data);
        if ($update) {
             $notification=array(
                     'messege'=>'Successfully Product Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);

        }else{
            $notification=array(
                     'messege'=>'Nothing To Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
        }
    }

    public function UpdateProductPhoto(Request $request,$id)
    {
        $old_one=$request->old_one;
        $old_two=$request->old_two;
        $old_three=$request->old_three;

        $image_one=$request->image_one;
        $image_two=$request->image_two;
        $image_three=$request->image_three;
        $data=array();

		if($request->has('image_one') && $request->has('image_two') && $request->has('image_three')){
           unlink($old_one);

           $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->resize(230,300)->save('public/products/'.$image_one_name);
           $data['image_one']='public/products/'.$image_one_name;
            
           $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
           $data['image_two']='public/products/'.$image_two_name;

            $image_three_name= uniqid().'.'.$image_three->getClientOriginalExtension();
           Image::make($image_three)->resize(230,300)->save('public/products/'.$image_three_name);
           $data['image_three']='public/products/'.$image_three_name;
            DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One and Two and Three Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
          

        }
    	if($request->has('image_one') && $request->has('image_two')){
            
           unlink($old_one);
           $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->resize(230,300)->save('public/products/'.$image_one_name);
           $data['image_one']='public/products/'.$image_one_name;
            
           $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
           $data['image_two']='public/products/'.$image_two_name;

           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One and Two Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);
           
        }
		if($request->has('image_one')) {

          	unlink($old_one);
           $image_one_name= uniqid().'.'.$image_one->getClientOriginalExtension();
           Image::make($image_one)->resize(300,300)->save('public/products/'.$image_one_name);
           $data['image_one']='public/products/'.$image_one_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image One Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);


        }if($request->has('image_two')) {
  	           $image_two_name= uniqid().'.'.$image_two->getClientOriginalExtension();
           Image::make($image_two)->resize(230,300)->save('public/products/'.$image_two_name);
           $data['image_two']='public/products/'.$image_two_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image Two Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);

        }if($request->has('image_three')) {
           
           $image_three_name= uniqid().'.'.$image_three->getClientOriginalExtension();
           Image::make($image_three)->resize(230,300)->save('public/products/'.$image_three_name);
           $data['image_three']='public/products/'.$image_three_name;
           DB::table('products')->where('id',$id)->update($data);
            $notification=array(
                     'messege'=>'Image Three Updated ',
                     'alert-type'=>'success'
                    );
             return Redirect()->route('all.product')->with($notification);


        }

	}
}