@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row justify-content-center">
			<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<h3>Album Categories</h3>
								<a href="" class="btn btn-success float-right" data-toggle="modal" data-target="#CreateCategoryModal">Create New Category</a>
								<catcreate></catcreate>
							</div>
							<div class="card-body">
								<catlist></catlist>
							</div>
					</div>
			</div>
	</div>
</div>
@endsection
