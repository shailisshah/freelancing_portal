<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Redirect;
use DataTables;
use Auth;
use Carbon\Carbon;

class ProjectsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        if ($request->ajax()) {
            $data = Projects::where(['status' => Projects::STATUS_ACTIVE, 'assigned_to' => Auth::user()->id])->get();
            return Datatables::of($data)
                            ->editColumn('due_date', function ($request) {
                                return date('d/m/Y', strtotime($request->due_date));
                            })
                            ->addColumn('action', function($row) {

                                $btn = '<a href="' . route('admin.projects.show', $row->id) . '"  title="View" class="btn btn-primary btn-sm ">View</a>';

                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.projects.index');
    }

    /**
     * Show the form object.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Projects $project) {
        $uploaded_documents = [];
        foreach (explode(',', $project->documents) as $dk => $dv)
            $uploaded_documents[] = URL('/') . '/uploads/documents/' . $project->id . '/' . $dv; // upload path
        $project->due_date = date('d/m/Y', strtotime($project->due_date));

        return view('admin.projects.view', ['project' => $project, 'uploaded_documents' => $uploaded_documents]);
    }

}
