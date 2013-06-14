<?php

namespace SpiffyConnect\Storage;

use Zend\Session\Container as SessionContainer;
use Zend\Session\ManagerInterface as SessionManager;

class SessionStorage implements StorageInterface
{
    /**
     * Default session namespace
     */
    const NAMESPACE_DEFAULT = 'Zend_Auth';

    /**
     * Default session object member name
     */
    const MEMBER_DEFAULT = 'storage';

    /**
     * Object to proxy $_SESSION storage
     *
     * @var SessionContainer
     */
    protected $session;

    /**
     * Session namespace
     *
     * @var mixed
     */
    protected $namespace = self::NAMESPACE_DEFAULT;

    /**
     * Session object member
     *
     * @var mixed
     */
    protected $member = self::MEMBER_DEFAULT;

    /**
     * @param  string $namespace
     * @param  string $member
     * @param  SessionManager $manager
     */
    public function __construct($namespace = null, $member = null, SessionManager $manager = null)
    {
        if ($namespace !== null) {
            $this->namespace = $namespace;
        }
        if ($member !== null) {
            $this->member = $member;
        }
        $this->session = new SessionContainer($this->namespace, $manager);
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !isset($this->session->{$this->member});
    }

    /**
     * {@inheritDoc}
     */
    public function read()
    {
        return $this->session->{$this->member};
    }

    /**
     * {@inheritDoc}
     */
    public function write($contents)
    {
        $this->session->{$this->member} = $contents;
    }

    /**
     * @return void
     */
    public function clear()
    {
        unset($this->session->{$this->member});
    }
}
