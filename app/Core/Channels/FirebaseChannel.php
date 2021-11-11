<?php

namespace App\Core\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

/**
 *
 */
class FirebaseChannel
{
	public function send($notifiable, Notification $notification){

		$payload = $notification->toFirebase($notifiable);
		$fields = $this->prepareFields($notifiable,$payload);
		$response = Http::withHeaders([
            'Authorization' => 'key='.env('FIREBASE_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send',$fields);
		return $response->json();
	}

	private function prepareFields($notifiable,$payload){

		//$tokens = $notifiable->tokens()->where('revoked',0)->pluck('device_token')->all();
		$tokens = $notifiable->device_token;
		$fields = [
            'priority' => 'high',
            'content_available' => true,
            'registration_ids' => [$tokens],
        ];
        return array_merge($payload,$fields);

	}
}
?>
