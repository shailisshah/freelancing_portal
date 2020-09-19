@extends('admin.layouts.admin-sidebar')

@section('content')
<div class="container">
    <div class="panel-body">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
    </div>
    <br>
    <h1>Manages Punches</h1>
    <br>
    <a href="{{route('admin.post-projects.create')}}" class='btn btn-success btn-sm'>Create</a>
    <br><br><br>
    <table id="projects" class="table table-hover table-condensed" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection