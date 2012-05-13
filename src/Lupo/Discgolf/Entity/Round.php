<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Exclude;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\Round
 *
 * @ORM\Table(name="round")
 * @ORM\Entity
 */
class Round
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="round_id_seq", allocationSize="1", initialValue="1")
     *
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @var datetime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     *
     * @Groups({"list", "details"})
     */
    private $timestamp;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @Groups({"details"})
     */
    private $description;

    /**
     * @var DgUser
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     * })
     *
     * @Groups({"list", "details"})
     */
    private $reporter;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     *
     * @Groups({"list", "details"})
     */
    private $course;

    /**
     * @var text $hash
     *
     * @ORM\Column(name="hash", type="text", unique=true)
     * @Exclude
     */
    private $hash;

    /**
     * @var ArrayCollection $results
     * @ORM\OneToMany(targetEntity="Result", mappedBy="round")
     *
     * @Groups({"details"})
     */
    private $results;

    /**
     * @var ArrayCollection $participants
     * @ORM\OneToMany(targetEntity="RoundParticipant", mappedBy="round")
     *
     * @Groups({"list", "details"})
     */
    private $participants;

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
     * Set timestamp
     *
     * @param datetime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get timestamp
     *
     * @return datetime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set reporter
     *
     * @param Lupo\Discgolf\Entity\User $reporter
     */
    public function setReporter(\Lupo\Discgolf\Entity\User $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * Get reporter
     *
     * @return Lupo\Discgolf\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set course
     *
     * @param Lupo\Discgolf\Entity\Course $course
     */
    public function setCourse(\Lupo\Discgolf\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return Lupo\Discgolf\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Sets the hash of this round. This is used to ensure round uniqueness.
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Retrieves the hash of the round.
     * @return text
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Returns the results of the round.
     * @return ArrayCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Returns the participants of this round.
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}