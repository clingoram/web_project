<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 對應model
use App\Merchandise;
use App\Shopcart;
use DateTime;

// DB
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShopcartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(Auth::check()){
    //         $user = Auth::user();
    //         // return view('member.shop_cart',['member'=> $user->id]);

    //         // 取得登入會員購物車內的資料
    //         $member_cartData = DB::table('shopcarts AS cart',)
    //                         ->join('merchandises AS product', function ($join) use ($id) {
    //                             $join->on('cart.merchandise_id', '=', 'product.id')
    //                                 ->where('cart.userid', '=',$user->id)
    //                                 ->orderBy('cart.created_at', 'desc');
    //                         })
    //                         ->get();
    //         return $member_cartData;
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if user is login or not
        if(Auth::check()){

            // if user is login
            $user = Auth::user();

            $data = $this->validate($request,[
                'purchase_number' => 'required',
                'product_id' => 'required'
            ]);

            // 取得點選商品頁的商品id
            // $merchandise_data = DB::table('merchandises')->find($data['product_id']);
            $merchandise_data = Merchandise::find($data['product_id']);
 
            // 年月日
            $now = new DateTime();
            
            // insert data into table
            $insert_data = DB::table('shopcarts')->insert([
                // 售價
                'price' => $merchandise_data->price,
                // 欲購買數量
                'total_purchase_item' => $data['purchase_number'],
                // 總價
                'total_price' => $merchandise_data->price * $data['purchase_number'],
                // 商品ID
                'merchandise_id' => $merchandise_data->id,
                // 建立時間
                'created_at' => $now,
                // 會員ID
                'userid' => $user->id
            ]);
            return redirect('product')->with('success','add');
            
        }else {
            // direct to login page
            return view('auth.login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        if(Auth::check() AND $user->id == $id ) {
            // 取得登入會員購物車內的資料
            $member_cartData = DB::table('shopcarts AS cart',)
                            ->join('merchandises AS product', function ($join) use ($id) {
                                $join->on('cart.merchandise_id', '=', 'product.id')
                                    ->where('cart.userid', '=',$id)
                                    ->orderBy('cart.created_at', 'desc');
                            })
                            ->get();
            // 計算購物車總金額
            $total = DB::table('shopcarts AS cart')->where('cart.userid', '=',$id)->sum('total_price');

            $shopcartdata = [
                'list' => $member_cartData,
                'total_cost' => $total
            ];

            // return view('member.shop_cart')->with('shopcartdata',$shopcartdata);
            return view('member.shop_cart',compact('shopcartdata'));

            // return ShopcartResource::collection($shopcartdata);
            // return $shopcartdata;

        }
        return view('auth.login');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cart = Shopcart::find($id);

        // 改url $cart id
        if(!isset($cart) OR Auth::user()->id !== $cart->userid){
            return redirect('/')->with('error','Error!!The permission is denied.');
        }
        return view('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // user 更改選購的商品數量
        $user = Auth::user();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $userId = Auth::user()->id;
        $shopCart = DB::table('shopcarts')->where('merchandise_id','=',$id)->delete();

        // if($userId !== $shopCart->userid) {
        //     return redirect('/')->with('error','Error!!The permission is denied.');
        // }
        // $shopCart->delete();

        return redirect('/')->with('success','Product removed!!');

    }
}
