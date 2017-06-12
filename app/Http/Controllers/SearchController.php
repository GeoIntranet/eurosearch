<?php

namespace App\Http\Controllers;

use App\Articles;
use App\Locator;
use App\Marques;
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
        $searchData = false;
        $data = false;

        if(session('data'))
        {
            $data = session('data');
            $searchData = $this->search->handle(session('data'));
        }

        if(empty($searchData)) $searchData = false;
        $searchs = Search::orderBy('created_at','DESC')->get() ;
        $db = DB::getQueryLog();


       $responses=[
           'data' =>$data,
           'db' => $db,
           'searchs' => $searchs,
           'searchData' => $searchData,
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


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSearch( Request $request )
    {
        $request->session()->reflash();
        $searchModel = new Search();
        $searchModel->wherenotNull('data')->delete();
        return redirect()->back();
    }

    public function getMarque()
    {
        $articles = Articles::select('art_marque')->distinct()->pluck('art_marque');
        foreach ($articles as $index => $article) {
            Marques::create(
                ['marque' => $article]
            );
        }
        dump($articles);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOneSearch(Request $request , $id)
    {
        $request->session()->reflash();
        $searchModel = new Search();
        $searchModel->where('id',$id)->delete();
        return redirect()->back();
    }


}
