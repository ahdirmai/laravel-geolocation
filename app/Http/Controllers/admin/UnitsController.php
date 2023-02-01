<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();
        $data = [
            'units' => $units
        ];
        return view('pages.admin.units.index', $data);
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
        $slug = Str::slug($request->name, '-');

        $this->validate($request, [
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'logo' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move('uploads/imgCover/', $imageName);
        }

        $save = Unit::create([
            'name' => $request->name,
            'slug' => $slug,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'logo' => $imageName,
        ]);
        if ($save) {
            return redirect()->route('unit.index')->with('success', 'Unit Berhasil Ditambahkan');
        } else {
            return redirect()->route('unit.index')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $data = [
            'unit' => $unit
        ];
        return view('pages.admin.units.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->logo);
        $unit = Unit::findOrFail($id);
        $slug = Str::slug($request->name, '-');
        $this->validate($request, [
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            if (File::exists("uploads/imgCover/" . $unit->logo)) {
                File::delete("uploads/imgCover/" . $unit->logo);
            }
            $file = $request->file("logo");
            $unit->update([
                'logo' => time() . '_' . $file->getClientOriginalName()
            ]);
            $file->move('uploads/imgCover/', $unit->logo);
            $request['logo'] = $unit->logo;
        }

        $update = $unit->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'slug' => $slug,
        ]);

        if ($update) {
            return redirect()->route('unit.index')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('unit.index')->with('error', 'Data gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        if (File::exists("uploads/imgCover/" . $unit->logo)) {
            File::delete("uploads/imgCover/" . $unit->logo);
        }
        $unit->delete();
        return redirect()->route('unit.index');
    }
}
