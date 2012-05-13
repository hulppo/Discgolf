<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity
 */
class Player
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="player_id_seq", allocationSize="1", initialValue="1")
     *
     * @Groups({"list", "details", "player_details"})
     */
    private $id;

    /**
     * @var text $name
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     *
     * @Groups({"list", "details", "player_details"})
     */
    private $name;

    /**
     * @var ArrayCollection $altNames
     * @ORM\OneToMany(targetEntity="PlayerName", mappedBy="player")
     *
     * @Groups({"player_details"})
     */
    private $altNames;

    public function __construct($playerName)
    {
        $this->name = $playerName;
        $this->altNames = new ArrayCollection();
    }

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

    /**
     * Creates a new alternative player name for this player.
     * @param string $name
     * @return \Lupo\Discgolf\Entity\PlayerName
     */
    public function addAltName($name)
    {
        $altName = new PlayerName();
        $altName->setPlayer($this);
        $altName->setAltName($name);
        return $altName;
    }
}