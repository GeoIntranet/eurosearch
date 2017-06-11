@extends('layouts.main')

@section('content')

    <div class="col-3">
        <div class="card">
            <h3 class="card-header"><i class="fa fa-clock-o mr-2"></i> Historique recherche</h3>
            <div class="card-block">
                <h5 class="card-title">Classé par date décroissante</h5>
                <ul class="square">
                    @foreach($searchs as $search)
                        <li>{{$search->data}} - {{$search->created_at->format('d-m-y')}}</li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    <div class="col-7">
        @if(isset($data) AND $data <> false)
            <div class="row">
                <div class="col">
                    <h3> <i class="fa fa-search mr-2"></i> Votre recherche</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {{$data}}
                </div>
            </div>

        @else
            <div class="alert alert-danger" role="alert">
                {{--<i class="fa fa-exclamation-circle mr-2"> </i><strong>Opps!</strong> Historique recherche.--}}
                <i class="fa fa-exclamation-circle mr-2"> </i><strong>Opps!</strong> Aucune recherche actuellement.
            </div>
        @endif
    </div>
@endsection