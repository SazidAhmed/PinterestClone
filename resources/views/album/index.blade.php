@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row justify-content-center">
			<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<h3>Your Albums</h3>
								<a href="{{route('album.create')}}" class="btn btn-success float-right">Create New Album</a>
							</div>

							<div class="card-body">
								<albumlist></albumlist>
							</div>
					</div>
			</div>
	</div>
</div>
@endsection
