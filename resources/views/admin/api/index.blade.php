@extends('layouts.app')

@section('title', 'Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-api-index') }}
@endsection

@section('content')
    <h1>Api Application Management</h1>

    <p>
        Here you can create new Api Applications to interface with Digiplay. You can also revoke access for applications as well as get the keys for applications. Be sure to check out the documenation for the different methods available!
    </p>

    <h2>Create</h2>

    <form class="form mb-sm-2" action="{{ route('admin-api-create') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>
    
        <button class="btn btn-warning" type="submit">Create</button>
    </form>

    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Key</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
                <tr>
                    <td>{{ $application->name }}</td>
                    <td>{{ $application->key }}</td>
                    <td>
                        <a class="btn btn-warning" href="{{ route('admin-api-delete', $application->id) }}">
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection('content')