<?php

namespace App\Http\Controllers;

use App\Search;
use Illuminate\Http\Request;
use App\Http\Controllers\Searcher;

class SearchController extends Controller
{
    public $search;

    /**
     * SearchController constructor.
     * @param \App\Http\Controllers\Searcher $search
     */
    public function __construct(Searcher $search)
    {
        $this->search = $search ;
    }

    public function index()
    {
        $data = false;
        if(session('data')) $data = session('data'); ;

        $responses=[
            'data' =>$data,
            'searchs' => Search::orderBy('created_at','DESC')->get()
        ];


        return view('search',$responses);
    }

    /**
     * @param Request $request
     */
    public function search(Request $request)
    {

        $this->search->init($request);

        $request->session()->flash('data', $request->input('search'));

        return redirect()->route('getSearch');
    }

    public function oldSearch()
    {
        
    }
}
