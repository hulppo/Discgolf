<?php
namespace Lupo\Discgolf\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FgolfStatsController extends Controller
{
    /**
     * Displays the javascripts stats stuff system.
     */
    public function indexAction()
    {
        // stupid way to do api root
        $apiRoot = $this->get('router')->generate('discgolf_players_get_players');
        // need to strip the players stuff
        $apiRoot = substr($apiRoot, 0, strpos($apiRoot, '/playe'));
        return $this->render('LupoDiscgolf:FgolfStats:index.html.php',
            array('api_root' => $apiRoot));
    }
}