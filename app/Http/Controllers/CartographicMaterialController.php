<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CartographicMaterial;
use App\BibliographicMaterial;
use App\Loanable;
use App\CartographicMaterialKeyWord;
use App\LoanCategory;

class CartographicMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CartographicMaterial::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bibliographicMaterial = new BibliographicMaterial();
        $loanable = new Loanable();
        $cartographicMaterial = new CartographicMaterial();
        $cartographicMaterialKeyWord =  new CartographicMaterialKeyWord;
        $loanCategory = new LoanCategory();
        
        $loanCategory->limit_time = $request->limit_time;
        $loanCategory->name = $request->name;
        $loanCategory->max_hours = $request->max_hours;
        $loanCategory->save();
        
        $loanable->barcode = $request->barcode;
        $loanable->note = $request->note;
        $loanable->state_id = $request->state_id;
        $loanable->loan_category_id = $loan_category_id->id;
        $loanable->save();
        
        $loanableId = Loanable::where('barcode', $request->barcode)->first->id;

        $bibliographicMaterial->year = $request->year;
        $bibliographicMaterial->signature = $request->signature;
        $bibliographicMaterial->publication_place = $request->publication_place;
        $bibliographicMaterial->editorial_id = $request->editorial_id;
        $bibliographicMaterial->loanable_id = $loanableId;        
        $bibliographicMaterial->save();
        
        $cartographicMaterial->bibliographic_materials_id = $bibliographicMaterial->id;        
        $cartographicMaterial->cartographic_format_id = $request->cartographic_format_id;
        $cartographicMaterial->dimension = $request->dimension;
        $cartographicMaterial->save();
        
        $cartographicMaterialKeyWord->key_word_id = $request->key_word_id;
        $cartographicMaterialKeyWord->cartographic_material_id = $cartographicMaterial->id;
        $cartographicMaterialKeyWord->save();
        
        return $cartographicMaterial; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CartographicMaterial::find($id);
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
        $cartographicMaterial = CartographicMaterial::find($id);
        $bibliographicMaterial = BibliographicMaterial::find($cartographicMaterial->bibliographic_materials_id);
        $loanable = Loanable::find($cartographicMaterial->loanable_id);       
        $loanCategory = new LoanCategory();
        
        $loanCategory->limit_time = $request->limit_time;
        $loanCategory->name = $request->name;
        $loanCategory->max_hours = $request->max_hours;
        $loanCategory->save();
        
        $loanable->barcode = $request->barcode;
        $loanable->note = $request->note;
        $loanable->state_id = $request->state_id;
        $loanable->loan_category_id = $loan_category_id->id;
        $loanable->save();
        
        $loanableId = Loanable::where('barcode', $request->barcode)->first->id;

        $bibliographicMaterial->year = $request->year;
        $bibliographicMaterial->signature = $request->signature;
        $bibliographicMaterial->publication_place = $request->publication_place;
        $bibliographicMaterial->editorial_id = $request->editorial_id;
        $bibliographicMaterial->loanable_id = $loanableId;        
        $bibliographicMaterial->save();
        
        $cartographicMaterial->bibliographic_materials_id = $bibliographicMaterial->id;        
        $cartographicMaterial->cartographic_format_id = $request->cartographic_format_id;
        $cartographicMaterial->dimension = $request->dimension;
        $cartographicMaterial->save();        
        
        return $cartographicMaterial;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartographicMaterial = CartographicMaterial::find($id);
        $id_bibliographicMaterial = $cartographicMaterial->bibliographic_materials_id;
        $id_loanable = $cartographicMaterial->loanable_id;
        
        
               
        DB::table('cartographic_material_key_words')->where('cartographic_material_id', $cartographicMaterial->id)->delete();
        
        $del1 = CartographicMaterial::destroy($id);
        $del2 = BibliographicMaterial::destroy($id_bibliographicMaterial);
        $del3 = Loanable::destroy($id_loanable);
		if($del1==true && $del2==true && $del3==true) {
		return 1;
		}
		return 0;
    }
}
