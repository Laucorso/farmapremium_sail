<?php

namespace App\Repositories;

interface CardRepositoryInterface
{
    
    public function findByClientId($client_id);
    public function canjearPoints($pharmacy_id, $points, $client_id);
    public function updateCardBalance($points, $card, $operation);
    public function obtainPoints($pharmacy_id, $points, $client_id);
    public function getTotalPoints($client_id);


}