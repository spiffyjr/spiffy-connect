<?php

namespace SpiffyConnect\Profile;

use Zend\Http\Response;

interface ProfileInterface
{
    /**
     * @param int $id
     * @return Profile
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $displayName
     * @return Profile
     */
    public function setDisplayName($displayName);

    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @param string $email
     * @return Profile
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param Response $rawResponse
     * @return Profile
     */
    public function setRawResponse(Response $rawResponse);

    /**
     * @return \Zend\Http\Response
     */
    public function getRawResponse();
}