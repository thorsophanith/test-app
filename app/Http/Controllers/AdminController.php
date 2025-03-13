<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use App\Models\Slide;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


    //            ==========Index==========

class AdminController extends Controller{
    public function index(){
        $orders = Order::orderBy('created_at','DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
        sum(if(status='ordered', total,0)) As TotalOrderedAmount,
        sum(if(status='delivered', total,0)) As TotalDeliveredAmount,
        sum(if(status='canceled', total,0)) As TotalCanceledAmount,
        Count(*) As Total,
        sum(if(status='ordered', 1,0)) As TotalOrdered,
        sum(if(status='delivered', 1,0)) As TotalDelivered,
        sum(if(status='canceled', 1,0)) As TotalCanceled
        From Orders
        ");


        $monthlyDates = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
        IFNULL(D.TotalAmount,0) As TotalAmount,
        IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
        IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
        IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount FROM month_names M
        LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
        MONTH(created_at) As MONTHNo,
        sum(total) As TotalAmount,
        sum(if(status='ordered', total,0)) As TotalOrderedAmount,
        sum(if(status='delivered', total,0)) As TotalDeliveredAmount,
        sum(if(status='canceled', total,0)) As TotalCanceledAmount
        From Orders WHERE YEAR(created_at)=YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at) , DATE_FORMAT(created_at, '%b')
        order By MONTH(created_at)) D On D.MonthNo=M.id");


        $AmountM = implode(',', collect($monthlyDates)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDates)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDates)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDates)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDates)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDates)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDates)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDates)->sum('TotalCanceledAmount');




        return view('admin.index', compact('orders','dashboardDatas','AmountM','OrderedAmountM','DeliveredAmountM','CanceledAmountM','TotalAmount','TotalOrderedAmount','TotalDeliveredAmount','TotalCanceledAmount'));
    }

    //            ==========Brands==========

    public function brands(){
            $brands = Brand::orderBy('id','DESC')->paginate(10);
            return view("admin.brands",compact('brands'));
    }


        //            ==========Brand_Add==========

    public function brand_add(){
        return view('admin.brand-add');
    }


        //            ==========Brand_Store==========

    public function brand_store(Request $request){
     $request->validate([
          'name' => 'required',
          'slug' => 'required|unique:brands,slug',
          'image' => 'mimes:png,jpg,webp,jpeg|max:2048'
     ]);

     $brand = new Brand();
     $brand->name = $request->name;
     $brand->slug = Str::slug($request->name);
     $image = $request->file('image');
     $file_extention = $request->file('image')->extension();
     $file_name = Carbon::now()->timestamp . '.' . $file_extention;
     $this->GenerateBrandThumbnailImage($image,$file_name);
     $brand->image = $file_name;
     $brand->save();
     return redirect()->route('admin.brands')->with('status','Record has been added successfully !');
     }


        //            ==========Brand_Edit==========

public function brand_edit($id){
    $brand = Brand::find($id);
    return view('admin.brand-edit',compact('brand'));
    }


        //            ==========Brand_Update==========

public function brand_update(Request $request){
     $request->validate([
          'name' => 'required',
          'slug' => 'required|unique:brands,slug,'.$request->id,
          'image' => 'mimes:png,jpg,webp,jpeg|max:2048'
        ]);

     $brand = Brand::find($request->id);
     $brand->name = $request->name;
     $brand->slug = Str::slug($request->name);
     if($request->hasFile('image')){
        if(File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
     $image = $request->file('image');
     $file_extention = $request->file('image')->extension();
     $file_name = Carbon::now()->timestamp . '.' . $file_extention;
     $this->GenerateBrandThumbnailImage($image,$file_name);
     $brand->image = $file_name;
     }

     $brand->save();
     return redirect()->route('admin.brands')->with('status','Record has been update successfully !');
    }

public function GenerateBrandThumbnailImage($image, $file_name){
    $path = public_path('uploads/brands');
    $img = Image::read($image->getRealPath());
    $img->resize(300, 300, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })->save($path . '/' . $file_name);
    }


        //            ==========Brand_Delete==========

