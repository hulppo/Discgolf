<?php
namespace Lupo\Discgolf\Controller;

use FOS\RestBundle\View\View;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseController extends Controller
{
    /**
     * Retrieves the courses available.
     */
    public function getCoursesAction()
    {
        $courses = $this->getRepository()->findAll();

        $view = View::create();
        if ('html' === $this->getRequest()->getRequestFormat())
            $view->setData(array('courses' => $courses));
        else {
            $view->setData($courses);
        }
        $view->setSerializerGroups(array('list'));
        $view->setTemplate('LupoDiscgolf:Course:getCourses.html.php');
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_courses" [GET] /courses

    public function getCourseAction($courseId)
    {
        $course = $this->getRepository()->find($courseId);
        $view = View::create()->setData($course);
        $view->setSerializerGroups(array('details'));
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_course" [GET] /courses/{$courseId}

    /**
     * Returns a doctrine repository for the Round Entity.
     * @return Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->get('doctrine')->getEntityManager()
        ->getRepository('Lupo\Discgolf\Entity\Course');
    }
}

?>