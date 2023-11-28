<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\CardService;
use App\Services\PharmacyService;
use App\Models\Client;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="FARMAPREMIUM API",
 *     version="1.0.0",
 *     description="Farmapremium API",
 *     @OA\Contact(
 *         email="laucorrs@gmail.com",
 *         name="L.Cormand"
 *     ),
 *     @OA\License(
 *         name="Licencia",
 *         url="http://www.example.com/license"
 *     )
 * )
 */
class FarmaPremiumController extends Controller
{
    /**
 * @OA\Post(
 *     path="/acumulate",
 *     summary="Acumular puntos",
 *     description="Acumular puntos a la tarjeta de fidelización del cliente",
 *     @OA\Parameter(
 *         name="client_id",
 *         in="query",
 *         description="ID del cliente",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="pharmacy_id",
 *         in="query",
 *         description="ID de la farmacia",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="points",
 *         in="query",
 *         description="Cantidad de puntos a acumular",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Puntos obtenidos en una transacción",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Puntos acumulados con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/canjear-points",
 *     summary="Canjear puntos",
 *     description="Permite canjear puntos para un cliente para su tarjeta",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="client_id", type="integer", description="ID del cliente"),
 *             @OA\Property(property="pharmacy_id", type="integer", description="ID de la farmacia"),
 *             @OA\Property(property="points", type="integer", description="Cantidad de puntos a canjear")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Puntos canjeados con éxito",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Puntos canjeados con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación o saldo insuficiente",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 *  * @OA\Post(
 *     path="/review",
 *     summary="Consultar puntos según Farmacia y Cliente y saldo del cliente",
 *     description="Obtiene información sobre los puntos otorgados por una farmacia en un periodo de tiempo, también sobre los puntos otorgados por una farmacia y por cliente, y el saldo del cliente",
 *     @OA\Parameter(
 *         name="pharmacy_id",
 *         in="query",
 *         required=true,
 *         description="ID de la farmacia",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="client_id",
 *         in="query",
 *         required=true,
 *         description="ID del cliente",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="start_date",
 *         in="query",
 *         required=false,
 *         description="Fecha de inicio (Formato: Y-m-d)",
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="end_date",
 *         in="query",
 *         required=false,
 *         description="Fecha de fin (Formato: Y-m-d)",
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Información sobre los puntos otorgados y el saldo del cliente",
 *         @OA\JsonContent(
 *             @OA\Property(property="given_points_dates", type="mixed"),
 *             @OA\Property(property="given_points_client", type="mixed"),
 *             @OA\Property(property="client_card_balance", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */

    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function acumulate(Request $request)
    {
        try {
            $this->validateInputs($request);
        } catch (ValidationException $e) {

            return response()->json(['success'=>false,'error' => $e->getMessage()], 400);
        }

        $points = $request->input('points');
        $pharmacyId = $request->input('pharmacy_id');
        $clientId = $request->input('client_id');

        $result = $this->cardService->acumulatePoints($clientId, $pharmacyId, $points);

        return response()->json(['message' => isset($result) && $result ? 'Puntos acumulados con éxito' : 'Hubo un error']);
    }

    public function canjear(Request $request)
    {
        try {
            $this->validateInputs($request);
        } catch (ValidationException $e) {

            return response()->json(['error' => $e->getMessage()], 400);
        }

        $points = $request->input('points');
        $pharmacyId = $request->input('pharmacy_id');
        $clientId = $request->input('client_id');

        $result = $this->cardService->canjearPoints($clientId, $pharmacyId, $points);
        $result == true  ? $message = 'Puntos canjeados con éxito' : $message = 'Saldo insuficiente para canjear los puntos';


        return response()->json(['message' => $message]);
    }

    public function review(Request $request, PharmacyService $pharmacyService){

        try {
            $this->validateInputs($request);
        } catch (ValidationException $e) {

            return response()->json(['error' => $e->getMessage()], 400);
        }
        
        $pharmacyId = $request->input('pharmacy_id');
        $clientId = $request->input('client_id');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $client = Client::find($clientId);

        $response = [];

        $givenPointsByDates = $pharmacyService->givenPointsBetweenDates($pharmacyId, $start, $end);
        if($client){
            $card = $client->card;
            if($card) $givenPointsByClient = $pharmacyService->givenPointsByClient($pharmacyId, $card);
            $clientCardBalance = $this->cardService->getTotalPoints($clientId);

        }else{
            $givenPointsByClient = 'No existe el cliente indicado.';
        }

        $response['given_points_dates'] = $givenPointsByDates;
        $response['given_points_client'] = $givenPointsByClient;
        $response['client_card_balance'] = $clientCardBalance;
    
        return response()->json($response);

    }

    public function validateInputs($data){
        $data->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'pharmacy_id' => 'sometimes|exists:pharmacies,id',
            'points'=>'sometimes|integer|min:0',
            'start_date'=>'sometimes|date_format:Y-m-d',
            'end_date'=>'sometimes|date_format:Y-m-d',
        ]);
    }
}
