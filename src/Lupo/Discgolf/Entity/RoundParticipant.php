<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\RoundParticipant
 *
 * @ORM\Table(name="round_participant")
 * @ORM\Entity
 */
class RoundParticipant
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="round_participant_id_seq", allocationSize="1", initialValue="1")
     */
    private $id;

    /**
     * @var Round
     *
     * @ORM\ManyToOne(targetEntity="Round")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="round_id", referencedColumnName="id")
     * })
     *
     * @Groups({"list", "details"})
     */
    private $round;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     *
     * @Groups({"list", "details"})
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
     * Set round
     *
     * @param Lupo\Discgolf\Entity\Round $round
     */
    public function setRound(\Lupo\Discgolf\Entity\Round $round)
    {
        $this->round = $round;
    }

    /**
     * Get round
     *
     * @return Lupo\Discgolf\Entity\Round
     */
    public function getRound()
    {
        return $this->round;
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