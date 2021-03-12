<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Services\BlockchainService;
use App\Services\ConvertService;
use Illuminate\Http\Request;



class ConvertController extends Controller
{
    private $convertService;
    private $blockchainService;

    public function __construct(ConvertService $convertService, BlockchainService $blockchainService)
    {
        $this->convertService = $convertService;

        // тут конечно лучше по крону запускать с таймером
        $this->blockchainService = $blockchainService;
        $this->blockchainService->init();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/convert",
     *     summary="Конвертировать",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="currency_from",
     *         in="query",
     *         description="исходная валюта",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="currency_to",
     *         in="query",
     *         description="валюта в которую конвертируем",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="value",
     *         in="query",
     *         description="количество единиц исходной валюты",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wrong credentials response",
     *         @OA\JsonContent(
     *            @OA\Property(property="currency_from", type="string", example="исходная валюта"),
     *            @OA\Property(property="currency_to", type="string", example="валюта в которую конвертируем"),
     *            @OA\Property(property="value", type="number", example="количество единиц исходной валюты"),
     *            @OA\Property(property="converted_value", type="number", example="количество единиц валюты после обмена"),
     *            @OA\Property(property="rate", type="number", example="курс"),
     *            @OA\Property(property="created_at", type="string", example="дата создания")
     *        )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Auth error", )
     *     )
     * )
     */
    /**
     * @param Request $request
     * @return ErrorResource|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function convert(Request $request)
    {
        try {
            $request->validate([
                'currency_from' => 'required',
                'currency_to' => 'required',
                'value' => 'required|numeric',
            ]);
        }
        catch (\Exception $e){
            return new ErrorResource([
                'status' => 'error',
                'code' => 403,
                'message' => 'No validate request: '
            ]);
        }

        return $this->convertService->convert((string)$request->currency_from, (string)$request->currency_to, (float)$request->value);
    }
}
