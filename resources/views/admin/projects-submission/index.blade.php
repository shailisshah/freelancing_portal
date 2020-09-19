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
    <h1>Projects Submission</h1>
    <br><br><br>
    <table id="projects-submission" class="table table-hover table-condensed" style="width:100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Assigned To</th>
                <th>Project Submission Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection