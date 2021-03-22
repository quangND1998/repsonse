<?php

namespace Darkness\Response;

use Darkness\Response\Transformers\OptimusPrime;

trait ResponseHandler
{
    public $transform;

    protected function setTransformer($transform)
    {
        $this->transform = $transform;
    }

    protected function successResponse($data)
    {
        if ($this->transform) {
            $response = array_merge([
                'code' => 200,
                'status' => 'success',
            ], $this->transform($data));
            return response()->json($response, $response['code']);
        } else {
            if (is_null($data)) {
                $data = [];
            }
            $response = array_merge([
                'code' => 200,
                'status' => 'success',
            ], ['data' => $data]);
            return response()->json($response, 200);
        }
    }

    protected function notFoundResponse()
    {
        $response = [
            'code' => 404,
            'status' => 'error',
            'data' => 'Resource Not Found',
            'message' => 'Not Found'
        ];
        return response()->json($response, $response['code']);
    }

    public function deleteResponse()
    {
        $response = [
            'code' => 200,
            'status' => 'success',
            'data' => [],
            'message' => 'Resource Deleted'
        ];
        return response()->json($response, $response['code']);
    }

    public function errorResponse($data)
    {
        $response = [
            'code' => 422,
            'status' => 'error',
            'data' => $data,
            'message' => 'Unprocessable Entity'
        ];
        return response()->json($response, $response['code']);
    }

    public function paymentRequiredResponse($message)
    {
        $response = [
            'code' => 402,
            'status' => 'error',
            'data' => 'payment required',
            'message' => $message
        ];
        return response()->json($response, $response['code']);
    }

    private function transform($data)
    {
        $optimus = app()->make(OptimusPrime::class);
        return $optimus->transform($data, $this->transform);
    }
}
