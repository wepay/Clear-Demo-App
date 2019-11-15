<?php

namespace App\Services;

use App\Objects\Account;
use App\Objects\Dispute;
use App\Objects\MerchantInfoUS;
use App\Objects\PayoutUs;

use App\Wepay\Wepay;

use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WePayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use DateTime;

class WepayService
{
    /**
     * @var Wepay
     */
    private $wepayClient;

    /**
     * WepayService constructor.
     */
    public function __construct()
    {
        $this->wepayClient = $this->initWepayClient();
    }

    /**
     * @return Wepay
     */
    private function initWepayClient()
    {
        $wepayClient = Wepay::getInstance();

        if (!$wepayClient->isInitialized()) {
            $wepayClient->init(
                $_ENV['WEPAY_ENVIRONMENT'],
                $_ENV['WEPAY_APP_ID'],
                $_ENV['WEPAY_API_VERSION'],
                $_ENV['WEPAY_TOKEN']
            );
        }

        return $wepayClient;
    }

    /**
     * @param string $country
     *
     * @return string
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function createLegalEntity($country)
    {
        $response = $this->wepayClient->request("/legal_entities", [
            'country' => $country
        ]);

        return $response['id'];
    }

    /**
     * @param string $legalEntityId
     * @param string $token
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function updateLegalEntity($legalEntityId, $token)
    {
        $this->wepayClient->request("/legal_entities/{$legalEntityId}", [
            'token' => [
                'id' => $token
            ]
        ]);
    }

    /**
     * @param string $legalEntityId
     *
     * @return MerchantInfoUS
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function getLegalEntityUS($legalEntityId) {
        $response = $this->wepayClient->request("/legal_entities/{$legalEntityId}");

        $merchantInfo = new MerchantInfoUS();
        $merchantInfo->setEmail($response['controller']['email']);
        $merchantInfo->setFirstName($response['controller']['name']['first']);
        $merchantInfo->setLastName($response['controller']['name']['last']);
        $merchantInfo->setIsBeneficialOwner($response['controller']['is_beneficial_owner']);

        $merchantInfo->setSocialSecurityNumberIsPresent($response['controller']['personal_country_info']['US']['social_security_number_is_present']);
        $merchantInfo->setDateOfBirthIsPresent($response['controller']['date_of_birth_is_present']);

        return $merchantInfo;
    }

    /**
     * @param string $legalEntityId
     *
     * @return string
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function createAccount($legalEntityId)
    {
        $response = $this->wepayClient->request("/accounts", [
            'legal_entity_id' => $legalEntityId
        ]);

        return $response['id'];
    }

    /**
     * @param string $accountId
     *
     * @return Account
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function getAccount($accountId)
    {
        $response = $this->wepayClient->request("/accounts/{$accountId}");

        $account = new Account();
        $account->setName($response['name']);
        $account->setDescription($response['description']);
        $account->setStatementDescription($response['statement_description']);

        return $account;
    }

    /**
     * @param string $accountId
     * @param Account $account
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function updateAccount($accountId, $account)
    {
        $this->wepayClient->request("/accounts/{$accountId}", [
            'name'                  => $account->getName(),
            'description'           => $account->getDescription(),
            'statement_description' => $account->getStatementDescription()
        ]);
    }

    /**
     * @param string $accountId
     * @param string $payoutId
     * @param string $currency
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function setPayout($accountId, $payoutId, $currency)
    {
        $this->wepayClient->request("/accounts/{$accountId}", [
            'payout' => [
                'currencies' => [
                    $currency => [
                        'payout_method_id' => $payoutId,
                        'period'           => 'daily'
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param string $legalEntityId
     * @param string $token
     *
     * @return mixed
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function createPayout($legalEntityId, $token)
    {
        $response = $this->wepayClient->request("/payout_methods", [
            'legal_entity_id' => $legalEntityId,
            'nickname'        => 'Test Payout Account',
            'token'           => [
                'id' => $token
            ]
        ]);

        return $response['id'];
    }

    /**
     * @param string $payoutId
     *
     * @return PayoutUs
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function getPayoutUS($payoutId)
    {
        $response = $this->wepayClient->request("/payout_methods/{$payoutId}");

        $payout = new PayoutUs();
        $payout->setAccountNumber('****' . $response['payout_bank_us']['last_four']);
        $payout->setAccountType($response['payout_bank_us']['account_type']);

        return $payout;
    }

    /**
     * @param float  $amount
     * @param string $token
     *
     * @return mixed
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function createPayment($amount, $token)
    {
        $response = $this->wepayClient->request('/payments', [
            'account_id' => $_ENV['PAYMENT_ACCOUNT_ID'],
            'amount'     => $amount * 100,
            'currency'   => 'USD',
            'payment_method' => [
                'token'      => [
                    'id' => $token
                ]
            ]
        ], true);

        return $response['id'];
    }

    /**
     * @return array
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function disputesCollection($ownerId)
    {
        if (!$ownerId) {
            return [];
        }

        $response = $this->wepayClient->request("/disputes?owner_id=$ownerId");

        $disputes = [];

        if (!empty($response['results'])) {
            foreach ($response['results'] as $disputeData) {
                $dispute = new Dispute();
                $dispute->setId($disputeData['id']);
                $dispute->setAmount($disputeData['amount'] / 100);
                $dispute->setStatus($disputeData['status']);
                $dispute->setCreatedAt(date_timestamp_set(new DateTime(), $disputeData['create_time']));
                $dispute->setPaymentId($disputeData['payment']['id']);

                $disputes[] = $dispute;
            }
        }

        return $disputes;
    }

    /**
     * @param $disputeId
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function concedeDispute($disputeId)
    {
        $this->wepayClient->request("/disputes/{$disputeId}/concede");
    }

    /**
     * @param string $disputeId
     * @param string $documentId
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function addDocument($disputeId, $documentId) {
        $this->wepayClient->request("/disputes/{$disputeId}", [
            'documentation' => [
                'documents' => [
                    $documentId
                ],
                'explanation' => 'lorem ipsum dolores umbridge'
            ]
        ]);
    }

}