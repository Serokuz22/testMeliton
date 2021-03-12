<?php
namespace App\Services;

use App\Convert;
use App\Http\Resources\ConvertResource;
use App\Http\Resources\ErrorResource;
use App\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class ConvertService
{
    /**
     * @param string $from
     * @param string $to
     * @param float $count
     * @return JsonResource
     */
    public function convert(string $from, string $to, float $count) : JsonResource
    {
        if($from == 'BTC'){
            $currency = Rate::where('currency', $to)->first();
            if(!$currency)
                return $this->responseError('to ' . $to);
            return $this->save(
                $from,
                $to,
                $count,
                round($currency->value * $count, 2),
                $currency->value
            );
        }
        else if($to == 'BTC'){
            $currency = Rate::where('currency', $from)->first();
            if(!$currency)
                return $this->responseError('from ' . $to);
            return $this->save(
                $from,
                $to,
                $count,
                round( ((1/$currency->value) * $count), 10),
                $currency->value
            );
        }
        else{
            return $this->responseError('from|to BTC');
        }
    }

    /**
     * @param string $info
     * @return ErrorResource
     */
    private function responseError(string $info) : ErrorResource
    {
        $data =[
            'status' => 'error',
            'code' => 403,
            'message' => 'No valid currency: ' . $info
        ];
        return new ErrorResource($data);
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $count
     * @param float $result
     * @param float $rate
     * @return ConvertResource
     */
    private function save(string $from, string $to, float $count, float $result, float $rate) : ConvertResource
    {
        $data =  [
            'currency_from' => $from,
            'currency_to' => $to,
            'value' => $count,
            'converted_value' => $result,
            'rate' => $rate,
        ];

        $item = new Convert($data);
        $item->save();
        $response = new ConvertResource($item);
        return $response;
    }
}
