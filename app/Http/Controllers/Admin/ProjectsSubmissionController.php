<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\User;
use App\Models\Roles;
use App\Models\ProjectsSubmissionsHistory;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Carbon\Carbon;
use DataTables;

class ProjectsSubmissionController extends Controller {

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
            if (Auth::user()->role_id == Roles::ROLE_CLIENT)
                $data = Projects::where(['status' => Projects::STATUS_ACTIVE, 'created_by' => Auth::user()->id])->get();
            else
                $data = Projects::where(['status' => Projects::STATUS_ACTIVE, 'assigned_to' => Auth::user()->id])->get();
            return Datatables::of($data)
                            ->editColumn('due_date', function ($request) {
                                return date('d/m/Y', strtotime($request->due_date));
                            })
                            ->editColumn('project_submission_status', function ($request) {
                                $ProjectsSubmissionsHistory = ProjectsSubmissionsHistory::Where(['projects_id' => $request->id])->get();
                                return !empty($request->project_submission_status) ? (Projects::$ProjectSubmissionStatus[$request->project_submission_status]) : (((isset($ProjectsSubmissionsHistory[0]->id) && Auth::user()->role_id == Roles::ROLE_CLIENT) ? 'Send Back to Designer' : (((isset($ProjectsSubmissionsHistory[0]->id) && Auth::user()->role_id == Roles::ROLE_DESIGNER)) ? 'Re-Submit Project' : 'Not Submitted Yet')));
                            })
                            ->editColumn('assigned_to', function ($request) {
                                return User::getUserName($request->assigned_to);
                            })
                            ->addColumn('action', function($row) {
                                if ($row->project_submission_status == Projects::SUBMITTED) {
                                    $btn = '<a href="' . route('admin.projects-submission.show', $row->id) . '"  title="View" class="btn btn-primary btn-sm ">View</a>';
                                } else if ($row->project_submission_status == Projects::SEND_BACK_TO_CLIENT) {
                                    if (Auth::user()->role_id == Roles::ROLE_DESIGNER)
                                        $btn = '<a href="' . route('admin.projects-submission.show', $row->id) . '"  title="View" class="btn btn-primary btn-sm ">View</a>';
                                    else
                                        $btn = '<a href="' . route('admin.projects-submission.edit', $row->id) . '"  title="Submit Project" class="btn btn-primary btn-sm ">Submit Project</a>';
                                } else {
                                    if (Auth::user()->role_id == Roles::ROLE_CLIENT)
                                        $btn = '<a href="' . route('admin.projects-submission.show', $row->id) . '"  title="View" class="btn btn-primary btn-sm ">View</a>';
                                    else
                                        $btn = '<a href="' . route('admin.projects-submission.edit', $row->id) . '"  title="Submit Project" class="btn btn-primary btn-sm ">Submit Project</a>';
                                }
                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.projects-submission.index');
    }

    /**
     * Show the form object.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Projects $projects_submission) {
        // Retrieve all Project History
        $ProjectsSubmissionsHistory = ProjectsSubmissionsHistory::Where(['projects_id' => $projects_submission->id])->get();
        $uploaded_documents = [];
        foreach (explode(',', $projects_submission->documents) as $dk => $dv)
            $uploaded_documents[] = URL('/') . '/uploads/documents/' . $projects_submission->id . '/' . $dv; // upload path
        $projects_submission->due_date = date('d/m/Y', strtotime($projects_submission->due_date));
        return view('admin.projects-submission.view', ['ProjectsSubmissionsHistory' => $ProjectsSubmissionsHistory, 'project' => $projects_submission, 'uploaded_documents' => $uploaded_documents]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Projects $projects_submission) {
        // Retrieve all Project History
        $ProjectsSubmissionsHistory = ProjectsSubmissionsHistory::Where(['projects_id' => $projects_submission->id])->get();
        $uploaded_documents = [];
        if (!empty($projects_submission->documents)) {
            foreach (explode(',', $projects_submission->documents) as $dk => $dv)
                $uploaded_documents[] = URL('/') . '/uploads/documents/' . $projects_submission->id . '/' . $dv; // upload path
        }
        $projects_submission->due_date = date('d/m/Y', strtotime($projects_submission->due_date));
        $assigned_to = User::where(['status' => User::STATUS_ACTIVE, 'role_id' => Roles::ROLE_DESIGNER])->pluck('name', 'id')->toArray();
        return view('admin.projects-submission.form', ['ProjectsSubmissionsHistory' => $ProjectsSubmissionsHistory, 'project' => $projects_submission, 'assigned_to' => $assigned_to, 'uploaded_documents' => $uploaded_documents]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->isMethod('post')) {
            request()->validate([
                'messages' => 'required'
            ]);
            $data = $request->all();
            $ProjectsSubmissionsHistory = ProjectsSubmissionsHistory::create([
                        'messages' => $data['messages'],
                        'projects_id' => $data['project_id'],
                        'created_by' => (isset(Auth::user()->id) && !empty(Auth::user()->id)) ? Auth::user()->id : 0,
                        'created_dt' => Carbon::now()->toDateTimeString(),
            ]);
            $documents = [];
            if ($request->hasfile('documents')) {
                foreach ($request->file('documents') as $key => $file) {
                    $name = $file->getClientOriginalName();
                    $name = $key . '_' . Carbon::now() . '_' . $name;
                    $file->move(public_path() . '/uploads/documents/' . $data['project_id'] . '/', $name);
                    $documents[] = $name;
                }
            }
            if ($documents) {
                $ProjectsSubmissionsHistory->documents = implode(',', $documents);
                $ProjectsSubmissionsHistory->update();
            }
            //For updating the Project status in Project table.
            if ($data['project_id']) {
                Projects::Where(['id' => $data['project_id']])->update(['project_submission_status' => $data['project_submission_status']]);
            }
            return Redirect::to("admin/projects-submission")->with('status', "Project Submission Stored Successfully.");
        }
        return view('admin.projects-submission.form');
    }

}
