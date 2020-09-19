@extends('admin.layouts.admin-sidebar')

@section('content')
<?php

use App\Models\User;
use App\Models\Projects;
use App\Models\Roles;
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <br>
                <div class="panel-heading"><h3>Project Contents</h3></div>
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
                </div>
                <br>
                <hr>
                <?php if (isset($ProjectsSubmissionsHistory[0]->id)) { ?>
                    <div class="panel-heading"><h3>Project Submission Details</h3></div>

                    <div class="panel-body">
                        <?php foreach ($ProjectsSubmissionsHistory as $k => $v) { ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="messages" class="control-label">Messages</label>
                                    <div class="form-group">
                                        {{$v->messages}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="documents" class="control-label">Documents</label>
                                    <div class="form-group">
                                        <?php
                                        $docs = [];
                                        if ($v->documents)
                                            $docs = explode(',', $v->documents);
                                        foreach ($docs as $kd => $vd) {
                                            ?>
                                            <div><a target="_blank" href="{{URL('/') . '/uploads/documents/' . $v->projects_id . '/' .$vd}}">Document {{$kd+1}}</a></div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="messages" class="control-label">Written BY</label>
                                    <div class="form-group">
                                        {{User::getUserName($v->created_by)}}
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="panel-body">
                    {{ Form::open(['route' => 'admin.projects-submission.store','method' => 'post','enctype' => 'multipart/form-data',  'autocomplete' => 'off']) }}
                    {{ csrf_field() }}
                    <input type="hidden" name="project_id" value="{{$project->id}}" >
                    <?php if (Auth::user()->role_id == Roles::ROLE_DESIGNER) { ?>
                        <div class="form-group{{ $errors->has('project_submission_status') ? ' has-error' : '' }}">
                            <label for="project_submission_status" class="col-md-6 control-label">Project Submission Status</label>

                            <div class="col-md-6">
                                {{ Form::select('project_submission_status', Projects::$ProjectSubmissionStatus, old('project_submission_status'), ['class' => 'form-control']) }}
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="project_submission_status" value="" >
                    <?php } ?>
                    <div class="form-group{{ $errors->has('messages') ? ' has-error' : '' }}">
                        <label for="messages" class="col-md-6 control-label">Messages</label>

                        <div class="col-md-9">
                            {{ Form::textarea('messages',old('messages'),['class'=>'form-control', 'rows' => 10, 'cols' => 70]) }}

                            @if ($errors->has('messages')) 
                            <strong>{{ $errors->first('messages') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <?php if (Auth::user()->role_id == Roles::ROLE_DESIGNER) { ?>
                        <div class="form-group">
                            <label for="documents" class="col-md-6 control-label">Documents</label>

                            <div class="col-md-6">
                                <input type="file" name="documents[]" class="form-control" multiple="true">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                SAVE
                            </button>
                            <a type="button" class="btn btn-default" href="{{ route('admin.projects-submission.index') }}">
                                Back
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
