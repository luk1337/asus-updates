@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @foreach($devices as $device)
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $device->name }}</div>
                    <div class="panel-body">
                        <table class="table table-responsive table-nomargin">
                            <thead>
                                <th>Category</th>
                                <th>Version</th>
                                <th>Release date</th>
                                <th>Description</th>
                                <th>Download</th>
                            </thead>
                            <tbody>
                                @foreach($firmwares->where('device.id', $device->id) as $firmware)
                                    <tr>
                                        <td>{{ $firmware->category->name }}</td>
                                        <td>{{ $firmware->version }}</td>
                                        <td>{{ $firmware->release_date }}</td>
                                        <td><a href="#">Description</a></td>
                                        <td><a href="{{ $firmware->url }}">Download</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
