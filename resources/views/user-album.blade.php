@extends('layouts.app')

@section('content')
<div class="container">
         
@if($userBgPic)
<img src="{{Storage::url($userBgPic)}}" style="width: 100%;">

  @else
<img src="{{asset('banner')}}/banner.jpg" style="width: 100%;"> 
 
@endif   


       

            <br><br>
            @if(Auth::check()&&auth()->user()->id!=$userId)

                <follow user-id="{{$userId}}" follows="{{$follows}}"></follow>
            @endif

    <div class="row">
        @foreach($albums as $album)

        <div class="col-lg-3">
            <div class="card">
            <img src="{{asset('album')}}/{{$album->image}}" class="card-img-top">

            <div class="card-body">
                <h5 class="card-title"><center>{{$album->name}}</center></h5>
                <center>
                    <a href="{{route('view.album',[$album->slug,$album->id])}}" class="btn btn-primary">View Album</a>
                </center>
            </div>
        </div>

    </div>
    
        @endforeach

    </div>

        <br>
        <br>


</div>
@endsection
