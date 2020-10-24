<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Comment
{
    /**
     * @var string
     *
     * @Assert\Length(min=5, minMessage = "Nick must be at min 5 characters long.")
     */
    protected $nick;

    /**
     * @var string
     *
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="The message cannot be empty.")
     */
    protected $message;

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

}
