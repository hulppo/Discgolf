<?php
namespace Lupo\Discgolf\Controller;

use FOS\RestBundle\View\View;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResultController extends Controller
{
    /**
     * Retrieves results.
     */
    public function getResultsAction()
    {
        $results = $this->getRepository()->findAll();

        $view = View::create();
        if ('html' === $this->getRequest()->getRequestFormat())
            $view->setData(array('results' => $results));
        else {
            $view->setData($results);
        }
        $view->setTemplate('LupoDiscgolf:Result:getResults.html.php');
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_results" [GET] /results

    public function getResultAction($resultId)
    {
        $result = $this->getRepository()->find($resultId);
        $view = View::create()->setData($result);
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_result" [GET] /results/{$resultId}

    /**
     * Returns a doctrine repository for the Round Entity.
     * @return Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->get('doctrine')->getEntityManager()
        ->getRepository('Lupo\Discgolf\Entity\Result');
    }
}

?>