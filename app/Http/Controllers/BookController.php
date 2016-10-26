<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Book;
use App\BibliographicMaterial;
use App\Loanable;
use APP\LoanCategory;
use DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
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
        $   $book = new Book();
        $bibliographicMaterial = new BibliographicMaterial();
        $loanable = new Loanable();
    
        DB::BeginTransaction();
        try {    
        $loanable->barcode = $request->barcode;
        $loanable->note = $request->note;
        $loanable->state_id = $request->state_id;
        //$loanable->loan_category_id = $request->loan_category_id;
        $loanable->save();

        $bibliographicMaterial->year = $request->year;
        $bibliographicMaterial->signature = $request->signature;
        $bibliographicMaterial->publication_place = $request->publication_place;
        $bibliographicMaterial->editorial_id = $request->editorial_id;
        $bibliographicMaterial->loanable_id = $loanable->id;
        $bibliographicMaterial->save(); 

        $book->bibliographic_materials_id = $bibliographicMaterial->id;
        $book->save();

        } catch (\Exception $e) {
           DB::rollback();
           return null;
        }

        DB::commit();
        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Book::find($id);
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
       
        $book = Book::find($id);
        $bibliographicMaterial = BibliographicMaterial::find($book->bibliographic_materials_id);
        $loanable = Loanable::find($bibliographicMaterial->loanable_id);
       
        DB::beginTransaction();
        try { 

        $loanable->barcode = $request->barcode;
        $loanable->note = $request->note;
        $loanable->state_id = $request->state_id;
        //$loanable->loan_category_id = $request->loan_category_id;
        $loanable->save();

        $bibliographicMaterial->year = $request->year;
        $bibliographicMaterial->signature = $request->signature;
        $bibliographicMaterial->publication_place = $request->publication_place;
        $bibliographicMaterial->editorial_id = $request->editorial_id;
        $bibliographicMaterial->loanable_id = $loanable->id;
        $bibliographicMaterial->save(); 

        $book->bibliographic_materials_id = $bibliographicMaterial->id;
        $book->save();

        } catch (\Exception $e) {
           DB::rollback();
           return null;
        }

        DB::commit();
        return $book;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $book = Book::find($id);
        $id_BibliographicMaterial = $book->bibliographic_materials_id;
        $bibliographicMateial = BibliographicMaterial::find($id_BibliographicMaterial);
        $id_loanable = $bibliographicMateial->loanable_id;

        DB::BeginTransaction();
        try{ 
        
        Book::destroy($id);
        BibliographicMaterial::destroy($id_BibliographicMaterial);
        Loanable::destroy($id_loanable);
        
        } catch (\Exception $e) {
        DB::rollback();
        return null;
        }
        DB::commit();
        return 1;
    }
        
}
