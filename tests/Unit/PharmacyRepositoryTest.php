<?php

namespace Tests\Unit;

use app\Models\Client;
use app\Models\Pharmacy;
use PHPUnit\Framework\TestCase;

use App\Repositories\PharmacyRepository;

class PharmacyRepositoryTest extends TestCase
{
    protected $pharmacyRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->pharmacyRepository = new PharmacyRepository(new Pharmacy());
    }
    public function test_find_method()
    {
        $pharmacy = Pharmacy::factory()->create();

        $foundPharmacy = $this->pharmacyRepository->find($pharmacy->id);

        $this->assertInstanceOf(Pharmacy::class, $foundPharmacy);

        $this->assertEquals($pharmacy->id, $foundPharmacy->id);
    }

    public function test_givenPointsBetweenDates()
    {
        $pharmacy = Pharmacy::factory()->create();
        $start = now()->subDays(10);
        $end = now();

        $pharmacy->transactions()->createMany([
            ['type' => 'given', 'points' => 10, 'created_at' => now()->subDays(9)],
            ['type' => 'given', 'points' => 20, 'created_at' => now()->subDays(2)],
            ['type' => 'canjeados', 'points' => 5, 'created_at' => now()->subDays(1)],
            ['type' => 'given', 'points' => 15, 'created_at' => now()->subDays(15)],
            ['type' => 'given', 'points' => 25, 'created_at' => now()->subDays(11)],
        ]);

        $result = $this->pharmacyRepository->givenPointsBetweenDates($pharmacy->id, $start, $end);

        $this->assertEquals($pharmacy->transactions()->where('type','given')->whereBetween('created_at',[$start,$end])->sum('points'), $result);
    }

    public function test_givenPointsByClient()
    {
        $client = Client::factory()->create();
        $pharmacy = Pharmacy::factory()->create();
        $card = $client->card()->create(); 

        $pharmacy->transactions()->createMany([
            ['type' => 'given', 'points' => 10, 'card_id' => $card->id],
            ['type' => 'given', 'points' => 20, 'card_id' => $card->id],
            ['type' => 'given', 'points' => 15, 'card_id' => $card->id],
            ['type' => 'canjeados', 'points' => 10, 'card_id' => $card->id],
            ['type' => 'canjeados', 'points' => 5, 'card_id' => $card->id],
            ['type' => 'given', 'points' => 25, 'card_id' => $card->id],
        ]);

        $result = $this->pharmacyRepository->givenPointsByClient($pharmacy->id, $card);

        $this->assertEquals($pharmacy->transactions()->where('type','given')->sum('points'), $result);
    }
}
