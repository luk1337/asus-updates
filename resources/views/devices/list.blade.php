@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Devices
                    <a class="btn btn-xs btn-success pull-right" href="{{ url('devices/add') }}">Add device</a>
                </div>

                <div class="panel-body table-responsive">
                    @if(count($devices) == 0)
                        <strong>No devices added, yet!</strong>
                    @else
                        @set('num', 0)
                        <table class="table table-nomargin">
                            <thead>
                            <th>#</th>
                            <th>Device name</th>
                            <th>URL</th>
                            <th>Actions</th>
                            </thead>
                            <tbody>
                            @foreach($devices as $device)
                                @set('num', $num += 1)
                                <tr>
                                    <td>{{ $num }}</td>
                                    <td>{{ $device->name }}</td>
                                    <td><a href="{{ $device->url }}">{{ $device->url }}</a></td>
                                    <td>
                                        <a class="btn btn-xs btn-primary" href="{{ url('devices/edit/' . $device->id ) }}">Edit</a>
                                        <a class="btn btn-xs btn-danger" href="{{ url('devices/delete/' . $device->id ) }}">Delete</a>
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
