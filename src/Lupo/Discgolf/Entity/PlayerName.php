<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\PlayerName
 *
 * @ORM\Table(name="player_name")
 * @ORM\Entity
 */
class PlayerName
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="player_name_id_seq", allocationSize="1", initialValue="1")
     */
    private $id;

    /**
     * @var text $altName
     *
     * @ORM\Column(name="alt_name", type="text", nullable=false)
     *
     * @Groups({"player_details"})
     */
    private $altName;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     */
    private $player;



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
     * Set altName
     *
     * @param text $altName
     */
    public function setAltName($altName)
    {
        $this->altName = $altName;
    }

    /**
     * Get altName
     *
     * @return text
     */
    public function getAltName()
    {
        return $this->altName;
    }

    /**
     * Set player
     *
     * @param Lupo\Discgolf\Entity\Player $player
     */
    public function setPlayer(\Lupo\Discgolf\Entity\Player $player)
    {
        $this->player = $player;
    }

    /**
     * Get player
     *
     * @return Lupo\Discgolf\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}