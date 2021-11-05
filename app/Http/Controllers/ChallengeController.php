<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Challenges\GetChallengeByIdRequest;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function getChallenges(Request $request)
    {
        try {
            $challange = $request->id;
            if ($challange) {


                $data = Challenge::find($challange);
            } else {

                $data = Challenge::with('business')
                    ->when(request('type') == 'started', function ($q) {
                            return $q->where('is_completed', 0);
                    })
                    ->when(request('type') == 'completed', function ($q) {
                        return $q->where('is_completed', 1);
                    })
                    ->get();
            }

            return api_success('Events', $data);

        } catch (\Exception $ex) {

            return api_error('message: ' . $ex->getMessage(), 500);

        }

    }
}
