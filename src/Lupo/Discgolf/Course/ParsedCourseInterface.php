<?php
namespace Lupo\Discgolf\Course;

/**
 * An interface used for identifying unique courses
 * from parsed data.
 *
 * @author tommy
 *
 */
interface ParsedCourseInterface
{
    /**
     * Returns the name of the course.
     * @return string
     */
    public function getCourseName();

    /**
     * Returns information about the holes on the course.
     * @return array Array with hole number indexing possible
     * par.
     */
    public function getHoles();

    /**
     * Tells whether we have par information about the course.
     * @return boolean
     */
    public function hasParInformation();
}

?>