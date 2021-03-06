<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource  extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
        parent::withResponse($request, $response); // TODO: Change the autogenerated stub
    }
}
