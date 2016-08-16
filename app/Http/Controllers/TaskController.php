<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use App\Task;
use Session;

class TaskController extends Controller
{
	protected $tasks;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    public function index(Request $request)
    {
        return view('tasks.index', [
            'tasksList' => $this->tasks->forUser($request->user()),
        ]);
    }

    public function show(Request $request)
    {
        
    }

	public function store(Request $request)
	{
	    $this->validate($request, [
	        'name' => 'required|max:255',
	    ]);

	    $request->user()->tasks()->create([
	        'name' => $request->name,
	    ]);

	    return redirect('/tasks');
	}

	public function destroy(Request $request, Task $task)
	{
        dd($task);
	    $this->authorize('destroy', $task);

	    $task->delete();
        // redirect
        Session::flash('message', 'Successfully deleted!');
    	return Redirect::to('tasks');
	}
}