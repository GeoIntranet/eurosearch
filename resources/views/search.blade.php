@extends('layouts.main')

@section('content')
    <div class="col ">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <h3 class="card-header"><i class="fa fa-clock-o mr-2"></i> Historique recherche</h3>
                    <div class="card-block">
                        <h5 class="card-title">Classé par date décroissante</h5>
                        <p><a href="{{route('deleteSearch')}}" class="text-danger"><i
                                        class="fa fa-close text-danger mr-2"> </i> Effacer les recherches</a></p>
                        <ul class="square">
                            @foreach($searchs as $search)
                                <li>
                                    <b>{{$search->data}}</b> &ndash;
                                    <small>{{$search->created_at->format('D d M Y')}} </small>
                                    <a href="{{route('deleteOneSearch',['id'=>$search->id])}}"
                                       class=" ml-2 text-danger"><i class="fa fa-trash"></i></a>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <h3><i class="fa fa-code mr-2"></i> la requette SQL</h3>
                                <p class=" mr-2"><i class="fa fa-angle-right mr-2 b"> </i> C'est la requette qui est exécuté
                                    par
                                    le server afin de
                                    rechercher les elements qui ont été écrit dans la zone de recherche.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-7 " style="background-color: white; border-radius: 5px; padding: 10px">
                @if(isset($data) AND $data <> false)
                    <br>
                    <div class="row ">
                        <div class="col">
                            <h3 class=""><i class="fa fa-send mr-2"></i> Votre recherche</h3>
                        </div>
                    </div>

                    <div class="row ml-2">
                        <div class="col">
                            Texte écrit : <b>{{$data}}</b> &ndash; Nombre de résultat <span
                                    class="badge badge-pill badge-primary mr-2">@if($searchData <> false) {{count($searchData )}}@else 0 @endif</span>
                            <br>
                            <br>
                        </div>
                    </div>
                @php $i=1; @endphp
                    @if($searchData <> false)
                        @foreach($searchData->chunk(6) as $rowResult)
                            <div class="row ml-2">
                                @foreach($rowResult as $result)
                                    <div class="col">
                                        <div class="card p-0">
                                            <div class="card-block  p-2">
                                                <b>{{$i++}}</b> - {{$result->search}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div><br>
                        @endforeach
                    @endif


                @else
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info mr-0"> </i><strong>nformation</strong> : Ecrit ce que tu veux rechercher !
                        A toi de jouer ;)
                    </div>

                @endif

            </div>

            <br>


        </div>
    </div>



@endsection