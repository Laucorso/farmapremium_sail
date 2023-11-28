<?php

namespace App\Repositories;

interface PharmacyRepositoryInterface
{
    public function find($pharmacyId);
    public function givenPointsBetweenDates($pharmacy_id,$start,$end);
    public function givenPointsByClient($pharmacy_id,$card);

}