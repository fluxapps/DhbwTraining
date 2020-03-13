<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class ReccomenderCurl
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommenderCurl {

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    /**
     * @var xdhtObjectFacadeInterface
     */
	protected $facade;
    /**
     * @var RecommenderResponse
     */
	protected $response;


    /**
     * RecommenderCurl constructor
     *
     * @param xdhtObjectFacadeInterface $facade
     * @param RecommenderResponse $response
     */
	public function __construct(xdhtObjectFacadeInterface $facade, RecommenderResponse $response) {
	    $this->facade = $facade;
        $this->response = $response;
	}


	/**
	 * Init a Curl connection
	 *
	 * @param array  $headers
     * @param string  $rest_url
	 *
	 * @return ilCurlConnection
	 * @throws ilCurlConnectionException
	 */
	protected function initCurlConnection(string $rest_url, array $headers): ilCurlConnection {
		$curlConnection = new ilCurlConnection();

		$curlConnection->init();

		$curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
		$curlConnection->setOpt(CURLOPT_VERBOSE, true);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYPEER, false);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYHOST, false);

        switch ($this->facade->settings()->getRecommenderSystemServer()) {
            case xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_EXTERNAL:
                $url = rtrim($this->facade->settings()->getUrl() . "/") . $rest_url;
                break;

            case xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG:
                $url = ILIAS_HTTP_PATH . substr(self::plugin()->directory(), 1) . "/classes/Recommender/debug/" . trim($rest_url, "/") . ".php?ref_id=" . $this->facade->refId();
                $curlConnection->setOpt(CURLOPT_COOKIE, session_name() . '=' . session_id() . ";XDEBUG_SESSION=" . $_COOKIE["XDEBUG_SESSION"]);
                break;

            default:
                break;
        }

		$curlConnection->setOpt(CURLOPT_URL, $url);

		$curlConnection->setOpt(CURLOPT_HTTPHEADER, $headers);

		return $curlConnection;
	}


	/**
	 * @param string $rest_url
	 * @param array  $headers
	 * @param array|null   $post_data
	 */
	protected function doRequest(string $rest_url, array $headers, /*?*/array $post_data = NULL)/*:void*/ {

		$curlConnection = NULL;

		try {
			$curlConnection = $this->initCurlConnection($rest_url, $headers);

			if ($post_data !== NULL) {
                if ($this->facade->settings()->getLog()) {
                    $this->response->addSendInfo('<pre>post_data:
' . json_encode($post_data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT) . ' </pre>');
                }

				$curlConnection->setOpt(CURLOPT_POST, true);
				$curlConnection->setOpt(CURLOPT_POSTFIELDS, json_encode($post_data, JSON_FORCE_OBJECT));
			}

			$raw_response = $curlConnection->exec();

            if (empty($raw_response)) {
                $this->response->addSendError(self::plugin()->translate("error_recommender_system_not_reached"));
                return;
            }

            $result = json_decode($raw_response, true);

            if (empty($result) || !is_array($result)) {
                if ($this->facade->settings()->getLog()) {
                    $this->response->addSendInfo('<pre>raw_response:
' . $raw_response . ' </pre>');
                }

                $this->response->addSendError(self::plugin()->translate("error_recommender_system_not_reached"));
                return;
            }

            if ($this->facade->settings()->getLog()) {
                if ($this->facade->settings()->getRecommenderSystemServer() === xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG) {
                    if (!empty($result['debug_server'])) {
                        $this->response->addSendInfo('<pre>' . self::plugin()->translate("recommender_system_server_built_in_debug") . ':
' . $result['debug_server'] . '</pre>');
                    }
                    unset($result['debug_server']);
                }

                $this->response->addSendInfo('<pre>response:
' . json_encode($result, JSON_PRETTY_PRINT) . ' </pre>');
            }

            if (!empty($result['status'])) {
                $this->response->setStatus($result['status']);
            } else {
                $this->response->addSendError(self::plugin()->translate("error_recommender_system_no_status"));
                return;
            }

            if (!empty($result['recomander_id'])) {
                $this->response->setRecomanderId($result['recomander_id']);
            }

            if (!empty($result['response_type'])) {
                $this->response->setResponseType($result['response_type']);
            }

            if (!empty($result['answer_response'])) {
                $this->response->setAnswerResponse($result['answer_response']);
            }

            if (!empty($result['message'])) {
                $this->response->setMessage($result['message']);
            }
		} catch (Exception $ex) {
            if ($this->facade->settings()->getLog()) {
                $this->response->addSendError($ex->getMessage());
            } else {
                $this->response->addSendError(self::plugin()->translate("error_recommender_system_not_reached"));
            }
		} finally {
			// Close Curl connection
			if ($curlConnection !== NULL) {
				$curlConnection->close();
				$curlConnection = NULL;
			}
		}
	}


	/**
     *
	 */
	public function start()/*:void*/ {
		global $DIC;

		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];
		$data = [
			"secret" => $this->facade->settings()->getSecret(),
			"installation_key" =>  $this->facade->settings()->getInstallationKey(),
			"user_id" => $DIC->user()->getId(),
			"lang_key" => $DIC->user()->getLanguage(),
			"training_obj_id" => $this->facade->settings()->getDhbwTrainingObjectId(),
            "question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId()
		];

		$this->doRequest("api/v1/start", $headers, $data);
	}

	/**
     * @param string $recomander_id
     * @param int $question_type
     * @param mixed $answer
	 */
	public function answer(string $recomander_id, int $question_type, $answer)/*:void*/ {
		global $DIC;

		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];


		$data = [
			"secret" => $this->facade->settings()->getSecret(),
			"installation_key" => $this->facade->settings()->getInstallationKey(),
			"user_id" => $DIC->user()->getId(),
			"lang_key" => $DIC->user()->getLanguage(),
			"training_obj_id" => $this->facade->settings()->getDhbwTrainingObjectId(),
			"question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId(),
			"recomander_id" => $recomander_id,
			"question_type" => $question_type,
			"answer" => $answer
		];

		$this->doRequest("api/v1/answer", $headers, $data);
	}

	/**
     * @param string $recomander_id
     * @param int $rating
	 */
	public function sendRating(string $recomander_id, int $rating)/*:void*/ {
		global $DIC;

		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];


		$data = [
			"secret" => $this->facade->settings()->getSecret(),
			"installation_key" => $this->facade->settings()->getInstallationKey(),
			"user_id" => $DIC->user()->getId(),
			"lang_key" => $DIC->user()->getLanguage(),
			"training_obj_id" => $this->facade->settings()->getDhbwTrainingObjectId(),
			"question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId(),
			"recomander_id" => $recomander_id,
			"rating" => $rating
		];

		$this->doRequest("api/v1/rating", $headers, $data);
	}
}
