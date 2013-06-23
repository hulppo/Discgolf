<?php
namespace Lupo\Discgolf\Round;

use Lupo\Discgolf\Entity\RoundParticipant;

use Lupo\Discgolf\Entity\User;

use Lupo\Discgolf\Entity\Result;

use Lupo\Discgolf\Entity\Round;

use Doctrine\ORM\EntityManager;
use Lupo\Discgolf\Player\PlayerManager;
use Lupo\Discgolf\Entity\Course;

class RoundManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    private $playerManager;

    /**
     * Constructs the course manager.
     * @param EntityManager $em
     * @param Lupo\Player\PlayerManager $playerManager
     */
    public function __construct(EntityManager $em, PlayerManager $playerManager)
    {
        $this->em = $em;
        $this->playerManager = $playerManager;
    }

    /**
     * Creates a new round on the given course.
     * @param Lupo\Discgolf\Entity\Course $course
     * @param ParsedRoundInterface $parsedRound
     * @return boolean Whether round creation succeeded or failed.
     */
    public function createNewRound(Course $course, ParsedRoundInterface $parsedRound,
       User $user = null)
    {
        $hash = $this->calculateHash($parsedRound);
        // check whether hash already exists
        $roundRepository = $this->em->getRepository('Lupo\Discgolf\Entity\Round');
        $sameRounds = $roundRepository->findBy(array('hash' => $hash));
        if (count($sameRounds) > 0) { // existing round
            return false;
        }

        if ($user === null) {
            $user = $this->em->getRepository('Lupo\Discgolf\Entity\User')
                ->find(1);
        }

        $round = new Round();
        if (null === $parsedRound->getRoundStart()) {
            $round->setTimestamp(new \DateTime()); // default to current time if no time set
        } else {
            $round->setTimestamp(new \DateTime($parsedRound->getRoundStart()));
        }
        $descr = '';
        foreach ($parsedRound->getRoundInformation() as $key => $info) {
            $descr .= "$key $info\n";
        }
        $round->setDescription($descr);
        $round->setReporter($user);
        $round->setCourse($course);
        $round->setHash($hash);
        $this->em->persist($round); // does this already throw the uniqueness error?

        $players = array();
        foreach ($parsedRound->getPlayers() as $playerName) {
            $player = $this->playerManager->getPlayer($playerName);
            $players[$playerName] = $player;
            $roundParticipant = new RoundParticipant();
            $roundParticipant->setPlayer($players[$playerName]);
            $roundParticipant->setRound($round);
            $this->em->persist($roundParticipant);
        }
        $holes = array();
        foreach ($course->getHoles() as $hole) {
            $holes[$hole->getNumber()] = $hole;
        }
        $putts = $parsedRound->getPutts();
        $penalties = $parsedRound->getPenalty();

        foreach ($parsedRound->getScores() as $playerName => $scores) {
        	$playerPutts = isset($putts[$playerName]) ? $putts[$playerName]
        		: array();
        	$playerPenalty = isset($penalties[$playerName])
        		? $penalties[$playerName] : array();
            foreach ($scores as $holeNumber => $throws) {
                if ($throws != '') {
                    $result = new Result();
                    $result->setRound($round);
                    $result->setPlayer($players[$playerName]);
                    $result->setHole($holes[$holeNumber]);
                    $result->setThrows($throws);
                    if (isset($playerPutts[$holeNumber])
                        && $playerPutts[$holeNumber] != ''
                    ) {
                    	$result->setPutts($playerPutts[$holeNumber]);
                    }
                    if (isset($playerPenalty[$holeNumber])
                        && $playerPenalty[$holeNumber] != ''
                    ) {
                    	$result->setOutOfBounds($playerPenalty[$holeNumber]);
                    }
                    $this->em->persist($result);
                }
            }
        }
        $this->em->flush();
        return true;
    }

    /**
     * Returns the rounds as a sorted array.
     * @return array Array of Round objects.
     */
    public function getSortedRounds()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('r')->from('Lupo\Discgolf\Entity\Round', 'r');
        $qb->orderBy('r.timestamp');
        $rounds = $qb->getQuery()->getResult();
        return $rounds;
    }

    /**
     * Returns the unique hash for the given round.
     * @param ParsedRoundInterface $round
     * @return string
     */
    private function calculateHash(ParsedRoundInterface $round)
    {
        $hash = $round->getRoundStart() . implode('', $round->getPlayers());
        $hash = md5($hash);
        return $hash;
    }
}

?>