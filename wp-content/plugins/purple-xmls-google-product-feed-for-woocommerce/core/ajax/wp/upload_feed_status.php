<?php
/********************************************************************
 * Version 1.0
 * Returns status of upload feed given a feed ID.
 * Copyright 2014 Purple Turtle Productions. All rights reserved.
 * license    GNU General Public License version 3 or later; see GPLv3.txt
 * By: Tyler 2014-12-28
 ********************************************************************/
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!is_admin()) {
    die('Permission Denied!');
}
result('upload_feed_status');
result($_POST);
if (isset($_POST['provider'])) {
    if (sanitize_text_field($_POST['provider']) == 'amazonsc') {
        define('CPF_DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
        /************************************************************************
         * REQUIRED
         *
         * * Access Key ID and Secret Acess Key ID, obtained from:
         * http://aws.amazon.com
         *
         * IMPORTANT: Your Secret Access Key is a secret, and should be known
         * only by you and AWS. You should never include your Secret Access Key
         * in your requests to AWS. You should never e-mail your Secret Access Key
         * to anyone. It is important to keep your Secret Access Key confidential
         * to protect your account.
         ***********************************************************************/
        define('CPF_AWS_ACCESS_KEY_ID', sanitize_text_field($_POST['accessid']));
        define('CPF_AWS_SECRET_ACCESS_KEY', sanitize_text_field($_POST['secretid']));

        /************************************************************************
         * REQUIRED
         *
         * All MWS requests must contain a User-Agent header. The application
         * name and version defined below are used in creating this value.
         ***********************************************************************/
        define('CPF_APPLICATION_NAME', 'MarketplaceWebServiceProducts PHP5 Library');
        define('CPF_APPLICATION_VERSION', '2');

        /************************************************************************
         * REQUIRED
         *
         * All MWS requests must contain the seller's merchant ID and
         * marketplace ID.
         ***********************************************************************/
        define('CPF_MERCHANT_ID', sanitize_text_field($_POST['sellerid']));

        set_include_path(dirname(__FILE__) . '/../../../');
        foreach (glob(dirname(__FILE__) . '/../../../MarketplaceWebService/*.php') as $file)
            require_once $file;
        foreach (glob(dirname(__FILE__) . '/../../../MarketplaceWebService/Model/*.php') as $file)
            require_once $file;
        //require_once dirname(__FILE__) . '/../../../MarketplaceWebService/Model/SubmitFeedRequest.php';

        $serviceUrl = "https://mws.amazonservices.com";
        $config = array(
            'ServiceURL' => $serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'MaxErrorRetry' => 3,
        );

        /************************************************************************
         * Instantiate Implementation of MarketplaceWebService
         *
         * CPF_AWS_ACCESS_KEY_ID and CPF_AWS_SECRET_ACCESS_KEY constants
         * are defined in the .config.inc.php located in the same
         * directory as this sample
         ***********************************************************************/
        $service = new MarketplaceWebService_Client(
            CPF_AWS_ACCESS_KEY_ID,
            CPF_AWS_SECRET_ACCESS_KEY,
            $config,
            CPF_APPLICATION_NAME,
            CPF_APPLICATION_VERSION);

        $request = new MarketplaceWebService_Model_GetFeedSubmissionResultRequest();
        $request->setMerchant(CPF_MERCHANT_ID);
        $request->setFeedSubmissionId(sanitize_text_field($_POST['feedid']));
        $request->setFeedSubmissionResult(@fopen('php://memory', 'rw+'));

        invokeGetFeedSubmissionResult($service, $request);
    }
}


/**
 * Get Feed Submission Result Action Sample
 * retrieves the feed processing report
 *
 * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
 * @param mixed $request MarketplaceWebService_Model_GetFeedSubmissionResult or array of parameters
 */
function invokeGetFeedSubmissionResult(MarketplaceWebService_Interface $service, $request)
{
    try {
        $response = $service->getFeedSubmissionResult($request);

        result("Service Response\n");
        result("=============================================================================\n");

        result("        GetFeedSubmissionResultResponse\n");
        if ($response->isSetGetFeedSubmissionResultResult()) {
            $getFeedSubmissionResultResult = $response->getGetFeedSubmissionResultResult();
            result("            GetFeedSubmissionResult");

            if ($getFeedSubmissionResultResult->isSetContentMd5()) {
                result("                ContentMd5");
                result("                " . $getFeedSubmissionResultResult->getContentMd5() . "\n");
            }
        }
        if ($response->isSetResponseMetadata()) {
            result("            ResponseMetadata\n");
            $responseMetadata = $response->getResponseMetadata();
            if ($responseMetadata->isSetRequestId()) {
                result("                RequestId\n");
                result("                    " . $responseMetadata->getRequestId() . "\n");
            }
        }

        result("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
        echo json_encode($response);
    } catch (MarketplaceWebService_Exception $ex) {
        result("Caught Exception: " . $ex->getMessage() . "\n");
        result("Response Status Code: " . $ex->getStatusCode() . "\n");
        result("Error Code: " . $ex->getErrorCode() . "\n");
        result("Error Type: " . $ex->getErrorType() . "\n");
        result("Request ID: " . $ex->getRequestId() . "\n");
        result("XML: " . $ex->getXML() . "\n");
        result("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
    }
}

function result($str)
{
    if (is_array($str)) {
        file_put_contents('result.txt', print_r(wp_unslash($str), true) . "\r\n", FILE_APPEND);
    } else {
        file_put_contents('result.txt', print_r(sanitize_text_field($str), true) . "\r\n", FILE_APPEND);
    }
}
