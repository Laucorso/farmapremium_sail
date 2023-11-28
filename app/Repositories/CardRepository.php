<?php
namespace App\Repositories;

use App\Models\Card;
use App\Models\Purchase;

class CardRepository implements CardRepositoryInterface
{
    protected $model;

    public function __construct(Card $card)
    {
        $this->model = $card;
    }
    public function findByClientId($client_id)
    {
        return $this->model->where('client_id', $client_id)->first();
    }
    
    public function canjearPoints($pharmacy_id, $points, $client_id)
    {
        $card = $this->findByClientId($client_id);

        $transactionsPossibleToCanjear = $card->transactions()
                                            ->where('type', 'given')
                                            ->orderBy('created_at')
                                            ->get();
                                            
        if(count($transactionsPossibleToCanjear)>0){
            foreach ($transactionsPossibleToCanjear as $transaction) {
                $pointsFromTransaction = $transaction->points;
    
                // verificamos si el saldo de cada transaccion no canjeada aún es igual o inferior para proceder
                if ($points >= $pointsFromTransaction) {
                    $transaction->update([
                        'type' => 'canjeado',
                        'pharmacy_id'=>$pharmacy_id,
                    ]);
                    $points -= $pointsFromTransaction;
                } else {
                    // canjeamos parte de los puntos del movimiento actual
                    $transaction->update([
                        'points' => $pointsFromTransaction - $points,
                        'pharmacy_id'=>$pharmacy_id,
                    ]);
                    break;
                }
            }
    
            $this->updateCardBalance($points,$card, '-');

            return true; 

        }

        return false;
    }

    public function updateCardBalance($points, $card, $operation)
    {
        $newBalance = ($operation == '+') ? $card->total_points + $points : $card->total_points - $points;
        $card->update(['total_points' => $newBalance]);
    }

    public function obtainPoints($pharmacy_id, $points, $client_id)
    {
        $card = $this->findByClientId($client_id);
        $purchase = Purchase::create([
            'pharmacy_id'=>$pharmacy_id,
            'client_id'=>$client_id,
        ]);
        $card->transactions()->create([
            'pharmacy_id' => $pharmacy_id,
            //'card_id'=>$card_id,
            'purchase_id'=>$purchase->id,
            'type'=>'given',
            'points' => $points,
        ]);

        $this->updateCardBalance($points,$card, '+');
        return true;
    }

    public function getTotalPoints($client_id)
    {
        $card = $this->findByClientId($client_id);
        return $card->total_points;
    }

}

