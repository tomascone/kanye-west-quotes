<?php

namespace App\Http\Controllers;

use App\Models\FavouriteQuote;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ApiQuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $num
     * @return \Illuminate\Http\Response
     */
    public function index(int $num = 1)
    {
        $quotes = [];
        $response = Http::get('https://api.kanye.rest/text');

        for ($i=0; $i < $num; $i++) { 
            $response = Http::get('https://api.kanye.rest/text');
            $quotes[] = $response->body();
        }

        return $quotes;
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
        return FavouriteQuote::firstOrCreate([
            'quote'     => $request->quote,
            'user_id'   => $request->user()->id,
        ]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
