@extends('admin.layouts.admin-sidebar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <br>
                <div class="panel-heading"><h3>Create Project</h3></div>

                <div class="panel-body">

                    @if(isset($project))
                    {{ Form::model($project, ['route' => ['admin.post-projects.update', $project->id], 'method' => 'patch', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off']) }}
                    @else
                    {{ Form::open(['route' => 'admin.post-projects.store','method' => 'post','enctype' => 'multipart/form-data',  'autocomplete' => 'off']) }}
                    @endif

                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="title" class="col-md-6 control-label">Title</label>

                        <div class="col-md-6">
                            {{ Form::text('title',old('title'), ['class'=>'form-control']) }}
                            @if ($errors->has('title')) 
                            <strong>{{ $errors->first('title') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description" class="col-md-9 control-label">Description</label>

                        <div class="col-md-9">
                            {{ Form::textarea('description',old('description'),['class'=>'form-control', 'rows' => 10, 'cols' => 70]) }}

                            @if ($errors->has('description')) 
                            <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                        <label for="due_date" class="col-md-6 control-label">Due Date</label>

                        <div class="col-md-6">
                            {{ Form::text('due_date',old('due_date'), ['class'=>'form-control datepicker']) }}
                            @if ($errors->has('due_date')) 
                            <strong>{{ $errors->first('due_date') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('assigned_to') ? ' has-error' : '' }}">
                        <label for="assigned_to" class="col-md-6 control-label">Assigned To</label>

                        <div class="col-md-6">
                            {{ Form::select('assigned_to',['' => 'Please Select'] + $assigned_to, old('assigned_to'), ['class' => 'form-control']) }}

                            @if ($errors->has('assigned_to')) 
                            <strong>{{ $errors->first('assigned_to') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="documents" class="col-md-6 control-label">Documents</label>

                        <div class="col-md-6">
                            <input type="file" name="documents[]" class="form-control" multiple="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="documents" class="col-md-6 control-label">Uploaded Documents</label>

                        <div class="col-md-12">
                            <?php foreach ($uploaded_documents as $k => $v) { ?>
                            <div><a target="_blank" href="{{$v}}">Document {{$k+1}}</a></div>
                            <?php }
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                SAVE
                            </button>
                            <a type="button" class="btn btn-default" href="{{ route('admin.post-projects.index') }}">
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
