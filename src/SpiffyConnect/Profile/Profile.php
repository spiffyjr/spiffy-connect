<?php

namespace SpiffyConnect\Profile;

use Zend\Http\Response;
use Zend\Stdlib\AbstractOptions;

class Profile extends AbstractOptions implements ProfileInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var Response
     */
    protected $rawResponse;

    /**
     * @param int $id
     * @return Profile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $displayName
     * @return Profile
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $email
     * @return Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param Response $rawResponse
     * @return Profile
     */
    public function setRawResponse(Response $rawResponse)
    {
        $this->rawResponse = $rawResponse;
        return $this;
    }

    /**
     * @return Response
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }
}