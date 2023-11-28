<?php

namespace App\Services;

use App\Repositories\PharmacyRepositoryInterface;

class PharmacyService
{
    protected $pharmacyRepository;

    public function __construct(PharmacyRepositoryInterface $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function givenPointsBetweenDates($pharmacyId, $start, $end)
    {

        $result = $this->pharmacyRepository->givenPointsBetweenDates($pharmacyId,$start,$end);
        return $result;
    }

    public function givenPointsByClient($pharmacyId,$card){

        $result = $this->pharmacyRepository->givenPointsByClient($pharmacyId,$card);
        return $result;
    }
}
