<?php
/**
 * @OA\Info(title="Тестовое задание meleton", version="1.0")
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyCollection;
use App\Rate;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class RatesController extends Controller
{
    private $blockchainService;

    public function __construct(BlockchainService $blockchainService)
    {
        // тут конечно лучше по крону запускать с таймером
        $this->blockchainService = $blockchainService;
        $this->blockchainService->init();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/rates",
     *     summary="Получить список всех валют",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *       response="200",
     *       description="Возврашает array со списком страниц",
     *       @OA\JsonContent(
     *         @OA\Schema( type="array",
     *             @OA\Items(
     *                type="object",
     *                 @OA\Property(
     *                     property="cyrrency",
     *                     type="string"),
     *                 @OA\Property(
     *                     property="rate",
     *                     type="float"),
     *             ),
     *         )
     *       )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Auth error", )
     *     )
     * )
     */
    /**
     * @OA\Get(
     *     path="/api/v1/rates?filter[currency]={currency}",
     *     summary="Получить одну валюту",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="currency",
     *         in="path",
     *         description="Currency",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Возврашает array со списком страниц",
     *       @OA\JsonContent(
     *         @OA\Schema( type="array",
     *             @OA\Items(
     *                type="object",
     *                 @OA\Property(
     *                     property="cyrrency",
     *                     type="string"),
     *                 @OA\Property(
     *                     property="rate",
     *                     type="float"),
     *             ),
     *         )
     *       )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Auth error", )
     *     )
     * )
     */
    /**
     * @param Request $request
     * @return CurrencyCollection
     */
    public function rates(Request $request)
    {
        $u = QueryBuilder::for(Rate::orderBy('value'), $request)
            ->allowedFilters('currency')
            ->get();
        return new CurrencyCollection($u);
    }
}
