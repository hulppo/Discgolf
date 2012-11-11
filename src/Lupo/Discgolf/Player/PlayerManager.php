<?php
namespace Lupo\Discgolf\Player;

use Lupo\Discgolf\Entity\PlayerName;

use Lupo\Discgolf\Entity\Player;

use Doctrine\ORM\EntityManager;

/**
 * Class for managering discgolf players.
 * @author tommy
 *
 */
class PlayerManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Returns array of Player objects having the given name.
     * @param string $name
     * @return array Array of Player objects.
     */
    public function searchPlayersWithName($name)
    {
        // check newest matches first
        $qb = $this->em->createQueryBuilder();
        $qb->select('p')->from('Lupo\Discgolf\Entity\Player', 'p');
        $qb->join('p.altNames', 'pn');
        $qb->where('UPPER(pn.altName) = :name'); // in case sensitive name search
        $qb->orderBy('p.id', 'DESC');
        $qb->setParameter('name', mb_strtoupper($name));
        $players = $qb->getQuery()->getResult();
        return $players;
    }

    /**
     * Returns a Player matching the given name.
     * @param string $playerName
     * @param boolean $createNew Whether to create new one if none found.
     * @return Lupo\Discgolf\Entity\Player
     */
    public function getPlayer($playerName, $createNew = true)
    {
        $ret = null;
        $players = $this->searchPlayersWithName($playerName);
        if (count($players) > 0) {
            $ret = reset($players); // take the first one
        }
        if ($ret == null && $createNew) {
            $ret = new Player($playerName);
	    $playerName = $ret->addAltName($playerName);
            $this->em->persist($ret);
	    $this->em->persist($playerName);
            $this->em->flush(); // when should this really be done?
        }
        return $ret;

    }

}

?>