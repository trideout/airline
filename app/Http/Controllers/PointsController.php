<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointRequest;
use App\Models\Point;
use Illuminate\Http\Request;
use App\Http\Resources\PointResource;

class PointsController extends Controller
{
    public function index()
    {
        return view('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $points = Point::all();
        return PointResource::collection($points);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $point = new Point();
        $points = Point::all()->filter( fn($p) => $p->id != $point->id)->values();
        return view('edit', compact('point','points'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PointRequest $request)
    {
        $point = Point::create($request->validated());
        return new PointResource($point);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $point = Point::findOrFail($id);
        return new PointResource($point);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $point = Point::findOrFail($id);
        $points = Point::all()->filter( fn($p) => $p->id != $point->id)->values();
        return view('edit', compact('point','points'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PointRequest $request, $id)
    {
        $point = Point::findOrFail($id);
        $point->update($request->validated());
        return new PointResource($point);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $point = Point::findOrFail($id);
        $point->delete();
        return redirect()->back();
    }
}
