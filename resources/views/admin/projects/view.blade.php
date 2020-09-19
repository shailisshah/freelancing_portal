@extends('admin.layouts.admin-sidebar')

@section('content')
<?php

use App\Models\User;
use App\Models\Projects;
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <br>
                <div class="panel-heading"><h3>Project Details</h3></div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="documents" class="control-label">Title</label>
                            <div class="form-group">
                                {{$project->title}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="documents" class="control-label">Description</label>
                            <div class="form-group">
                                {!! $project->description !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="documents" class="control-label">Due Date</label>
                            <div class="form-group">
                                {{$project->due_date}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="documents" class="control-label">Assgned To</label>
                            <div class="form-group">
                                {{User::getUserName($project->assigned_to)}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="documents" class="col-md-6 control-label">Uploaded Documents</label>
                        <div class="col-md-6">
                            <?php foreach ($uploaded_documents as $k => $v) { ?>
                                <div><a target="_blank" href="{{$v}}">Document {{$k+1}}</a></div>
                            <?php }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="documents" class="col-md-6 control-label">Project Submission Status</label>
                        <div class="col-md-6">
                            {{!empty($project->project_submission_status)?Projects::$ProjectSubmissionStatus[$project->project_submission_status]:'Not Submitted Yet'}}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a type="button" class="btn btn-default" href="{{ route('admin.projects.index') }}">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
