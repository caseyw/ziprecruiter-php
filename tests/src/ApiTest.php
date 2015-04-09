<?php

use GuzzleHttp\Client;
use GuzzleHttp\Ring\Client\MockHandler;
use ZipRecruiter\Query;
use ZipRecruiter\ZipRecruiterApi;

class ApiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Setup client
     *
     * @param array $mockResponse
     *
     * @return \GuzzleHttp\Client
     */
    protected function setUpClient($mockResponse)
    {
        $mock = new MockHandler($mockResponse);
        $client = new Client(
            [
                'base_url' => 'https://api.ziprecruiter.com/job-alerts/v2',
                'handler' => $mock,
                'defaults' => [
                    'auth' => [
                        'ZipRecruiter Username',
                        'ZipRecruiter Password'
                    ],
                ]
            ]
        );

        return $client;
    }

    /**
     * Test Can Subscribe One Email
     */
    public function testCanSubscribeOneEmail()
    {
        $email = 'johndoe@example.com';
        $name = 'John Doe';
        $createTime = '2015-04-09T13:03:30';
        $search = 'First Search For User';
        $location = 'Canton, OH';
        $ipAddress = '8.8.8.8';
        $brand = 'example';

        $json = $this->generateJsonResponse(
            [
                'email' => $email,
                'search' => $search,
                'location' => $location,
                'brand' => $brand,
                'create_time' => $createTime,
            ]
        );

        $respGeneratedArr = json_decode($json, true);

        $response = [
            'body' => $json,
            'status' => 201,
        ];
        $client = $this->setUpClient($response);


        $api = new ZipRecruiterApi($client);
        $resp = $api->subscribe($email, $search, $location, $name, $ipAddress, $brand, $createTime);

        $this->assertEquals($respGeneratedArr['email_md5'], $resp['email_md5']);
        $this->assertNull($resp['deactivation_reason']);
        $this->assertEquals($createTime, $resp['create_time']);
        $this->assertEquals($respGeneratedArr['initial_search_id'], $resp['initial_search_id']);
        $this->assertEquals($brand, $resp['brand']);
        $this->assertEquals($respGeneratedArr['id'], $resp['id']);
        $this->assertNull($resp['deactivation_time']);
    }

    /**
     * Create response from data being passed
     *
     * @param array $data
     *
     * @return string
     */
    protected function generateJsonResponse(Array $data)
    {
        $email_md5 = md5(strtolower($data['email']));

        $resp = [
            'email_md5' => $email_md5,
            'deactivation_reason' => null,
            'create_time' => $data['create_time'],
            'initial_search_id' => substr($email_md5, 8, 8),
            'brand' => $data['brand'],
            'id' => substr($email_md5, 16, 8),
            'deactivation_time' => null,
        ];

        return json_encode($resp);
    }

    /**
     * Test Can Query And Get Result
     */
    public function testCanQueryAndGetResult()
    {
        $query = new Query();
        $query->setId('12345678');
        $query->setEmail('johndoe@example.com');
        $query->setEmailMd5(md5($query->getEmail()));
        $query->setName('John Doe');
        $query->setCreateTime('2015-04-09 14:50:30');
        $query->setSearch('Terms');
        $query->setLocation('Canton, OH');
        $query->setIpAddress('8.8.8.8');
        $query->setStatus('deactivated');
        $query->setBrand('example');

        $this->assertEquals('12345678', $query->getId());
        $this->assertEquals('johndoe@example.com', $query->getEmail());
        $this->assertEquals('John Doe', $query->getName());
        $this->assertEquals('2015-04-09 14:50:30', $query->getCreateTime());
        $this->assertEquals('Terms', $query->getSearch());
        $this->assertEquals('Canton, OH', $query->getLocation());
        $this->assertEquals('8.8.8.8', $query->getIpAddress());
        $this->assertEquals('deactivated', $query->getStatus());
        $this->assertEquals('example', $query->getBrand());


        $response = [
            'body' => $this->generateQueryJsonResponse($query),
            'status' => 200,
        ];

        $client = $this->setUpClient($response);

        $api = new ZipRecruiterApi($client);
        $resp = $api->query($query);

        $this->assertEquals($query->getLimit(), $resp['limit']);
        $this->assertEquals($query->getSkip(), $resp['offset']);

        $resp = $resp['results'];

        $this->assertEquals($query->getEmailMd5(), $resp['email_md5']);
        $this->assertNull($resp['deactivation_reason']);
        $this->assertEquals($query->getCreateTime(), $resp['create_time']);
        $this->assertEquals($query->getBrand(), $resp['brand']);
        $this->assertEquals($query->getId(), $resp['id']);
        $this->assertNull($resp['deactivation_time']);
    }

    /**
     * Helper to generate result from query
     *
     * @param Query $query
     *
     * @return string
     */
    protected function generateQueryJsonResponse(Query $query)
    {
        $response = [
            'limit' => $query->getLimit(),
            'results' => [
                'email_md5' => $query->getEmailMd5(),
                'deactivation_reason' => null,
                'create_time' => $query->getCreateTime(),
                'brand' => $query->getBrand(),
                'id' => $query->getId(),
                'deactivation_time' => null
            ],
            'offset' => $query->getSkip(),
            'total_count' => '1'
        ];

        return json_encode($response);
    }

    /**
     * Test Can Deactivate Subscription
     */
    public function testCanDeactivateSubscription()
    {
        $idToDeactivate = '12345678';

        $response = [
            'body' => '{"email_md5":"12345678901234567890123456789012","deactivation_reason":"unsubscribed","create_time":"2015-05-09T16:45:17","brand":"example","id":"12345678","deactivation_time":"2015-04-09T17:00:00"}',
            'status' => 200,
        ];

        $client = $this->setUpClient($response);
        $api = new ZipRecruiterApi($client);
        $resp = $api->deactivate($idToDeactivate);

        $this->assertEquals('12345678901234567890123456789012', $resp['email_md5']);
        $this->assertEquals('unsubscribed', $resp['deactivation_reason']);
        $this->assertEquals('2015-05-09T16:45:17', $resp['create_time']);
        $this->assertEquals('example', $resp['brand']);
        $this->assertEquals('12345678', $resp['id']);
        $this->assertEquals('2015-04-09T17:00:00', $resp['deactivation_time']);
    }
}
