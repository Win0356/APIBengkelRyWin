<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Bengkel;

class BengkelController extends Controller
{
    public function index()
    {
        $services = Bengkel::all();

        if (count($services) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $services
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $service = Bengkel::find($id);

        if(!is_null($service)) {
            return response([
                'message' => 'Retrieve Service Success',
                'data' => $service
            ], 200);
        }

        return response([
            'message' => 'Service Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'jenis_service' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $service = Bengkel::create($storeData);
        return response([
            'message' => 'Add Service Success',
            'service' => $service
        ], 200);
    }

    public function destroy($id)
    {
        $service = Bengkel::find($id);

        if(is_null($service)) {
            return response([
                'message' => 'Service Not Found',
                'data' => null
            ], 404);
        }

        if($service->delete()) {
            return response([
                'message' => 'Delete Service Success',
                'data' => $service
            ], 200);
        }

        return response([
            'message' => 'Delete Service Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $service = Bengkel::find($id);
        if(is_null($service)) {
            return response([
                'message' => 'Service Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required', 
            'jenis_service' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
           
        $service->nama_pelanggan = $updateData['nama_pelanggan']; 
        $service->alamat_pelanggan = $updateData['alamat_pelanggan']; 
        $service->jenis_service = $updateData['jenis_service'];
    
        if ($service->save()) {
            return response([
                'message' => 'Update Service Success',
                'data' => $service
            ], 200);
        }
        return response([
            'message' => 'Update Service Failed',
            'data' => null,
        ], 400);
    }
}

