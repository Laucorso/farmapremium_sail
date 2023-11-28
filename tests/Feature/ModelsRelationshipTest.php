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

class ModelsRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_has_one_card()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Card::class, $client->card);
        $this->assertEquals($client->id, $card->client_id);
    }

    public function test_pharmacy_has_many_transactions()
    {
        $pharmacy = Pharmacy::factory()->create();
        $transactions = Transaction::factory(3)->create(['pharmacy_id' => $pharmacy->id]);

        $this->assertInstanceOf(Transaction::class, $pharmacy->transactions->first());
        $this->assertCount(3, $pharmacy->transactions);
    }

    public function test_card_has_many_transactions()
    {
        $client = Client::factory()->create();
        $card = Card::factory()->create(['client_id' => $client->id]);
        $transactions = Transaction::factory(10)->create(['card_id' => $card->id]);

        $this->assertInstanceOf(Transaction::class, $card->transactions->first());
        $this->assertCount(10, $card->transactions);
    }

    public function test_item_belongs_to_purchase()
    {
        $purchase = Purchase::factory()->create();
        $item = Items::factory()->create(['purchase_id' => $purchase->id]);

        $this->assertInstanceOf(Purchase::class, $item->purchase);
        $this->assertEquals($purchase->id, $item->purchase->id);
    }

    public function test_transaction_belongs_to_purchase()
    {
        $purchase = Purchase::factory()->create();
        $transaction = Transaction::factory()->create(['purchase_id' => $purchase->id]);

        $this->assertInstanceOf(Purchase::class, $transaction->purchase);
        $this->assertEquals($purchase->id, $transaction->purchase->id);
    }


}