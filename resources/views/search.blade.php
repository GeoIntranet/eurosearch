@extends('layouts.main')

@section('content')
<div class="col ">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <h3 class="card-header"><i class="fa fa-clock-o mr-2"></i> Historique recherche</h3>
                <div class="card-block">
                    <h5 class="card-title">Classé par date décroissante</h5>
                    <p><a href="{{route('deleteSearch')}}" class="text-danger"><i class="fa fa-close text-danger mr-2"> </i> Effacer les recherches</a></p>
                    <ul class="square">
                        @foreach($searchs as $search)
                            <li>
                                <b>{{$search->data}}</b>  &ndash; <small>{{$search->created_at->format('D d M Y')}} </small>
                                &ndash;
                                résultat trouvé

                                <span class="badge badge-pill badge-default mr-2">0</span>

                                <a href="{{route('deleteOneSearch',['id'=>$search->id])}}" class=" ml-2 text-danger"><i class="fa fa-trash"></i></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-7 " style="background-color: white; border-radius: 5px; padding: 10px">
            @if(isset($data) AND $data <> false)
                <div class="row">
                    <div class="col">
                        <h3> <i class="fa fa-code mr-2"></i> la requette SQL</h3>
                        <p class=" mr-2"> <i class="fa fa-angle-right mr-2 b"> </i> C'est la requette qui est exécuté par le server afin de
                            rechercher les elements qui ont été écrit dans la zone de recherche.</p>
                    </div>
                </div>
                <div class="row ml-2">
                    <div class="col">
                        <p class="small"><i class="fa fa-cog mr-2"> </i> Recherche des articles du locator</p>
                        <div class="alert alert-success" role="alert">
                            <i class="fa fa-arrow-circle-o-right mr-2"> </i> {{$db[0]['query']}}
                        </div>
                    </div>
                </div>
            
                <br>
                <div class="row ">
                    <div class="col" >
                        <h3 class=""> <i class="fa fa-send mr-2"></i> Votre recherche</h3>
                    </div>
                </div>
                <div class="row ml-2">
                    <div class="col">
                        Texte écrit : <b>{{$data}}</b>  &ndash; Nombre de résultat <span class="badge badge-pill badge-primary mr-2">0</span>
                        <br>
                        <br>
                    </div>
                </div>
                @foreach($results->chunk(3) as $rowResult)
                    <div class="row ml-2">
                       @foreach($rowResult as $result)
                           <div class="col">
                               <div class="card">
                                   <div class="card-block">
                                       <i class="fa fa-tag mr-2 "> </i>{{$result->article}}
                                   </div>
                               </div>
                           </div>
                        @endforeach
                    </div><br>
                @endforeach


            @else
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info mr-0"> </i><strong>nformation</strong> : Ecrit ce que tu veux rechercher ! A toi de jouer ;)
                </div>

            @endif
        </div>
    </div>
</div>


@endsection