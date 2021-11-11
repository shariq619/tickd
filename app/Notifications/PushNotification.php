<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification {
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public $title;
	public $body;
	public $data;

	public function __construct($title = null, $body = null, $data = []) {
		$this->title = $title;
		$this->body = $body;
		$this->data = $data;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable) {
		return ['firebase', 'database'];
	}

	/**
	 * Get the firebase representation of the notification.
	 */
	public function toDatabase($notifiable) {
		return [
			'title' => $this->title,
			'body' => $this->body,
			'data' => $this->data,
		];
	}
	public function toFirebase($notifiable) {
		return [
			'notification' => [
				'title' => $this->title,
				'body' => $this->body,
			],
			'data' => $this->data['data']??[],
		];
	}
	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		return (new MailMessage)
			->line('The introduction to the notification.')
			->action('Notification Action', url('/'))
			->line('Thank you for using our application!');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable) {
		return [
			//
		];
	}
}
