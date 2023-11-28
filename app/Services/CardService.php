<?php
namespace App\Services;

use App\Repositories\CardRepositoryInterface;

class CardService {

    protected $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function acumulatePoints($client_id, $pharmacy_id, $points){

        $result = $this->cardRepository->obtainPoints($pharmacy_id, $points, $client_id);
        return $result;
    }

    public function canjearPoints($client_id, $pharmacy_id, $points){

        if($this->cardRepository->getTotalPoints($client_id) >= $points){
            $result = $this->cardRepository->canjearPoints($pharmacy_id, $points, $client_id);
        }else{
            $result = false;
        }

        return $result;
    }

    public function getTotalPoints($client_id)
    {
        $card = $this->cardRepository->findByClientId($client_id);
        return $card->total_points;
    }
 
}
