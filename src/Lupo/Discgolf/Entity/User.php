<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Exclude;
use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\DgUser
 *
 * @ORM\Table(name="dg_user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="dg_user_id_seq", allocationSize="1", initialValue="1")
     *
     * @Exclude
     */
    private $id;

    /**
     * @var text $email
     *
     * @ORM\Column(name="email", type="text", nullable=false)
     * @Exclude
     */
    private $email;

    /**
     * @var text $name
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     *
     * @Groups({"list", "details"})
     */
    private $name;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param text $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return text
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param text $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return text
     */
    public function getName()
    {
        return $this->name;
    }
}