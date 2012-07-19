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
        return $this->render('LupoDiscgolf:FgolfStats:index.html.php');
    }
}