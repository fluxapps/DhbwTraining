<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Recommender/RecommenderResponse.php');


/*
use CURLFile;
use Exception;
use ilCurlConnection;
use ilCurlConnectionException;
use srag\DIC\HelpMe\DICTrait;
use Throwable;
*/


/**
 * Class ReccomenderCurl
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommenderCurl {

	use xdhtDIC;
	/**
	 * @var string
	 */
	const AUTHORIZATION_USERNAMEPASSWORD = "usernamepassword";
	/**
	 * @var string
	 */
	const AUTHORIZATION_OAUTH = "oauth";
	/**
	 * @var string
	 */
	protected $jira_domain = "";
	/**
	 * @var string
	 */
	protected $jira_authorization = "";
	/**
	 * @var string
	 */
	protected $jira_username = "";
	/**
	 * @var string
	 */
	protected $jira_password = "";
	/**
	 * @var string
	 */
	protected $jira_consumer_key = "";
	/**
	 * @var string
	 */
	protected $jira_private_key = "";
	/**
	 * @var string
	 */
	protected $jira_access_token = "";


	/**
	 * JiraCurl constructor
	 */
	public function __construct() {
	}


	/**
	 * Init a Jira Curl connection
	 *
	 * @param string $url
	 * @param array  $headers
	 *
	 * @return ilCurlConnection
	 * @throws ilCurlConnectionException
	 */
	protected function initCurlConnection(string $url, array $headers): ilCurlConnection {
		$curlConnection = new ilCurlConnection();

		$curlConnection->init();

		$curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
		$curlConnection->setOpt(CURLOPT_VERBOSE, true);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYPEER, false);
		$curlConnection->setOpt(CURLOPT_SSL_VERIFYHOST, false);
		$curlConnection->setOpt(CURLOPT_URL, $url);


		$curlConnection->setOpt(CURLOPT_HTTPHEADER, $headers);

		return $curlConnection;
	}


	/**
	 * @param string $rest_url
	 * @param array  $headers
	 * @param null   $post_data
	 *
	 * @return null|RecommenderResponse
	 */
	protected function doRequest(string $rest_url, array $headers, $post_data = NULL,xdhtSettingsInterface $settings)/*: ?array*/ {
		//Todo
		//$url = "http://172.28.128.6:5000/" . $rest_url;

		$url = $settings->getUrl().$rest_url;

		$curlConnection = NULL;


		try {
			$curlConnection = $this->initCurlConnection($url, $headers);

			if ($post_data !== NULL) {
				$curlConnection->setOpt(CURLOPT_POST, true);
				$curlConnection->setOpt(CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				$curlConnection->setOpt(CURLOPT_POSTFIELDS, $post_data);
			}

			if($settings->getLog()) {
				global $DIC;
				$DIC->logger()->root()->log("xdht - POST".print_r($post_data,true));

			}

			$result = $curlConnection->exec();

			$result = json_decode($result, true);

			if($settings->getLog()) {
				global $DIC;
				$DIC->logger()->root()->log("xdht - RESULT".print_r($result,true));
			}


			if(empty($result['status'])) {
				ilUtil::sendFailure("Es ist ein Fehler aufgetreten - Kein Status".print_r($result,true),true);
				$this->ctrl()->redirectByClass("xdhtStartGUI", xdhtStartGUI::CMD_STANDARD);
			}



			/*
			if(is_null($result['status'])) {
				$response = new RecommenderResponse();
				$response->setStatus(RecommenderResponse::STATUS_ERROR);
				$response->setResponseType(RecommenderResponse::RESPONSE_TYPE['TEST_IS_FINISHED']);
				return $response;
			}*/

			$response = new RecommenderResponse();
			$response->setStatus($result['status']);
			$response->setQuestionId($result['question_id']);
			$response->setResponseType($result['response_type']);
			$response->setAnswerResponse($result['answer_response']);

			if(!is_null($result['message'])) {
				$response->setMessage($result['message']);
			}

			return $response;
		} catch (Exception $ex) {
			// Curl-Error!
			return NULL;
		} finally {
			// Close Curl connection
			if ($curlConnection !== NULL) {
				$curlConnection->close();
				$curlConnection = NULL;
			}
		}
	}


	/**
	 * @return null|RecommenderResponse
	 */
	public function start(xdhtSettingsInterface $settings) {
		global $DIC;

		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];


		$data = [
			"secret" => $settings->getSecret(),
			"installation_key" =>  $settings->getInstallationKey(),
			"user_id" => $DIC->user()->getId(),
			"lang_key" => $DIC->user()->getLanguage(),
			"training_obj_id" => $settings->getDhbwTrainingObjectId(),
			"question_pool_obj_id" => $settings->getQuestionPoolId()
		];


		$response = $this->doRequest("api/v1/start", $headers, json_encode($data),$settings);

		return $response;
	}

	/**
	 * @return null|RecommenderResponse
	 */
	public function answer($question_id, $question_type, $answer,xdhtSettingsInterface $settings) {
		global $DIC;

		$headers = [
			"Accept" => "application/json",
			"Content-Type" => "application/json"
		];


		$data = [
			"secret" => $settings->getSecret(),
			"installation_key" => $settings->getInstallationKey(),
			"user_id" => $DIC->user()->getId(),
			"lang_key" => $DIC->user()->getLanguage(),
			"training_obj_id" => $settings->getDhbwTrainingObjectId(),
			"question_pool_obj_id" => $settings->getQuestionPoolId(),
			"question_id" => $question_id,
			"question_type" => $question_type,
			"answer" => $answer
		];

		$response = $this->doRequest("api/v1/answer", $headers, json_encode($data,JSON_FORCE_OBJECT),$settings);

		return $response;
	}
}
