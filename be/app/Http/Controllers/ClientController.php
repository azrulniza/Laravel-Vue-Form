<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function show($id)
    {
        return Client::findOrFail($id);
    }

    public function store(Request $request)
    {
        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json($client, 200);
    }

    public function destroy($id, Request $request)
    {
        // Find the client record by ID
        $client = Client::findOrFail($id);

        // Update the 'active' status based on the passed 'active' parameter
        $client->active = $request->input('active') ? 1 : 0;
        $client->save();

        // Return a success response
        if ($client->active == 0) {
            return response()->json(['message' => 'Client deactivated successfully', 'data' => $client]);
        } else {
            return response()->json(['message' => 'Client activated successfully', 'data' => $client]);
        }
    }
}
