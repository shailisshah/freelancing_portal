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
    <h1>Projects</h1>
    <br>
    <table id="designer" class="table table-hover table-condensed" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection