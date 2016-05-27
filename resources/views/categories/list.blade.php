@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Categories</div>

                <div class="panel-body">
                    @if(count($categories) == 0)
                        <strong>No categories added, yet!</strong>
                    @else
                        <table class="table table-responsive table-nomargin">
                            <thead>
                            <th>Category name</th>
                            <th>XPath</th>
                            <th>Actions</th>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->xpath }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-primary" href="{{ url('categories/edit/' . $category->id ) }}">Edit</a>
                                        <a class="btn btn-xs btn-danger" href="{{ url('categories/delete/' . $category->id ) }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
