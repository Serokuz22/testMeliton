<?php
namespace App\Services;

use App\Rate;
use Illuminate\Support\Facades\Http;

class BlockchainService
{
    private $url = 'https://blockchain.info/ticker';
    private $commission = 0.02;

    private $data = null;

    /**
     * @return $this
     * @throws \Exception
     */
    public function init()
    {
        $response = Http::get($this->url);

        if($response->ok())
            $this->data = $response->json();
        else
            throw new \Exception('No Data');

        $this->initCurrency();

        return $this;
    }

    /**
     *
     */
    private function initCurrency()
    {
        foreach ($this->data as $key => $val )
        {
            $rate = Rate::where('currency', $key)->first();
            if($rate){
                $rate->update([
                    'value' => $this->commissionCalc($val['15m']),
                    'rate' => $val['15m']
                ]);
            }
            else{
                $rate = new Rate([
                        'currency' => $key,
                        'value' => $this->commissionCalc($val['15m']),
                        'rate' => $val['15m']
                    ]);
                $rate->save();
            }
        }
    }

    /**
     * Считаем комиссию
     * @param float $val
     * @return float
     */
    private function commissionCalc(float $val)
    {
        return round($val+($val*$this->commission), 2);
    }
}
