<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function getOffers(Request $request)
    {
        try {
            $challange = $request->id;
            if ($challange) {
                $data = Offer::find($challange);
            } else {

                $data = Offer::with('business')
                    ->when(request('type') == 'Redeemed', function ($q) {
                        return $q->where('status', 'Redeemed');
                    })
                    ->get();
            }

            return api_success('Offers', $data);

        } catch (\Exception $ex) {

            return api_error('message: ' . $ex->getMessage(), 500);

        }

    }
}
