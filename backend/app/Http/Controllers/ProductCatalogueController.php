<?php

namespace App\Http\Controllers;

use App\ProductCatalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductCatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product_catalogues = ProductCatalogue::orderBy('order', 'ASC')->where('status', 1)->get();
        return view('pages.quan-ly-danh-muc', compact('product_catalogues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pages.them.them-danh-muc-san-pham');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $product_catalogue = new ProductCatalogue();
        $product_catalogue->name = $request->name;
        $product_catalogue->order = $request->order;
        $product_catalogue->status = $request->status;
        $product_catalogue->save();
        return redirect()->route('danh-muc-san-pham.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product_catalogue = ProductCatalogue::find($id);
        return view('pages.cap-nhat.cap-nhat-danh-muc-san-pham', compact('product_catalogue'));
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
        //
    }


    public function update_product_catalogue(Request $request, $id)
    {
        //
        $product_catalogue = ProductCatalogue::find($id);
        $product_catalogue->name = $request->name;
        $product_catalogue->order = $request->order;
        $product_catalogue->status = $request->status;
        $product_catalogue->save();
        return redirect()->route('danh-muc-san-pham.index');
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

    public function destroy_product_catalogue($id)
    {
        $product_catalogue = ProductCatalogue::find($id);
        $product_catalogue->status = 0;
        $product_catalogue->save();
        return redirect()->route('danh-muc-san-pham.index');

    }
    //api
    public function getProductCatalogues(){
        $product_catalogues = ProductCatalogue::where('status', 1)->get();
        return response()->json(['data'=>  $product_catalogues ], 200);
    }
}
