<?php

namespace App\Repositories;

use App\Models\Pharmacy;

class PharmacyRepository implements PharmacyRepositoryInterface
{
    protected $model;

    public function __construct(Pharmacy $pharmacy)
    {
        $this->model = $pharmacy;
    }

    public function find($pharmacyId)
    {
        return $this->model->find($pharmacyId);
    }

    public function givenPointsBetweenDates($pharmacy_id,$start,$end){
        $pharmacy = $this->find($pharmacy_id);
        if($pharmacy){
            return $pharmacy->transactions()
                            ->where('type', 'given')
                            ->whereBetween('created_at', [$start, $end])
                            ->sum('points');  
        }else{
            return 'No existe la farmacia indicada.';
        }
    }

    public function givenPointsByClient($pharmacy_id,$card){
        $pharmacy = $this->find($pharmacy_id);
        if($pharmacy){

            return $pharmacy->transactions()
                            ->where('type', 'given') //sin tener en cuenta los canjeados
                            ->where('card_id', $card->id)
                            ->sum('points'); 

        }else{
            return 'No existe la farmacia indicada.';
        }

    }

}