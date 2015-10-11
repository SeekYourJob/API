<?php namespace CVS\Texter;

use CVS\User;
use Log;

class Texter
{
	public $guzzleClient;

	public function __construct()
	{
		$this->guzzleClient = new \GuzzleHttp\Client;
	}

	public function sendToPhoneNumber($phoneNumber, $message)
	{
		if (env('TEXT_MESSAGES') == 'DISABLED') {
			\Log::warning('[TEXT] Text messages disabled. Sending aborted to ' . $phoneNumber);
			return;
		}

		if (empty($phoneNumber)) {
			\Log::warning('[TEXT] Phone number empty. Sending aborted with message ' . $message);
			return;
		}

		$this->guzzleClient->post('https://api.allmysms.com/http/9.0/', [
			'form_params' => [
				'login' => env('ALLMYSMS_LOGIN'),
				'apiKey' => env('ALLMYSMS_API_KEY'),
				'lowcost' => true,
				'message' => $message,
				'mobile' => $phoneNumber
			]
		]);

		\Log::info('[TEXT] Text message sent to ' . $phoneNumber . ' with content ' . $message);
	}

	public function sendToUser(User $user, $message)
	{
		if (env('TEXT_MESSAGES') == 'DISABLED') {
			\Log::warning('[TEXT] Text messages disabled. Sending aborted to ' . $user->phone);
			return;
		}

		if (empty($user->phone)) {
			\Log::warning('[TEXT] Phone number empty. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
			return;
		}

		if ( ! $user->sms_notifications) {
			\Log::warning('[TEXT] Text notifications disabled. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
			return;
		}

		$this->guzzleClient->post('https://api.allmysms.com/http/9.0/', [
			'form_params' => [
				'login' => env('ALLMYSMS_LOGIN'),
				'apiKey' => env('ALLMYSMS_API_KEY'),
				'lowcost' => true,
				'message' => $message,
				'mobile' => $user->phone
			]
		]);

		\Log::info('[TEXT] Text message sent to ' . $user->phone . ' with content ' . $message);
	}
}