<?php

namespace App\Http\Controllers;

use App\Search;

class Searcher{

    public $request;

    /**
     * Searcher constructor.
     */
    public function __construct(Search $search)
    {
        $this->search = $search;
    }


    public function init($request)
    {
        $this->request = $request ;

        if($request->input('search') <> null){
            $this->search->forceCreate([
                'data' => $request->input('search'),
                'count_result' => 1,
            ]);
        }



    }

    public function search()
    {
        // logique de la recherche

    }
}