<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 對應model
use App\Merchandise;
// use App\Auth;
use DateTime;

// DB
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MerchandiseController extends Controller
{
    // home
    public function index()
    {
        // to show all rows of datas from table merchandises in index.blade.php
        // $products = Merchandises::get();//DB::table('merchandises')->get();

        // return view('site.index',['items'=> $products]);

        $user = Auth::user();

        if(isset(Auth::user()->id) AND $user->role == 'A'){
            // if admin is login
            return view('site.product_management');
        }else{
            // to login page
            return redirect('/')->with('error','Please login.');
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 管理員上傳商品頁面
    public function create()
    {
    //     // $user = Auth::user();

    //     // if($user->role == 'A'){
    //     //     // if admin is login
    //     //     $direct_page = 'site.product_management';
    //     // }else{
    //     //     // to login page
    //     //     $direct_page = 'auth.login';
    //     // }

        return view('site.product_management');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // save
    public function store(Request $request)
    {
        $now = new DateTime();

        $data_validate = $this->validate($request,[
            'product_name'=> 'required',
            'price'=>'required',
            'product_number' => 'required',
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:1024'
        ]);

        if($request->file('image')){
            // 請求取得上傳圖片的原始名稱
            $filename_to_store = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();
            $file_name = $filename_to_store;
            $file_path = $request->file('image')->storeAs('public/images',$file_name);

        }else{
            $file_name = 'no_image.jpeg';
        }

        // insert data into table
        $insert_merchandise = DB::table('merchandises')->insert([
            'name'=> $data_validate['product_name'],
            'price'=> $data_validate['price'],
            'amount'=> $data_validate['product_number'],
            'image_path' => $file_name,
            'created_at' => $now
        ]);

        return redirect('/')->with('success','New Product');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
   
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 更新商品
    public function update(Request $request, $id)
    {

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
}
