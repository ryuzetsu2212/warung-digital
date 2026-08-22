<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return response()->json(Reservation::with('table')->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'number_of_guests' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::create($validated);

        return response()->json(['message' => 'Reservasi berhasil dibuat', 'data' => $reservation], 201);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $reservation->update($validated);

        return response()->json(['message' => 'Status reservasi berhasil diubah', 'data' => $reservation]);
    }
}