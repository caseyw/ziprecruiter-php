<?php

namespace ZipRecruiter;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Stream\StreamInterface;
use ZipRecruiter\Query;

class ZipRecruiterApi
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var bool
     */
    protected $expand_result = true;

    /**
     * Instantiate class with minimal fields to be make valid request
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->client->setDefaultOption('headers/User-Agent', 'caseyw/ziprecruiter-php');
    }

    /**
     * Get Client
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Return last response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Subscribe user to Zip Recruiter
     *
     * @param string $email
     * @param string $search
     * @param string $location
     * @param string|null $name
     * @param string|null $ipAddress
     * @param string|null $brand
     * @param string|null $createTime
     *
     * @return array
     */
    public function subscribe($email, $search, $location, $name = null, $ipAddress = null, $brand = null, $createTime = null)
    {

        $request = [
            'email' => $email,
            'search' => $search,
            'location' => $location,
        ];

        if (!is_null($name)) {
            $request['name'] = $name;
        }

        if (!is_null($ipAddress)) {
            $request['ip_address'] = $ipAddress;
        }

        if (!is_null($brand)) {
            $request['brand'] = $brand;
        }

        if (!is_null($createTime)) {
            $request['create_time'] = $createTime;
        }

        $url = 'subscriber';
        if ($this->isExpandedResults()) {
            $url .= '?expand_results=1';
        }

        $this->response = $this->client->post($url, ['body' => $request]);

        if (201 == $this->response->getStatusCode()) {
            return $this->response->json();
        }

        return false;
    }

    /**
     * Query API, and for my needs now we're going to loop through any pages.
     * We'll compile a list of results, and return those.
     *
     * @param \ZipRecruiter\Query $query
     *
     * @return bool|StreamInterface|array
     */
    public function query(Query $query, $subscriberId = null)
    {
        $data = [];
        $paging = true;

        $url = 'subscriber';
        if (!is_null($subscriberId)) {
            $url .= '/' . $subscriberId . '/searches';
        }

        if ($this->isExpandedResults()) {
            $url .= '?expand_results=1';
        }

        while ($paging) {

            $response = $this->client->get($url, [
                'query' => $query->toArray()
            ]);

            $json = $response->json();

            if (count($json['results']) == 0 || $json['total_count'] == 1) {
                $paging = false;
            }

            foreach ($json['results'] as $result) {
                $data[] = $result;
            }
            $query->next();

        }

        return $data;
    }

    /**
     * Helper to query Subscribers
     *
     * @param \ZipRecruiter\Query $query
     *
     * @return array|bool|\GuzzleHttp\Stream\StreamInterface
     */
    public function querySubscribers(Query $query)
    {
        return $this->query($query);
    }

    /**
     * Helper to query Searches
     *
     * @param \ZipRecruiter\Query $query
     *
     * @return array|bool|\GuzzleHttp\Stream\StreamInterface
     */
    public function queryJobSearches($subscriberId, Query $query)
    {
        return $this->query($query, $subscriberId);
    }

    /**
     * Deactivate Subscription for ID
     *
     * @param string $id
     *
     * @return string|false
     */
    public function deactivate($id)
    {
        $url = 'subscribe/' . $id;

        if ($this->isExpandedResults()) {
            $url .= '?expand_results=1';
        }

        $this->response = $this->client->delete($url);

        if (200 == $this->response->getStatusCode()) {
            return $this->response->json();
        }

        return false;
    }

    /**
     * Create Job Search for $subscriptionId
     *
     * @param $subscriptionId
     * @param $search
     * @param $location
     * @param $createTime
     *
     * @return bool|mixed
     */
    public function createJobSearch($subscriptionId, $search, $location, $createTime)
    {
        $request = [
            'search' => $search,
            'location' => $location,
            'create_time' => $createTime,
        ];

        $url = 'subscriber/' . $subscriptionId . '/searches';
        if ($this->isExpandedResults()) {
            $url .= '?expand_results=1';
        }

        $this->response = $this->client->post($url, ['body' => $request]);

        if (201 == $this->response->getStatusCode()) {
            return $this->response->json();
        }

        return false;
    }

    /**
     * Enable Expanded Results
     *
     * @return $this
     */
    public function enableExpandedResults()
    {
        $this->expand_result = true;

        return $this;
    }

    /**
     * Disable Expanded Results
     *
     * @return $this
     */
    public function disableExpandedResults()
    {
        $this->expand_result = false;

        return $this;
    }

    /**
     * Using expanded results?
     *
     * @return bool
     */
    public function isExpandedResults()
    {
        return $this->expand_result;
    }
}
