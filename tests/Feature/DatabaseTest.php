<?php
namespace Tests\Feature;

use App\Models\Client;
use App\Models\Card;
use App\Models\Pharmacy;
use App\Models\Transaction;
use App\Models\Items;
use App\Models\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_create_transaction(){
        $card = Card::factory()->create();
        $pharmacy = Pharmacy::factory()->create();

        $transaction = Transaction::factory(2)->create([
            'card_id'=>$card->id,
            'pharmacy_id'=>$pharmacy->id,
            'points'=>5,
        ]);
        $this->assertDatabaseCount('transactions', 2);
        $this->assertDatabaseHas('transactions', ['card_id' => $card->id]);
    }


}