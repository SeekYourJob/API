<?php namespace CVS\Texter;

use CVS\HistoryText;
use CVS\Jobs\SendTextToPhoneNumber;
use CVS\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;
use Log;

class Texter
{
	use DispatchesJobs;

	public function sendToPhoneNumber($phoneNumber, $message, $historyId = false)
	{
		if (env('TEXT_MESSAGES') == 'DISABLED') {
			\Log::warning('[TEXT] Text messages disabled. Sending aborted to ' . $phoneNumber);
			return;
		}

		if (empty($phoneNumber)) {
			\Log::warning('[TEXT] Phone number empty. Sending aborted with message ' . $message);
			return;
		}

		// Saving in history...
		if (!$historyId)
			$historyId = HistoryText::create(['phone' => $phoneNumber, 'message' => $message])->id;

		// The magic happens here... (almost!)
		$this->dispatch(new SendTextToPhoneNumber($phoneNumber, $message, $historyId));
	}

	public function sendToUser(User $user, $message, $forceSend = false)
	{
		if (env('TEXT_MESSAGES') == 'DISABLED') {
			\Log::warning('[TEXT] Text messages disabled. Sending aborted to ' . $user->phone);
			return;
		}

		if (empty($user->phone)) {
			\Log::warning('[TEXT] Phone number empty. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
			return;
		}

		// Checking is mobile phone number
		$phoneUtils = app('PhoneUtils');
		try {
			$phoneNumberProto = $phoneUtils->parse($user->phone, "FR");
			if ($phoneUtils->getNumberType($phoneNumberProto) !== PhoneNumberType::MOBILE) {
				\Log::warning('[TEXT] Not a mobile phone number. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
				return ;
			}
		} catch (NumberParseException $e) {
			\Log::warning('[TEXT] Phone number invalid. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
			return ;
		}

		if (!$forceSend && !$user->sms_notifications) {
			\Log::warning('[TEXT] Text notifications disabled. Sending aborted to ' . $user->firstname . ' ' . $user->lastname .' with message ' . $message);
			return;
		}

		// Saving in history...
		$history = HistoryText::create(['user_id' => $user->id, 'message' => $message]);

		// Sending SMS...
		 $this->sendToPhoneNumber($user->phone, $message, $history->id);
	}

	public static function doSendToPhoneNumber($phoneNumber, $message, $historyId)
	{
		$guzzleClient = new \GuzzleHttp\Client();

		$response = $guzzleClient->post('https://api.allmysms.com/http/9.0/', [
			'form_params' => [
				'login' => env('ALLMYSMS_LOGIN'),
				'apiKey' => env('ALLMYSMS_API_KEY'),
				'tpoa' => 'SeekYourJob',
				'message' => $message . "\r\nSTOP au 36180",
				'mobile' => $phoneNumber
			]
		]);

		$history = HistoryText::findOrFail($historyId);
		$history->ack = $response->getBody();
		$history->save();
	}

	public static function getRemainingCredits()
	{
		$guzzleClient = new \GuzzleHttp\Client();

		try {
			$response = $guzzleClient->post('https://api.allmysms.com/http/9.0/getInfo', [
				'form_params' => [
					'login' => env('ALLMYSMS_LOGIN'),
					'apiKey' => env('ALLMYSMS_API_KEY')
				]
			]);
			$response = json_decode($response->getBody());

			return [
				'remaining_sms' => floor($response->credits / 15),
				'remaining_credits' => (int) $response->credits,
				'credits_per_sms' => 15
			];
		} catch (\Exception $exception) {
			return [
				'remaining_sms' => 0,
				'remaining_credits' => 0,
				'credits_per_sms' => 15
			];
		}
	}
}