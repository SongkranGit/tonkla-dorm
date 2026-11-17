<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $current_branch = null;
        $branches = null;
        //$selected_branch_name = session('selected_branch_name');

        if ($user->hasRole('admin') || $user->hasRole('owner')) {
            $branches = Branch::get();
        }

        return view('home', compact('branches', $branches  ));
    }

    public function selectBranch(Request $request, $branch_id)
    {
        $branch = Branch::where('id', $branch_id)->first();
        $request->session()->put('current_branch_id', $branch_id);
        $request->session()->put('current_branch_name', $branch->name);
        $request->session()->put('should_show_branch_list', false);
        return redirect()->route('home');
    }

    public function showAllBranch(Request $request){
        $branches = Branch::get();
        $request->session()->put('should_show_branch_list', true);
        return view('home', compact('branches', $branches  ));
    }

}
