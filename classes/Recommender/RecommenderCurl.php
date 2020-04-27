<?php

use srag\DIC\DhbwTraining\DICTrait;
use srag\Plugins\DhbwTraining\Config\Config;


/**
 * Class ReccomenderCurl
 *
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommenderCurl
{

    use DICTrait;

    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const KEY_RESPONSE_TIME_START = ilDhbwTrainingPlugin::PLUGIN_PREFIX . "_response_time_start";
    const KEY_RESPONSE_PROGRESS_METER = ilDhbwTrainingPlugin::PLUGIN_PREFIX . "_response_progress_meter";
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
     * @param RecommenderResponse       $response
     */
    public function __construct(xdhtObjectFacadeInterface $facade, RecommenderResponse $response)
    {
        $this->facade = $facade;
        $this->response = $response;
    }


    /**
     *
     */
    public function start()/*:void*/
    {
        global $DIC;

        ilSession::clear(self::KEY_RESPONSE_TIME_START);

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json"
        ];
        $data = [
            "secret"               => $this->facade->settings()->getSecret(),
            "installation_key"     => $this->facade->settings()->getInstallationKey(),
            "user_id"              => $this->getAnonymizedUserHash(),
            "lang_key"             => $DIC->user()->getLanguage(),
            "training_obj_id"      => $this->facade->settings()->getDhbwTrainingObjectId(),
            "question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId()
        ];

        $this->doRequest("api/v1/start", $headers, $data);
    }


    /**
     * @return string
     */
    protected function getAnonymizedUserHash() : string
    {
        return md5(Config::getField(Config::KEY_SALT) . self::dic()->user()->getLogin());
    }


    /**
     * @param string $rest_url
     * @param array  $headers
     * @param array  $post_data
     */
    protected function doRequest(string $rest_url, array $headers, array $post_data = [])/*:void*/
    {

        $curlConnection = null;

        try {
            $curlConnection = $this->initCurlConnection($rest_url, $headers);

            $response_time_start = intval(ilSession::get(self::KEY_RESPONSE_TIME_START));
            if (!empty($response_time_start)) {
                $post_data["response_time"] = (time() - $response_time_start);
            }

            if ($this->facade->settings()->getLog()) {
                $this->response->addSendInfo('<pre>post_data:
' . json_encode($post_data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT) . ' </pre>');
            }

            $curlConnection->setOpt(CURLOPT_POST, true);
            $curlConnection->setOpt(CURLOPT_POSTFIELDS, json_encode($post_data, JSON_FORCE_OBJECT));

            $raw_response = $curlConnection->exec();

            if (empty($raw_response)) {
                $this->response->addSendFailure(self::plugin()->translate("error_recommender_system_not_reached"));

                return;
            }

            $result = json_decode($raw_response, true);

            if (empty($result) || !is_array($result)) {
                if ($this->facade->settings()->getLog()) {
                    $this->response->addSendInfo('<pre>raw_response:
' . $raw_response . ' </pre>');
                }

                $this->response->addSendFailure(self::plugin()->translate("error_recommender_system_not_reached"));

                return;
            }

            if ($this->facade->settings()->getLog()) {
                if ($this->facade->settings()->getRecommenderSystemServer() === xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG) {
                    if (!empty($result['debug_server'])) {
                        $this->response->addSendInfo('<pre>' . self::plugin()->translate("recommender_system_server_built_in_debug") . ':
' . json_encode($result['debug_server'], JSON_PRETTY_PRINT) . '</pre>');
                    }
                    unset($result['debug_server']);
                }

                $this->response->addSendInfo('<pre>response:
' . json_encode($result, JSON_PRETTY_PRINT) . ' </pre>');
            }

            if (!empty($result['status'])) {
                $this->response->setStatus(strval($result['status']));
            } else {
                $this->response->addSendFailure(self::plugin()->translate("error_recommender_system_no_status"));

                return;
            }

            if (!empty($result['recomander_id'])) {
                $this->response->setRecomanderId(strval($result['recomander_id']));
            }

            if (!empty($result['response_type'])) {
                $this->response->setResponseType(intval($result['response_type']));
            }

            if (!empty($result['answer_response'])) {
                $this->response->setAnswerResponse(strval($result['answer_response']));
            }

            if (!empty($result['answer_response_type'])) {
                $this->response->setAnswerResponseType(strval($result['answer_response_type']));
            }

            if (!empty($result['message'])) {
                $this->response->setMessage(strval($result['message']));
            }

            if (!empty($result['message_type'])) {
                $this->response->setMessageType(strval($result['message_type']));
            }

            if (isset($result['progress'])) {
                $this->response->setProgress($result['progress'] !== null ? floatval($result['progress']) : null);
            }

            if (!empty($result['progress_type'])) {
                $this->response->setProgressType(strval($result['progress_type']));
            }

            if (isset($result['learning_progress_status'])) {
                $this->response->setLearningProgressStatus($result['learning_progress_status'] !== null ? intval($result['learning_progress_status']) : null);
            }

            if (!empty($result['competences'])) {
                $this->response->setCompetences((array) $result['competences']);
            }

            if (!empty($result['progress_meters'])) {


                $progress_meter_list = [];
                foreach($result['progress_meters'] as $key => $value){
                    $progress_meter_list[] = ProgressMeter::newFromArray($value);
                }

                ilSession::set(self::KEY_RESPONSE_PROGRESS_METER, serialize($progress_meter_list));


                $this->response->setProgressmeters((array) $progress_meter_list);
            } else {
                if(strlen(ilSession::get(self::KEY_RESPONSE_PROGRESS_METER)) > 0) {
                   $this->response->setProgressmeters((array) unserialize(ilSession::get(self::KEY_RESPONSE_PROGRESS_METER)));
                }
            }


        } catch (Exception $ex) {
            if ($this->facade->settings()->getLog()) {
                $this->response->addSendFailure($ex->getMessage());
            } else {
                $this->response->addSendFailure(self::plugin()->translate("error_recommender_system_not_reached"));
            }
        } finally {
            // Close Curl connection
            if ($curlConnection !== null) {
                $curlConnection->close();
                $curlConnection = null;
            }
        }

        ilSession::set(self::KEY_RESPONSE_TIME_START, time());
    }


    /**
     * Init a Curl connection
     *
     * @param array  $headers
     * @param string $rest_url
     *
     * @return ilCurlConnection
     * @throws ilCurlConnectionException
     */
    protected function initCurlConnection(string $rest_url, array $headers) : ilCurlConnection
    {
        $curlConnection = new ilCurlConnection();

        $curlConnection->init();

        $curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curlConnection->setOpt(CURLOPT_VERBOSE, true);
        $curlConnection->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curlConnection->setOpt(CURLOPT_SSL_VERIFYHOST, false);

        switch ($this->facade->settings()->getRecommenderSystemServer()) {
            case xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_EXTERNAL:
                $url = rtrim($this->facade->settings()->getUrl(), "/") . $rest_url;
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
     * @param string $recomander_id
     * @param int    $question_type
     * @param mixed  $answer
     */
    public function answer(string $recomander_id, int $question_type, $answer)/*:void*/
    {
        global $DIC;

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json"
        ];

        $data = [
            "secret"               => $this->facade->settings()->getSecret(),
            "installation_key"     => $this->facade->settings()->getInstallationKey(),
            "user_id"              => $this->getAnonymizedUserHash(),
            "lang_key"             => $DIC->user()->getLanguage(),
            "training_obj_id"      => $this->facade->settings()->getDhbwTrainingObjectId(),
            "question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId(),
            "recomander_id"        => $recomander_id,
            "question_type"        => $question_type,
            "answer"               => $answer
        ];

        $this->doRequest("api/v1/answer", $headers, $data);
    }


    /**
     * @param string $recomander_id
     * @param int    $rating
     */
    public function sendRating(string $recomander_id, int $rating)/*:void*/
    {
        global $DIC;

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json"
        ];

        $data = [
            "secret"               => $this->facade->settings()->getSecret(),
            "installation_key"     => $this->facade->settings()->getInstallationKey(),
            "user_id"              => $this->getAnonymizedUserHash(),
            "lang_key"             => $DIC->user()->getLanguage(),
            "training_obj_id"      => $this->facade->settings()->getDhbwTrainingObjectId(),
            "question_pool_obj_id" => $this->facade->settings()->getQuestionPoolId(),
            "recomander_id"        => $recomander_id,
            "rating"               => $rating
        ];

        $this->doRequest("api/v1/rating", $headers, $data);
    }
}
