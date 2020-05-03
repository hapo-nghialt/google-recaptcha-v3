<?php
    session_start();

    const VERSION_3 = 3;
    const VERSION_2 = 2;

	class RecaptchaScore
	{
        const ROBOT_MIDDLE_SCORE = 0.5;
        const G_RECAPTCHAR_V3_SECRET_KEY = 'GOOGLE_RECAPTCHA_V3_SECRET_KEY';

		public function getRecaptchaScore()
		{
			$url = 'https://www.google.com/recaptcha/api/siteverify';
            $secret_key = self::G_RECAPTCHAR_V3_SECRET_KEY;
            $response = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
			$data = [
				'secret' => $secret_key,
				'response' => $response,
			];
			$options = [
				'http' => [
					'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
					'method' => 'POST',
					'content' => http_build_query($data)
				]
            ];

			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$resultJson = json_decode($result);
			var_dump($resultJson);
			return $this->isGoodInteractionCheck($resultJson);
		}

		public function isGoodInteractionCheck($resultJson)
		{
			return $resultJson->success and $resultJson->score > self::ROBOT_MIDDLE_SCORE;
		}
    }

    $isGoodInteraction;
    $score = new RecaptchaScore();
    $isGoodInteraction = $score->getRecaptchaScore();
    if ($isGoodInteraction) {
        // good interaction case, handle here(post comment...)
    } else {
        // bad interaction case, handle here(redirect...)
    }
?>
