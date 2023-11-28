<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Pharmacy;
use PHPUnit\Framework\TestCase;

use App\Repositories\CardRepository;


class CardTRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected $cardRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->cardRepository = new CardRepository(new Card());
    }

    public function test_findByClientId()
    {
        $card = Card::factory()->create();

        $foundCard = $this->cardRepository->findByClientId($card->client_id);

        $this->assertInstanceOf(Card::class, $foundCard);

        $this->assertEquals($card->client_id, $foundCard->client_id);
    }

    public function test_canjearPoints()
    {
        $client = Client::factory()->create();

        $card = Card::factory()->create([
            'client_id' => $client->id,
            'points'=>500,
        ]);        

        $pharmacy = Pharmacy::factory()->create();

        $transaction = Transaction::factory()->create([
            'card_id' => $card->id,
            'type' => 'given',
            'pharmacy_id'=>$pharmacy->id,
            'points' => 120,
        ]);

        $expectedBalance = $card->points - $transaction->points;
        $result = $this->cardRepository->canjearPoints($transaction->pharmacy_id, $transaction->points, $transaction->client_id);

        $this->assertTrue($result);

        $this->assertGreaterThanOrEqual(0, $this->cardRepository->getTotalPoints($card->client_id));

        $this->assertEquals($expectedBalance, $this->cardRepository->getTotalPoints($card->client_id));

        //checkeamos que falle si el saldo de la tarjeta es inferior a los puntos a canjear
        // $points = 800; 
        // $resultExcessive = $this->cardRepository->canjearPoints($transaction->pharmacy_id, $points, $transaction->client_id);

        // $this->assertFalse($resultExcessive);

        //$this->assertEquals($expectedBalance, $this->cardRepository->getTotalPoints($card->client_id));

    }

    public function test_obtainPoints_repository()
    {
        $points = 10;
        $pharmacy = Pharmacy::factory()->create();
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'points'=>25,
        ]);             

        $result = $this->cardRepository->obtainPoints($pharmacy->id, $points, $card->client_id);


        $this->assertTrue($result);

        $expectedBalance = $card->points + $points;

        $this->assertEquals($expectedBalance, $this->cardRepository->getTotalPoints($card->client_id));
    }

    public function test_updateCardBalance()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'points'=>25,
        ]);   
        $initialBalance = $this->cardRepository->getTotalPoints($card->client_id);

        $points = 5;

        //obtener puntos
        $this->cardRepository->updateCardBalance($points, $card, '+');
        $card->refresh();

        $this->assertEquals($initialBalance + $points, $this->cardRepository->getTotalPoints($card->client_id));

        //canjeo
        $this->cardRepository->updateCardBalance($points, $card, '-');
        $card->refresh();

        $this->assertEquals($initialBalance, $this->cardRepository->getTotalPoints($card->client_id));
    }

    public function test_getTotalPoints()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create([
            'client_id' => $client->id,
            'points'=>33,
        ]);   

        $totalPoints = $this->cardRepository->getTotalPoints($card->client_id);

        $this->assertIsInt($totalPoints);
        
        $this->assertGreaterThanOrEqual(0, $totalPoints);

    }

}
