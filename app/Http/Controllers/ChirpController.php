<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 'Hello World!';

        // get Chirps from all users
        // $chirps = Chirp::all(); 

        // I think solution with with('user') is for n + 1 problems
        // Eager Loading Multiple Relationships
        // Sometimes you may need to eager load several different relationships. 
        // To do so, just pass an array of relationships to the with method:
        // note: for single different relationship you dont need array. Just do it like down here:
        $chirps = Chirp::with('user')->latest()->get();

        return view('chirps.index', compact('chirps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = request()->validate([
            'message' => 'required|string|max:255'
        ]);

        // dd(auth()->user()->name);   get name of the user
        auth()->user()->chirps()->create($formFields);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function edit(Chirp $chirp)
    {
        // Even though we're only displaying the edit button to the author of the Chirp, 
        // we still need to make sure the user accessing these routes is authorized
        $this->authorize('update', $chirp);
        // if I pass User $user instead Chirp $chirp
        // $this->authorize('update', $user->chirps);

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $formFields = request()->validate([
            'message' => 'required|string|max:255'
        ]);

        $chirp->update($formFields);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        
        $chirp->delete();

        return back();
    }
}
