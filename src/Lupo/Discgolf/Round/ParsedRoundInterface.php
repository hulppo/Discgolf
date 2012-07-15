<?php
namespace Lupo\Discgolf\Round;

interface ParsedRoundInterface
{
    /**
     * Returns the start time of the round as SQL timestamp.
     * @return string
     */
    public function getRoundStart();

    /**
     * Returns an array with player names.
     * @return array Array of player names.
     */
    public function getPlayers();

    /**
     * Returns an array with player name indexing an score
     * array with hole number indexing amount of throws.
     * @return array
     */
    public function getScores();

    /**
     * Returns an array with round information.
     * @return array
     */
    public function getRoundInformation();

    /**
     * Returns putt information for this round.
     *
     * @return array Player name indexes a result array with all holes
     * we have putt information for.
     */
    public function getPutts();

    /**
     * Returns penalty information for this round.
     *
     * @return array Array with player name indexing an array with hole
     * number indexing penalty amount.
     */
    public function getPenalty();
}

?>