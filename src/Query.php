<?php

namespace ZipRecruiter;

class Query
{

    /**
     * Expand API results by default
     *
     * @var bool
     */
    protected $expand_results = 1;

    /**
     * Unique identifier for this subscriber
     *
     * @var string
     */
    protected $id;

    /**
     * Subscriber Email
     *
     * @var string
     */
    protected $email;

    /**
     * Subscriber Email in md5 hash
     *
     * @var string
     */
    protected $emailMd5;

    /**
     * Subscriber Name
     *
     * @var string
     */
    protected $name;

    /**
     * Created Time
     *
     * @var string
     */
    protected $create_time;

    /**
     * Search
     *
     * @var string
     */
    protected $search;

    /**
     * Location, can be City, ST, STATE or Postal Code
     *
     * @var string
     */
    protected $location;

    /**
     * IP Address
     *
     * @var string
     */
    protected $ip_address;

    /**
     * Subscription Status
     *
     * @var string
     */
    protected $status;

    /**
     * Brand, which is domain without tld
     * e.g. www.example.com => example
     *
     * @var string
     */
    protected $brand;

    /**
     * Subscription Deactivation Time
     *
     * @var string
     */
    protected $deactivation_time;

    /**
     * Subscription Deactivation Reason
     *
     * @var string
     */
    protected $deactivation_reason;

    /**
     * Limit per page
     *
     * @var integer
     */
    protected $limit = 100;

    /**
     * How many to skip, used for paging
     *
     * @var integer
     */
    protected $skip = 0;

    /**
     * Get Create Time
     *
     * @return string
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set Create Time
     *
     * @param string $create_time
     *
     * @return $this
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;

        return $this;
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get Email MD5
     *
     * @return string
     */
    public function getEmailMd5()
    {
        return $this->emailMd5;
    }

    /**
     * Set Email MD5
     *
     * @param string $emailMd5
     *
     * @return $this
     */
    public function setEmailMd5($emailMd5)
    {
        $this->emailMd5 = $emailMd5;

        return $this;
    }

    /**
     * Get ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get IP Address
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set IP Address
     *
     * @param string $ip_address
     *
     * @return $this
     */
    public function setIpAddress($ip_address)
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    /**
     * Get Location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set Location
     *
     * @param string $location
     *
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set Search
     *
     * @param string $search
     *
     * @return $this
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get Brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set Brand
     *
     * @param string $brand
     *
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get Deactivation Reason
     *
     * @return string
     */
    public function getDeactivationReason()
    {
        return $this->deactivation_reason;
    }

    /**
     * Set Deactivation Reason
     *
     * @param string $deactivation_reason
     *
     * @return $this
     */
    public function setDeactivationReason($deactivation_reason)
    {
        $this->deactivation_reason = $deactivation_reason;

        return $this;
    }

    /**
     * Get Deactivation Time
     *
     * @return string
     */
    public function getDeactivationTime()
    {
        return $this->deactivation_time;
    }

    /**
     * Set Deactivation Time
     *
     * @param string $deactivation_time
     *
     * @return $this
     */
    public function setDeactivationTime($deactivation_time)
    {
        $this->deactivation_time = $deactivation_time;

        return $this;
    }

    /**
     * Get Limit
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set Limit
     *
     * @param integer $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get Skip
     *
     * @return integer
     */
    public function getSkip()
    {
        return $this->skip;
    }

    /**
     * Set Skip
     *
     * @param integer $skip
     *
     * @return $this
     */
    public function setSkip($skip)
    {
        $this->skip = $skip;

        return $this;
    }

    /**
     * Turn the object into array to get properties combined with their not null values
     */
    public function toArray()
    {
        $resp = [];

        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {

            $propertyName = $property->getName();

            if (!is_null($this->$propertyName)) {
                $resp[$propertyName] = $this->$propertyName;
            }
        }

        return $resp;
    }

    /**
     * Update skip to get more results
     *
     * @TODO Refactor to use the total_count from a result, but for now cheat.
     */
    public function next()
    {
        $this->setSkip($this->getSkip() + $this->getLimit());
    }
}
