<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Groups;

/**
 * Lupo\Discgolf\Entity\CourseName
 *
 * @ORM\Table(name="course_name")
 * @ORM\Entity
 */
class CourseName
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="course_name_id_seq", allocationSize="1", initialValue="1")
     */
    private $id;

    /**
     * @var text $altName
     *
     * @ORM\Column(name="alt_name", type="text", nullable=false)
     *
     * @Groups({"details"})
     */
    private $altName;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
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