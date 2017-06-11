<?php

namespace App\Http\Controllers;

use App\Locator;
use App\Search;
use Illuminate\Http\Request;
use App\Http\Controllers\Searcher;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public $search;

    /**
     * SearchController constructor.
     * @param \App\Http\Controllers\Searcher $search
     */
    public function __construct(Searcher $search)
    {
        DB::enableQueryLog();
        $this->search = $search ;
    }

    public function index(Request $request)
    {
        $request->session()->reflash();
        $data = false;
        $result=collect([]);


        $result = Locator::orderby('in_datetime','DESC')->limit(0)->take(15)->get();

        if(session('data')) $data = session('data'); ;

        $searchs = Search::orderBy('created_at','DESC')->get() ;
        $db = DB::getQueryLog();

        $responses=[
            'data' =>$data,
            'db' => $db,
            'searchs' => $searchs,
            'results' => $result,
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

    public function deleteSearch( Request $request )
    {
        $request->session()->reflash();
        $searchModel = new Search();
        $searchModel->wherenotNull('data')->delete();
        return redirect()->back();
    }

    public function deleteOneSearch(Request $request , $id)
    {
        $request->session()->reflash();
        $searchModel = new Search();
        $searchModel->where('id',$id)->delete();
        return redirect()->back();
    }
}
