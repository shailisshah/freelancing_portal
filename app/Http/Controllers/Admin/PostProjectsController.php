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

class PostProjectsController extends Controller {

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
            $data = Projects::where(['status' => Projects::STATUS_ACTIVE])->get();
            return Datatables::of($data)
                            ->editColumn('due_date', function ($request) {
                                return date('d/m/Y', strtotime($request->due_date));
                            })
                            ->editColumn('assigned_to', function ($request) {
                                return User::getUserName($request->assigned_to);
                            })
                            ->addColumn('action', function($row) {
                                $btn = '<a href="' . route('admin.post-projects.edit', $row->id) . '"  title="Edit" class="btn btn-primary btn-sm ">Edit</a>';

                                $btn = $btn . ' <a onclick="deleteRecord(this)" data-href="' . route('admin.post-projects.delete', $row->id) . '"  title="Delete" class="btn btn-danger btn-sm" >Delete</a>';

                                return $btn;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        return view('admin.post-projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $assigned_to = User::where(['status' => User::STATUS_ACTIVE, 'role_id' => Roles::ROLE_CLIENT])->pluck('name', 'id')->toArray();
        return view('admin.post-projects.form', ['assigned_to' => $assigned_to]);
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
                'title' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                'assigned_to' => 'required',
            ]);
            $data = $request->all();
            $projects = Projects::create([
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'assigned_to' => $data['assigned_to'],
                        'due_date' => Carbon::createFromFormat('d/m/Y', $data['due_date'])->format('Y-m-d'),
            ]);
            return Redirect::to("admin/post-projects")->with('status', "Project Created Successfully.");
        }
        return view('admin.post-projects.form');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Projects $post_project) {
        $post_project->due_date = date('d/m/Y', strtotime($post_project->due_date));
        $assigned_to = User::where(['status' => User::STATUS_ACTIVE, 'role_id' => Roles::ROLE_CLIENT])->pluck('name', 'id')->toArray();
        return view('admin.post-projects.form', ['project' => $post_project, 'assigned_to' => $assigned_to]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Projects $post_project) {
        if ($request->isMethod('patch')) {
            request()->validate([
                'title' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                'assigned_to' => 'required',
            ]);
            $data = $request->all();
            if ($post_project) {
                $data['due_date'] = Carbon::createFromFormat('d/m/Y', $data['due_date'])->format('Y-m-d');
                $post_project->update($data);
                return Redirect::to("admin/post-projects")->with('status', "Project Updated Successfully. ");
            }
        }
        return view('admin.post-projects.form');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Projects $id) {
        $id->update(['status' => Projects::STATUS_DELETED]);
        return Redirect::to("admin/post-projects")->with('status', "Project Deleted Successfully. ");
    }

}
