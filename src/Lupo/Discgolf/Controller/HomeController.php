<?php
namespace Lupo\Discgolf\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('LupoDiscgolf:Home:index.html.php');
    }

}

?>