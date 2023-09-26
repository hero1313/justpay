<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


trait SendSMSTrait
{
	protected function sendSMS($number, $text, $id)
	{

		$user = User::find($id);
		$token = $user->sms_token;
		$sender = $user->sms_name;
		$results = [
			'status' => -1
		];
		if ($token && $sender) {
			$ch = curl_init();
			$timeout = 5;

			$sender = curl_escape($ch, $sender);
			$number = curl_escape($ch, $number);
			$text = curl_escape($ch, $text);

			$parameters = "key={$token}&destination={$number}&sender={$sender}&content={$text}&urgent=true";
			$url = "https://smsoffice.ge/api/v2/send/?{$parameters}";

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);

			$jsondata = json_decode($data, true);

			if ($jsondata != NULL) {
				if ($jsondata['Success']) {
					$results['status'] = 1;
				}

				$results['errorcode'] = $jsondata['ErrorCode'];

			}

		}
		return $results;
	}

	protected function balance()
	{
		// $data = Company::find(Auth::id());
		$token = '';
		//dump($token);
		if ($token) {
			$ch = curl_init();
			$timeout = 5;
			$token = curl_escape($ch, $token);
			$url = "http://smsoffice.ge/api/getBalance/?key=" . $token;

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);/*
dump($data);
            $jsondata = json_decode($data, true);
            dump($jsondata);*/
			return $data;
		}
	}
}
