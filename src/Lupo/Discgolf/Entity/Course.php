<?php

namespace Lupo\Discgolf\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use JMS\SerializerBundle\Annotation\Groups;
/**
 * Lupo\Discgolf\Entity\Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity
 */
class Course
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="course_id_seq", allocationSize="1", initialValue="1")
     *
     * @Groups({"list", "details"})
     */
    private $id;

    /**
     * @var text $name
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     *
     * @Groups({"list", "details"})
     */
    private $name;

    /**
     * @var text $location
     *
     * @ORM\Column(name="location", type="text", nullable=true)
     *
     * @Groups({"details"})
     */
    private $location;

    /**
     * @var ArrayCollection holes
     * @ORM\OneToMany(targetEntity="Hole", mappedBy="course")
     *
     * @Groups({"details"})
     */
    private $holes;

    /**
     * @var ArrayCollection $altNames
     * @ORM\OneToMany(targetEntity="CourseName", mappedBy="course")
     *
     * @Groups({"details"})
     */
    private $altNames;

    /**
     * Constructs a new course.
     * @param string $courseName
     */
    public function __construct($courseName)
    {
        $this->name = $courseName;
        $this->holes = new ArrayCollection();
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
     * Set location
     *
     * @param text $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return text
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Returns the holes of this course.
     * @return ArrayCollection
     */
     public function getHoles()
     {
         return $this->holes;
     }

     /**
      * Adds a hole to the course with the given arguments.
      *
      * @param integer $number
      * @param integer $par
      * @param integer $length Length in meters
      * @param string $description
      * @return \Lupo\Discgolf\Entity\Hole
      */
     public function addHole($number, $par = 0, $length = 0, $description = '')
     {
         $hole = new Hole();
         $hole->setCourse($this);
         $hole->setNumber($number);
         if ($par != 0) {
             $hole->setPar($par);
         }
         if ($length != 0) {
             $hole->setLength($length);
         }
         if ($description != '') {
             $hole->setDescription($description);
         }
         $this->holes[] = $hole;
         return $hole;
     }

     /**
      * Creates a new alternative course name for this course.
      * @param string $name
      * @return \Lupo\Discgolf\Entity\CourseName
      */
     public function addAltName($name)
     {
     	$altName = new CourseName();
     	$altName->setCourse($this);
     	$altName->setAltName($name);
     	return $altName;
     }
}