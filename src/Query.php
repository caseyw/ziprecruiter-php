<?php

namespace ZipRecruiter;

class Query
{

    /**
     * Unique identifier for this subscriber
     *
     * @var string
     */
    protected $id;

    /**
     * Comment
     *
     * @var string
     */
    protected $email;

    /**
     * Comment
     *
     * @var string
     */
    protected $emailMd5;

    /**
     * Comment
     *
     * @var string
     */
    protected $name;

    /**
     * Comment
     *
     * @var string
     */
    protected $create_time;

    /**
     * Comment
     *
     * @var string
     */
    protected $search;

    /**
     * Comment
     *
     * @var string
     */
    protected $location;

    /**
     * Comment
     *
     * @var string
     */
    protected $ip_address;

    /**
     * Comment
     *
     * @var string
     */
    protected $status;

    /**
     * Comment
     *
     * @var string
     */
    protected $brand;

    /**
     * Comment
     *
     * @var integer
     */
    protected $limit = 100;

    /**
     * Comment
     *
     * @var integer
     */
    protected $skip = 0;

    /**
     * @TODO Comment this!
     *
     * @return string
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getEmailMd5()
    {
        return $this->emailMd5;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @TODO Comment this!
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
     * @TODO Comment this!
     *
     * @return integer
     */
    public function getSkip()
    {
        return $this->skip;
    }

    /**
     * @TODO Comment this!
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
}
