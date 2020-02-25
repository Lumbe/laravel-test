<?php

namespace App\Http\Controllers;

use App\Models\RebMlsListing;
use Illuminate\Http\Request;

class MlsListingController extends Controller {

    public function showAllListings()
    {
        return response()->json(RebMlsListing::limit(20)->get());
    }

    public function showOneListing($id)
    {
        return response()->json(RebMlsListing::find($id));
    }

    public function create(Request $request)
    {
        $mls_property = RebMlsListing::create($request->all());

        return response()->json($mls_property, 201);
    }

    public function update($id, Request $request)
    {
        $mls_property = RebMlsListing::findOrFail($id);
        $mls_property->update($request->all());

        return response()->json($mls_property, 200);
    }

    public function delete($id)
    {
        RebMlsListing::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
