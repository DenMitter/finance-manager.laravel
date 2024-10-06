<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\StoreRequest;
use App\Models\Card;

class CardController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        Card::query()->create($data);

        return back();
    }
}
