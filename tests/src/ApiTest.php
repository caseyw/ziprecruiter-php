<?php

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Ring\Client\MockHandler;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
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
        $query->setDeactivationTime('2015-04-09 14:50:30');
        $query->setDeactivationReason('unsubscribe');

        $this->assertEquals('12345678', $query->getId());
        $this->assertEquals('johndoe@example.com', $query->getEmail());
        $this->assertEquals('John Doe', $query->getName());
        $this->assertEquals('2015-04-09 14:50:30', $query->getCreateTime());
        $this->assertEquals('Terms', $query->getSearch());
        $this->assertEquals('Canton, OH', $query->getLocation());
        $this->assertEquals('8.8.8.8', $query->getIpAddress());
        $this->assertEquals('deactivated', $query->getStatus());
        $this->assertEquals('example', $query->getBrand());
        $this->assertEquals('2015-04-09 14:50:30', $query->getDeactivationTime());
        $this->assertEquals('unsubscribe', $query->getDeactivationReason());

        $response = [
            'body' => $this->generateQueryJsonResponse($query),
            'status' => 200,
        ];

        $client = $this->setUpClient($response);

        $api = new ZipRecruiterApi($client);
        $resp = $api->querySubscribers($query);

        $this->assertEquals($query->getEmailMd5(), $resp[0]['email_md5']);
        $this->assertEquals($query->getDeactivationReason(), $resp[0]['deactivation_reason']);
        $this->assertEquals($query->getDeactivationTime(), $resp[0]['deactivation_time']);
        $this->assertEquals($query->getCreateTime(), $resp[0]['create_time']);
        $this->assertEquals($query->getBrand(), $resp[0]['brand']);
        $this->assertEquals($query->getId(), $resp[0]['id']);
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
                0 => [
                    'email_md5' => $query->getEmailMd5(),
                    'deactivation_reason' => $query->getDeactivationReason(),
                    'create_time' => $query->getCreateTime(),
                    'brand' => $query->getBrand(),
                    'id' => $query->getId(),
                    'deactivation_time' => $query->getDeactivationTime()
                ]
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

    /**
     * Test Can Get Multiple Result Pages
     */
    public function testCanGetMultipleResultPages()
    {
        $client = new Client();

        $responses = [
            new Response(200, [], Stream::factory('{"limit":"1","results":[{"email_md5":"12345678901234567890123456789012","deactivation_reason":"inactivity-never-clicked-14-day","create_time":"2015-01-01T02:31:25","brand":"example1","id":"12345678","deactivation_time":"2015-04-10T23:53:31"}],"offset":"0","total_count":"3"}')),
            new Response(200, [], Stream::factory('{"limit":"1","results":[{"email_md5":"12345678901234567890123456789012","deactivation_reason":"inactivity-120-day","create_time":"2015-01-01T00:24:21","brand":"example2","id":"23456789","deactivation_time":"2015-04-10T23:50:02"}],"offset":"1","total_count":"3"}')),
            new Response(200, [], Stream::factory('{"limit":"1","results":[{"email_md5":"12345678901234567890123456789012","deactivation_reason":"inactivity-never-cli","create_time":"2015-01-01T16:00:24","brand":"example3","id":"34567890","deactivation_time":"2015-04-10T00:13:55"}],"offset":"2","total_count":"3"}')),
            new Response(200, [], Stream::factory('{"limit":"1","results":[],"offset":"3","total_count":"3"}')),
        ];

        $mock = new Mock($responses);
        $client->getEmitter()->attach($mock);

        $query = new Query();
        $query->setDeactivationTime('2015-04-10 10:30:30');
        $query->setLimit(1);
        $query->setSkip(0);

        $api = new ZipRecruiterApi($client);
        $results = $api->querySubscribers($query);

        $this->assertEquals(3, count($results));
    }

    /**
     * Test Can Create Job Search For Subscriber
     */
    public function testCanCreateJobSearchForSubscriber()
    {
        $client = new Client();

        $json = '{"search":"Test","location":"Canton, OH","deactivation_reason":null,"create_time":"2015-04-10T07:00:00","id":"12345678","deactivation_time":null}';

        $responses = [
            new Response(201, [], Stream::factory($json)),
        ];

        $mock = new Mock($responses);
        $client->getEmitter()->attach($mock);

        $subscriberId = '09876543';
        $search = 'Test';
        $location = 'Canton, OH';
        $createTime = '2015-04-10T07:00:00';
        $searchId = '12345678';

        $api = new ZipRecruiterApi($client);
        $results = $api->createJobSearch($subscriberId, $search, $location, $createTime);

        $this->assertEquals(json_decode($json, true), $results);
        $this->assertEquals($search, $results['search']);
        $this->assertEquals($location, $results['location']);
        $this->assertNull($results['deactivation_reason']);
        $this->assertEquals($createTime, $results['create_time']);
        $this->assertEquals($searchId, $results['id']);
        $this->assertNull($results['deactivation_time']);
    }

}
