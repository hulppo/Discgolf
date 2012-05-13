<?php
namespace Lupo\Discgolf\User;

use Lupo\Discgolf\Entity\User;

use Doctrine\ORM\EntityManager;

class UserManager
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
     * Retrieves a User object for the given sender information.
     * @param string $sender
     * @param boolean $createNew
     */
    public function getUserForSender($sender, $createNew = true)
    {
        $ret = null;
        $name = null;
        if(preg_match('/(.*)\<(.*)\>/', $sender, $matches)) {
            $name = trim(str_replace('"', '', $matches[1]));
            $email = trim($matches[2]);
            $search = array('name' => $name, 'email' => $email);
        } else { // only an email address?
            $email = trim($sender);
            $name = trim($sender);
            $search = array('email' => $email);
        }
        $users = $this->em->getRepository('Lupo\Discgolf\Entity\User')
            ->findBy($search, array('id' => 'DESC'));
        if (count($users) > 0) {
            $ret = reset($users);
        }
        if ($ret === null && $createNew) { // create new user
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $this->em->persist($user);
            $this->em->flush();
        }
        return $ret;
    }
}

?>