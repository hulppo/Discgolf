<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\Exclude;
use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\Hole
 *
 * @ORM\Table(name="hole")
 * @ORM\Entity
 */
class Hole
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="hole_id_seq", allocationSize="1", initialValue="1")
     *
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @var integer $number
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     *
     * @Groups({"list", "details"})
     */
    private $number;

    /**
     * @var integer $par
     *
     * @ORM\Column(name="par", type="integer", nullable=true)
     *
     * @Groups({"details"})
     */
    private $par;

    /**
     * @var integer $length
     *
     * @ORM\Column(name="length", type="integer", nullable=true)
     *
     * @Groups({"details"})
     */
    private $length;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @Groups({"details"})
     */
    private $description;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="holes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     * @Exclude
     */
    private $course;



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
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set par
     *
     * @param integer $par
     */
    public function setPar($par)
    {
        $this->par = $par;
    }

    /**
     * Get par
     *
     * @return integer
     */
    public function getPar()
    {
        return $this->par;
    }

    /**
     * Set length
     *
     * @param integer $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
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
}