<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Exclude;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\Result
 *
 * @ORM\Table(name="result")
 * @ORM\Entity
 */
class Result
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="result_id_seq", allocationSize="1", initialValue="1")
     *
     * @Groups({"details"})
     */
    private $id;

    /**
     * @var integer $throws
     *
     * @ORM\Column(name="throws", type="integer", nullable=true)
     *
     * @Groups({"details"})
     */
    private $throws;

    /**
     * @var integer $putts
     *
     * @ORM\Column(name="putts", type="integer", nullable=true)
     *
     * @Groups({"details"})
     */
    private $putts;

    /**
     * @var integer $outOfBounds
     *
     * @ORM\Column(name="out_of_bounds", type="integer", nullable=true)
     *
     * @Groups({"details"})
     */
    private $outOfBounds;

    /**
     * @var Round
     *
     * @ORM\ManyToOne(targetEntity="Round")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="round_id", referencedColumnName="id")
     * })
     * @Exclude
     */
    private $round;

    /**
     * @var Hole
     *
     * @ORM\ManyToOne(targetEntity="Hole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hole_id", referencedColumnName="id")
     * })
     *
     * @Groups({"details"})
     */
    private $hole;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     *
     * @Groups({"details"})
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
     * Set throws
     *
     * @param integer $throws
     */
    public function setThrows($throws)
    {
        $this->throws = $throws;
    }

    /**
     * Get throws
     *
     * @return integer
     */
    public function getThrows()
    {
        return $this->throws;
    }

    /**
     * Set the amount of putts in this result.
     *
     * @param integer $putts
     */
    public function setPutts($putts)
    {
        $this->putts = $putts;
    }

    /**
     * Get putts
     *
     * @return integer
     */
    public function getPutts()
    {
        return $this->putts;
    }

    /**
     * Set amount of OB throws, i.e. out of bounds.
     *
     * @param integer $outOfBounds
     */
    public function setOutOfBounds($outOfBounds)
    {
        $this->outOfBounds = $outOfBounds;
    }

    /**
     * Get amount of out of bounds throws.
     *
     * @return integer
     */
    public function getOutOfBounds()
    {
        return $this->outOfBounds;
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
     * Set hole
     *
     * @param Lupo\Discgolf\Entity\Hole $hole
     */
    public function setHole(\Lupo\Discgolf\Entity\Hole $hole)
    {
        $this->hole = $hole;
    }

    /**
     * Get hole
     *
     * @return Lupo\Discgolf\Entity\Hole
     */
    public function getHole()
    {
        return $this->hole;
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