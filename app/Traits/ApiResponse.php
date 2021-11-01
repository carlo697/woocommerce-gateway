<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    protected function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($messeger, $code)
    {
        return response()->json(['error' => $messeger, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection->sortBy("id")->values()->all()], $code);
    }
    protected function showAllResources(ResourceCollection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection->sortBy("id")->values()->all()], $code);
    }
    protected function showOne(Model $instace, $code = 200)
    {
        return $this->successResponse(['data' => $instace], $code);
    }
    protected function showOneResource(JsonResource $instace, $code = 200)
    {
        return $this->successResponse(['data' => $instace], $code);
    }
    protected function successMessages($data, $code)
    {
        return response()->json($data, $code);
    }
}