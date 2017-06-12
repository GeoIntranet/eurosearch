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
        $searchData = false;
        $result=collect([]);

        if(session('data'))
        {
            $data = session('data');
            $searchData = $this->searchData(session('data'));
        }

        $searchs = Search::orderBy('created_at','DESC')->get() ;

        $db = DB::getQueryLog();



        $responses=[
            'data' =>$data,
            'db' => $db,
            'searchs' => $searchs,
            'searchData' => $searchData,
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

    private function searchData($request)
    {
        $keywords_string = str_replace('-',' ',strtoupper($request));
        $keywords_string = str_replace('+',' ',$keywords_string);
        $keywords_array = explode(' ',$keywords_string);

        if ( sizeof($keywords_array) == 1 )
        {
            preg_match_all('#[0-9]+#',$keywords_string,$extractInt);
            preg_match_all('#[A-Z]+#',$keywords_string,$extractString);

            //jamais valider : strtoupper($request) / mise en majuscule de la chaine de char
            //preg_match_all('#[a-z]+#',$keywords_string,$extractString);

            // throw exeception , on accede pas a une valeur qui n'existe pas.
            //$int = $extractInt[0][0];
            //$string = $extractString[0][0];

            if (isset($extractInt[0][0]) && isset($extractString[0][0]))
            {
                $int = $extractInt[0][0];
                $string = $extractString[0][0];

                $keywords_string = $string.' '.$int;
                $keywords_array = explode(' ',$keywords_string);
            }
            else
                {
                $keywords_array = $keywords_array;
            }
        }

        $where = '';

        $i = 0;
        foreach ($keywords_array as $key=>$value){
            $value = strtolower($value);
            if ($i == 0)
                $where .= "LOWER(pm.search) LIKE '%$value%' OR LOWER(pm.references) LIKE '%$value%'";
            else
                $where .= "AND (LOWER(pm.search) LIKE '%$value%' OR LOWER(pm.references) LIKE '%$value%' )";
            $i++;
        }

        $sql_products = "
		SELECT
		p.id as id_product,
		pm.title,
		pm.text
		FROM
		products p
		INNER JOIN products_multilingual pm ON pm.id_product = p.id
		WHERE
		p.id is not null
		AND ($where)
		AND id_language = 1
		GROUP BY p.id
		";
        return [
            'where' => $where,
            'sql' => $sql_products,
            'data' => $keywords_array
        ];


    }
}
