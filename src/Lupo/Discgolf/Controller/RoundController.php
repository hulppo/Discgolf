<?php
namespace Lupo\Discgolf\Controller;

use FOS\RestBundle\View\View;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoundController extends Controller
{
    public function getRoundsAction()
    {
        $rounds = $this->get('round_manager')->getSortedRounds();
        $view = View::create();
        if ('html' === $this->getRequest()->getRequestFormat())
            $view->setData(array('rounds' => $rounds));
        else {
            $view->setData($rounds);
        }
        $view->setTemplate('LupoDiscgolf:Round:getRounds.html.php');
        $view->setSerializerGroups(array('list'));
        return $this->get('fos_rest.view_handler')->handle($view);
    }  // "get_rounds"    [GET] /rounds

    public function getRoundAction($roundId)
    {
        $round = $this->getRepository()->find($roundId);
        $view = View::create()->setData($round);
        $view->setSerializerGroups(array('details'));
        return $this->get('fos_rest.view_handler')->handle($view);
    }   // "get_rounds"    [GET] /rounds/{$roundId}

    /**
     * Returns a doctrine repository for the Round Entity.
     * @return Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->get('doctrine')->getEntityManager()
        ->getRepository('Lupo\Discgolf\Entity\Round');
    }
}

?>