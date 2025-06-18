<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JacobitusController extends Controller
{
    //
    public function estadoToken(Request $request)
    {
        $response = Http::withoutVerifying()->withHeaders([
            'Accept'       => 'application/json',
            'x-csrf-token' => $request->header('x-csrf-token'), // opcional
        ])->get('https://localhost:9000/api/token/connected');

        return response($response->body(), $response->status())
            ->header('Content-Type', $response->header('Content-Type'));
    }

    public function certificadosToken(Request $request)
    {
        $pin      = $request->get('pin');
        $slot     = $request->get('slot');
        $response = Http::withoutVerifying()->withHeaders([
            'Accept'       => 'application/json',
            'x-csrf-token' => $request->header('x-csrf-token'), // opcional
        ])->post('https://localhost:9000/api/token/data', [
            'pin'  => $pin,
            'slot' => $slot,
        ]);

        return response($response->body(), $response->status())
            ->header('Content-Type', $response->header('Content-Type'));
    }
}
