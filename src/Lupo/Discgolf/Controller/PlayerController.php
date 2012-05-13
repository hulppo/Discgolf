<?php
namespace Lupo\Discgolf\Controller;

use FOS\RestBundle\View\View;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerController extends Controller
{
    public function getPlayersAction()
    {
        $players = $this->getRepository()->findAll();

        $view = View::create();
        if ('html' === $this->getRequest()->getRequestFormat())
            $view->setData(array('players' => $players));
        else {
            $view->setData($players);
        }
        $view->setSerializerGroups(array('list'));
        $view->setTemplate('LupoDiscgolf:Player:getPlayers.html.php');
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_players"    [GET] /players


    public function getPlayerAction($playerId)
    {
        $player = $this->getRepository()->find($playerId);
        $view = View::create()->setData($player);
        $view->setSerializerGroups(array('player_details'));
        return $this->get('fos_rest.view_handler')->handle($view);
    } // "get_user"     [GET] /players/{playerId}

    /**
     * Returns a doctrine repository for the Player Entity.
     * @return Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->get('doctrine')->getEntityManager()
            ->getRepository('Lupo\Discgolf\Entity\Player');
    }
}

?>