public function brand_delete($id){
    $brand = Brand::find($id);
    if(File::exists(public_path('uploads/brands').'/'.$brand->image))
        {
        File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Record has been delete successfully !');
    }




    //            ==========Category==========

public function categories(){
    $categories = Category::orderBy('id','DESC')->paginate(10);
    return view('admin.categories', compact('categories'));
    }

   //            ==========Category_Add==========

public function categories_add(){
    return view('admin.categories-add');
    }

   //            ==========Category_Store==========

public function categories_store(Request $request){
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:categories,slug',
        'image' => 'mimes:png,jpg,webp,jpeg|max:2048'
    ]);

   $categories = new Category();
   $categories->name = $request->name;
   $categories->slug = Str::slug($request->name);
   $image = $request->file('image');
   $file_extention = $request->file('image')->extension();
   $file_name = Carbon::now()->timestamp . '.' . $file_extention;
   $this->GenerateCategoryThumbnailImage($image,$file_name);
   $categories->image = $file_name;
   $categories->save();
   return redirect()->route('admin.categories')->with('status','Record has been added successfully !');
   }

private function GenerateCategoryThumbnailImage($image, $file_name){
    $path = public_path('uploads/categories');
    $img = Image::read($image->getRealPath());
    $img->resize(300, 300, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })->save($path . '/' . $file_name);
    }


   //            ==========Category_Edit==========

public function categories_edit($id){
    $category = Category::find($id);
    return view('admin.categories-edit',compact('category'));
    }


   //            ==========Category_Update==========

