<?php

namespace App\Http\Controllers;

use App\Marques;
use App\Product_multi;
use App\Products;
use App\Search;

class Searcher{

    public $request;
    public $result;

    /**
     * Searcher constructor.
     */
    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * create trace search data
     * @param $request
     */
    public function init($request)
    {
        $this->request = $request ;

        if($request->input('search') <> null)
        {
            $this->search->forceCreate([
                'data' => $request->input('search'),
                'count_result' => 1,
            ]);
        }
    }

    /**
     * LOGIQUE de la recherche
     * @param $data
     * $data == input du formulaire de recherche
     */
    public function handle($data)
    {

        // maj de la chaine
        $data =strtoupper($data);

        //mise en tableau
        $dataArray = collect(explode(' ', $data));

        // recherche en base de donné toute les marque disctinct et les sortir sous forme de tableau , marque en INDEX du tableau
        $marque = Marques::all()->pluck('marque','marque');


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         *  --  Mise en place d'un tableau regroupant ref / composer / exacte / marque / autres
         **/
        // 1 --mise en forme d'un tableau de terme a recherché ?
        // le tableau est une variable de la class  :  $this->result
        foreach ($dataArray as $index => $item)
        {

            //on evite les faux espace
            if ($item === "") unset ($dataArray[$index]);
            else
            {
                //recherche d'une possible reference dans les différent mot écrit
                if($this->isReference($item))
                {
                    preg_match_all('#[0-9]+#',$item,$extractInt);
                    preg_match_all('#[A-Z]+#',$item,$extractString);
                    $plane = $this->planeRef($item);
                    $this->result['ref_exacte'] = $plane ;
                    $this->result['mot_ref_approximative'] = array_merge($extractInt[0],$extractString[0]) ;
                }
                // recherche d'une marque
                elseif(isset($marque[$item]))
                {
                    $this->result['marque'] = $marque[$item];
                }
                //autres mot a recherche
                else
                {
                    $this->result['restant'][] =$item;
                }
            }
        }


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /**
         * RECHERCHE ECRITE : imprimante zebra GX42-202410-000
         ** 1 - Recherche REF exacte ? oui ?   stop : next ;
         *  2 - Recherche Ref approximative ? oui ? stop : next ;
         *  3 - Recherche marque ? oui ? stop : next ;
         *  4 - Recherche type de materiel ? oui ? stop : next ;
         *  5 - Recherche par mot de reference ( exemple marquey ou type de materiel ).
         *  6 - renvoyer page de demande de devis
         */
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // RECHERCHE ECRITE : imprimante zebra GX42-202410-000
        // test si on a trouver dans les mot ecrit une possible ref , et on fait la requette dans la base
        // select * from `ec_products_multilingual` where `references` like '%GX42202410000%'
        $res['ref_exact'] = collect([]);
        if( isset($this->result['ref_exacte']) )
        {
            $res['ref_exact'] = Product_multi::Where('references', 'like', '%' .$this->result['ref_exacte'] . '%')->get() ;
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // test si on a trouver dans les mot ecrit une possible ref APPROXIMATIVE VIA MOT COMPOSER
        // select `title`
        // from `ec_products_multilingual`
        // where `search` like '%42%' or `search` like '%202410%' or `search` like '%000%' or `search` like '%GX%'
        $res['ref_appro'] = collect([]);
        if( $res['ref_exact']->isEmpty() && isset($this->result['mot_ref_approximative']) )
        {
            $sql = Product_multi::select('*');
            foreach ($this->result['mot_ref_approximative'] as $index => $compo)
            {
                $sql = $sql->orWhere('search', 'like', '%' .$compo . '%');
            }
            $res['ref_appro'] = $sql->get();
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // test si on a trouver dans les mot ecrit une MARQUE
        //select `title` from `ec_products_multilingual` where `search` like '%ZEBRA%'
        $res['marque'] = collect([]);
        if( $res['ref_appro']->isEmpty() && $res['ref_exact']->isEmpty() && isset($this->result['marque']) )
        {
            $res['marque'] = Product_multi::select('*')->Where('search', 'like', '%' .$this->result['marque'] . '%')->get() ;
        }


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // termes restant
        //select * from `ec_products_multilingual` where `search` like '%IMPRIMANTE%' or `title` like '%IMPRIMANTE%'
        $res['restant'] = collect([]);
        if( $res['marque']->isEmpty() && $res['ref_appro']->isEmpty() && $res['ref_exact']->isEmpty()  && isset($this->result['restant']) )
        {
            $sql = Product_multi::select('*');
            foreach ($this->result['restant'] as $index => $restant)
            {
                $sql = $sql->orWhere('search', 'like', '%' .$restant . '%');
                $sql = $sql->orWhere('title', 'like', '%' .$restant . '%');
            }
            $res['restant'] = $sql->get();
        }


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $resultat_final = collect([]);
        // on regroupe tout les different sous tableau , pour avoir une collection de donné a affiché
        foreach ($res as $index => $resultat) {
           if( ! $resultat->isEmpty()){
               foreach ($resultat as $index_ => $item) {
                   $resultat_final[] = $item;
               }
           }
        }

        return $resultat_final;

    }

    /**
     * recherche si le mot passé peut etre un ref
     * @param $item
     */
    private function isReference($item)
    {
        preg_match_all('#[0-9]+#',$item,$extractInt);
        preg_match_all('#[A-Z]+#',$item,$extractString);

        if (isset($extractInt[0][0]) && isset($extractString[0][0]))
        {
            return true ;
        }
    }

    /**
     * uniforme la reference pour virer tout les caractère spéciaux.
     * @param $item
     * @return mixed
     */
    private function planeRef($item)
    {
        $keywords_string = str_replace('-','',strtoupper($item));
        $keywords_string = str_replace('+','',$keywords_string);
        $keywords_string = str_replace('_','',$keywords_string);
        $keywords_string = str_replace('&','',$keywords_string);
        $keywords_string = str_replace('+','',$keywords_string);
        $keywords_string = str_replace('*','',$keywords_string);
        $keywords_string = str_replace('/','',$keywords_string);

        return $keywords_string ;
    }
}