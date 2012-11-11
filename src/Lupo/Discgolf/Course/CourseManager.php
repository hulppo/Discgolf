<?php
namespace Lupo\Discgolf\Course;

/**
 * This class makes it possible to search courses with different options.
 *
 */
use Lupo\Discgolf\Entity\Course;

use Lupo\Discgolf\Course\ParsedCourseInterface;

use Doctrine\ORM\EntityManager;

class CourseManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructs the course manager.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Returns array of Course objects having the given name.
     * @param string $name
     * @return array Array of Course objects.
     */
    public function searchCoursesWithName($name)
    {
        // check newest matches first
        $qb = $this->em->createQueryBuilder();
        $qb->select('c')->from('Lupo\Discgolf\Entity\Course', 'c');
        $qb->join('c.altNames', 'cn');
        $qb->where('UPPER(cn.altName) = :name'); // in case sensitive search
        $qb->orderBy('c.id', 'DESC');
        $qb->setParameter('name', mb_strtoupper($name));
        $courses = $qb->getQuery()->getResult();
        return $courses;
    }

    /**
     * Retrieves a course matching the given parsing result.
     * If no existing course is found, a new one is created.
     *
     * @param ParsedCourseInterface $parsedCourse
     * @return Lupo\Discgolf\Entity\Course Null is returned if we don't
     * want a new one created and we find no match.
     */
    public function getCourseForParsing(ParsedCourseInterface $parsedCourse,
        $createNew = true)
    {
        $ret = null;
        $courses = $this->searchCoursesWithName($parsedCourse->getCourseName());
        if (count($courses) > 0) {
            $parsedHoles = $parsedCourse->getHoles();
            foreach ($courses as $course) {
                // check that courses have the same amount of holes
                if (count($course->getHoles()) == count($parsedHoles)) {
                    $ret = $course; // possibly, still pars to check
                    if ($parsedCourse->hasParInformation()) {
                        /* @var Lupo\Discgolf\Entity\Hole $hole */
                        foreach ($course->getHoles() as $hole) {
                            if ($hole->getPar() != $parsedHoles[$hole->getNumber()]) {
                                $ret = null;
                                break; // non-matching par, course not the same
                            }
                        }
                    } // how should we get par information for a course that we don't have it for?
                }
            }
        }
        if ($ret == null && $createNew) {
            $course = new Course($parsedCourse->getCourseName());
            $courseName = $course->addAltName($parsedCourse->getCourseName());
            $this->em->persist($course);
            $this->em->persist($courseName);
            foreach ($parsedCourse->getHoles() as $holeNumber => $par) {
                $par = $par == '' ? 0 : $par;
                $hole = $course->addHole($holeNumber, $par);
                $this->em->persist($hole);
            }
            $this->em->flush(); // we store course and holes in db
            $ret = $course;
        }
        return $ret;
    }

    /**
     * Saves a course to the database.
     *
     * @param Lupo\Discgolf\Entity\Course $course
     */
    public function saveNewCourse(Entity\Course $course)
    {
       $this->em->persist($course);
       foreach ($course->getHoles() as $hole) {
           $this->em->persist($hole);
       }
       $this->em->flush();
    }

}