public function categories_update(Request $request){
    $request->validate([
         'name' => 'required',
         'slug' => 'required|unique:categories,slug,'.$request->id,
         'image' => 'mimes:png,jpg,webp,jpeg|max:2048'
    ]);

    $categories = Category::find($request->id);
    $categories->name = $request->name;
    $categories->slug = Str::slug($request->name);
    if($request->hasFile('image')){
       if(File::exists(public_path('uploads/categories').'/'.$categories->image))
            {
            File::delete(public_path('uploads/categories').'/'.$categories->image);
        }
    $image = $request->file('image');
    $file_extention = $request->file('image')->extension();
    $file_name = Carbon::now()->timestamp . '.' . $file_extention;
    $this->GenerateCategoryThumbnailImage($image,$file_name);
    $categories->image = $file_name;
    }

    $categories->save();
    return redirect()->route('admin.categories')->with('status','Record has been update successfully !');
    }


   //            ==========Category_Delete==========

    public function categories_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
        {
        File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Record has been delete successfully !');
    }


   //            ==========Products==========

    public function products(){
        $products = Products::orderBy('created_at','DESC')->paginate(10);
        return view("admin.products",compact('products'));
    }


   //            ==========Product_Add==========

    public function product_add(){
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|mimes:png,jpg,webp,jpeg|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required'
        ]);
    $product = new Products();
    $product->name = $request->get('name');
    $product->slug = Str::slug($request->slug);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = (int) $request->input('quantity');
    $product->category_id = $request->get('category_id');
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    if($request->hasFile('image')){
        $image = $request->file('image');
        $imageName = $current_timestamp .'.'. $image->extension();
        $this->GenerateProductThumbnailImage($image, $imageName);
        $product->image = $imageName;
    }
    $gallery_arr = array();
    $gallery_images = "";
    $counter = 1;

    if($request->hasFile('images')){
        $allowedfileExtion = ['jpg','webp', 'png', 'jpeg'];
        $files = $request->file('images');
        foreach($files as $file){
            $gextension = $file->getClientOriginalExtension();
            $gcheck = in_array($gextension,$allowedfileExtion);
            if($gcheck){
                $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                $this->GenerateProductThumbnailImage($file,$gfileName);
                array_push($gallery_arr,$gfileName);
                $counter = $counter + 1;
            }
        }
        $gallery_images = implode(',', $gallery_arr);
    }
    $product->images = $gallery_images;
    $product->save();
    return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
}

    public function GenerateProductThumbnailImage($image, $imageName){
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->getRealPath());

        $img->cover(540,689, "top");
        $img->resize(540, 689, function($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
        }


        public function product_edit($id){
            $product = Products::find($id);
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $brands = Brand::select('id', 'name')->orderBy('name')->get();
            return view('admin.product-edit',compact('product', 'categories', 'brands'));
            }


public function product_update(Request $request){
    $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|integer|min:0',
            'image' => 'mimes:png,jpg,webp,jpeg|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required'
    ]);

    $product = Products::find($request->id);
    $product->name = $request->get('name');
    $product->slug = Str::slug($request->slug);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = (int) $request->input('quantity');
    $product->category_id = $request->get('category_id');
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    if($request->hasFile('image')){
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
                (File::delete(public_path('uploads/products').'/'.$product->image));
            }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                (File::delete(public_path('uploads/products/thumbnails').'/'.$product->image));
            }
        $image = $request->file('image');
        $imageName = $current_timestamp .'.'. $image->extension();
        $this->GenerateProductThumbnailImage($image, $imageName);
        $product->image = $imageName;
    }
    $gallery_arr = array();
    $gallery_images = "";
    $counter = 1;

    if($request->hasFile('images')){
        foreach(explode(',', $product->images) as $ofile){
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                 (File::delete(public_path('uploads/products/thumbnails').'/'.$ofile));
             }
             if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)){
                 (File::delete(public_path('uploads/products/thumbnails').'/'.$ofile));
             }
        }
        $allowedfileExtion = ['jpg','webp', 'png', 'jpeg'];
        $files = $request->file('images');
        foreach($files as $file){
            $gextension = $file->getClientOriginalExtension();
            $gcheck = in_array($gextension,$allowedfileExtion);
            if($gcheck){
                $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                $this->GenerateProductThumbnailImage($file,$gfileName);
                array_push($gallery_arr,$gfileName);
                $counter = $counter + 1;
            }
        }
        $gallery_images = implode(',', $gallery_arr);
        $product->images = $gallery_images;
    }
    $product->save();
    return redirect()->route('admin.products')->with('status','Product has been updated successfully!');
}




   //            ==========Product_Delete==========

            public function product_delete($id) {
                $product = Products::find($id);

                if (!$product) {
                    return redirect()->route('admin.products')->with('status', 'Product not found.');
                }

                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
                }

                $product->delete();

                return redirect()->route('admin.products')->with('status', 'Record has been deleted successfully.');
            }

            public function coupons(){
                $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
                return view('admin.coupons' ,compact('coupons'));
            }

            public function coupon_add(){
                return view('admin.coupon-add');
            }

            public function coupon_store(Request $request){
                $request->validate([
                    'code' => 'required|unique:coupons,code',
                    'type' => 'required',
                    'value' => 'required|numeric',
                    'cart_value' => 'required|numeric',
                    'expiry_date' => 'required|date'
                ]);
                $coupon = new Coupon();
                $coupon->code = $request->code;
                $coupon->type = $request->type;
                $coupon->value = $request->value;
                $coupon->cart_value = $request->cart_value;
                $coupon->expiry_date = $request->expiry_date;
                $coupon->save();
                return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
            }


            public function coupon_edit($id){
                $coupon = Coupon::find($id);
                return view('admin.coupon-edit', compact('coupon'));
            }

            public function coupon_update(Request $request){
                $request->validate([
                    'code' => 'required|unique:coupons,code',
                    'type' => 'required',
                    'value' => 'required|numeric',
                    'cart_value' => 'required|numeric',
                    'expiry_date' => 'required|date'
                ]);
                $coupon = Coupon::find($request->id);
                $coupon->code = $request->code;
                $coupon->type = $request->type;
                $coupon->value = $request->value;
                $coupon->cart_value = $request->cart_value;
                $coupon->expiry_date = $request->expiry_date;
                $coupon->save();
                return redirect()->route('admin.coupons')->with('status', 'Coupon has been update successfully!');
            }

            public function coupon_delete($id){
                $coupon = Coupon::find($id);
                if(File::exists(public_path('uploads/products').'/'.$coupon->image))
                {
                File::delete(public_path('uploads/products').'/'.$coupon->image);
                }
                $coupon->delete();
                return redirect()->route('admin.coupons')->with('status','Record has been delete successfully !');
            }



            public function orders(){
                $orders = Order::orderBy('created_at','DESC')->paginate(12);
                return view('admin.orders', compact('orders'));
            }


            public function order_details($order_id){
                $order = Order::find($order_id);
                $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
                $transaction = Transactions::where('order_id', $order_id)->first();
                return view('admin.order-details', compact('order', 'orderItems','transaction'));
            }


            public function update_order_status(Request $request){
                $order = Order::find($request->order_id);
                $order->status = $request->order_status;
                if($request->order_status=='delivered')
                {
                    $order->delivered_date = Carbon::now();
                }
                else if($request->order_status=='canceled')
                {
                    $order->canceled_date = Carbon::now();
                }
                $order->save();
                if($request->order_status=='delivered')
                {
                    $transaction = Transactions::where('order_id',$request->order_id)->first();
                    $transaction->status = 'approved';
                    $transaction->save();
                }
                return back()->with("status", "Status changed successfully!");
            }

            public function slides(){
                $slides = Slide::orderBy('id','DESC')->paginate(12);
                return view('admin.slides', compact('slides'));
            }

            public function slide_add(){
                return view('admin.slide-add');
            }

            public function slide_store(Request $request){
                $request->validate([
                    'tagline' => 'required',
                    'title' => 'required',
                    'subtitle' => 'required',
                    'link' => 'required',
                    'status' => 'required',
                    'image' => 'required|mimes:png,jpg,webp,jpeg|max:2048'
                ]);

                $slide = new Slide();
                $slide->tagline = $request->tagline;
                $slide->title = $request->title;
                $slide->subtitle = $request->subtitle;
                $slide->link = $request->link;
                $slide->status = $request->status;

                $image = $request->file('image');
                $file_extention = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extention;
                $this->GenerateSlideThumbnailImage($image,$file_name);
                $slide->image = $file_name;
                $slide->save();
                return redirect()->route('admin.slides')->with("status","Slide added successfully!");
            }

            public function GenerateSlideThumbnailImage($image, $file_name){
                $path = public_path('uploads/slides');
                $img = Image::read($image->getRealPath());
                $img->resize(650, 650, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path . '/' . $file_name);
                }


                public function slide_edit($id){
                    $slide = Slide::find($id);
                    return view('admin.slide-edit',compact('slide'));
                    }


                    public function slide_update(Request $request){
                        $request->validate([
                            'tagline' => 'required',
                            'title' => 'required',
                            'subtitle' => 'required',
                            'link' => 'required',
                            'status' => 'required',
                            'image' => 'required|mimes:png,jpg,webp,jpeg|max:2048',
                        ]);

                        $slide = Slide::find($request->id);
                        $slide->tagline = $request->tagline;
                        $slide->title = $request->title;
                        $slide->subtitle = $request->subtitle;
                        $slide->link = $request->link;
                        $slide->status = $request->status;

                        if($request->hasFile('image')){
                            if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
                                File::delete(public_path('uploads/slides').'/'.$slide->image);
                            }
                        $image = $request->file('image');
                        $file_extention = $request->file('image')->extension();
                        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
                        $this->GenerateSlideThumbnailImage($image,$file_name);
                        $slide->image = $file_name;
                        }


                        $slide->save();
                        return redirect()->route('admin.slides')->with("status","Slide updated successfully!");
                    }



                public function slide_delete($id){
                    $slide = Slide::find($id);
                    if(File::exists(public_path('uploads/slides').'/'.$slide->image))
                    {
                    File::delete(public_path('uploads/slides').'/'.$slide->image);
                    }
                    $slide->delete();
                    return redirect()->route('admin.slides')->with('status','Slide has been delete successfully!');
                }

                public function contacts(){
                    $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
                    return view('admin.contacts', compact('contacts'));
                }

                public function contact_delete($id){
                    $contact = Contact::find($id);
                    $contact->delete();
                    return redirect()->route('admin.contacts')->with("status", "Contact deleted successfully!");
                }

               public function search(Request $request){

                $query = $request->input('query');
                $results = Products::where('name', 'LIKE', "%{$query}%")->get()->take(8);
                return response()->json($results);
            }


            public function user(){
                $orders = Order::orderBy('created_at','DESC')->get()->take(10);
                    $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
                    sum(if(status='ordered', total,0)) As TotalOrderedAmount,
                    sum(if(status='delivered', total,0)) As TotalDeliveredAmount,
                    sum(if(status='canceled', total,0)) As TotalCanceledAmount,
                    Count(*) As Total,
                    sum(if(status='ordered', 1,0)) As TotalOrdered,
                    sum(if(status='delivered', 1,0)) As TotalDelivered,
                    sum(if(status='canceled', 1,0)) As TotalCanceled
                    From Orders
                    ");
                return view('admin.user', compact('orders', 'dashboardDatas'));
            }

            public function setting(){
                return view('admin.setting');
            }
}