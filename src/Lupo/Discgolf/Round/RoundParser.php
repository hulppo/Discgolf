<?php
namespace Lupo\Discgolf\Round;

/**
 * Parses round data into structured data.
 * @author lupo
 */
use Lupo\Discgolf\Course\ParsedCourseInterface;

class RoundParser implements ParsedCourseInterface,
    ParsedRoundInterface
{
    /**
     * Name of the course.
     * @var string
     */
    private $courseName;

    /**
     * Array with information key indexing information.
     * @var array
     */
    private $courseInfo = array();

    /**
     * SQL timestamp for round start.
     * @var string
     */
    private $timestamp;

    /**
     * Players on the round.
     * @var array
     */
    private $players = array();

    /**
     * Player scores.
     * @var array Player name indexes an array with hole number
     * indexing the throw amount.
     */
    private $scores = array();

    /**
     * Array with hole number indexing par, if available.
     * @var array
     */
    private $holes = array();

    /**
     * Array with player name indexing array of putt amounts per hole.
     * @var array
     */
    private $putts = array();

    /**
     * Array with player name indexing array of penalty amount per hole.
     * @var array
     */
    private $penalty = array();

    /**
     * CSV data string.
     * @var string
     */
    private $csvData;

    /**
     * Html string.
     * @var string
     */
    private $htmlData;

    /**
     * Charset encoding of the data.
     * @var string
     */
    private $charset;

    /**
     * We know some pars for the holes on the course.
     * @var boolean
     */
    private $knownPar = false;

    /**
     * Set the CSV data to be parsed for round information.
     * @param string $data
     */
    public function setCsvData($data, $charset = '')
    {
        if ($charset != '') { // convert the data
            if ($charset == 'US-ASCII') {
                $charset = 'ISO-8859-1';
            }
            $this->csvData = iconv($charset, 'utf-8', $data);
            $this->charset = $charset;
        } else {
            $this->csvData = $data;
        }
    }

    /**
     * Set the HTML data to be parsed for round information.
     * @param string $data
     */
    public function setHtmlData($data, $charset = '')
    {
        if ($charset != '') { // should we convert data here?
            if ($charset == 'US-ASCII') {
                $charset = 'ISO-8859-1';
            }
            $this->charset = $charset;
        }
        $this->htmlData = $data;
    }

    /**
     * Parses the given data.
     * @return boolean Whether parsing succeeded or not.
     */
    public function parse()
    {
        $ok = false;
        if ($this->csvData != null) {
            $ok = $this->parseCsvData();
        } elseif ($this->htmlData != null) {
            $ok = $this->parseHtmlData();
        }
        return $ok;
    }

    /**
     * Parses the CSV data. Returns boolean if at least one player score row
     * found.
     * @return boolean
     */
    private function parseCsvData()
    {
        $ok = false;
        $dd_array = array(); // two dimensional array for player scores
        $rows = explode("\n", $this->csvData);
        foreach ($rows as $row) {
            $dd_array[] = str_getcsv($row, ',');
        }
        $i = 0;
        $holeMap = array();
        $scores = false;
        $playerScores = array();
        foreach ($dd_array as $row) {
            if (count($row) <= 1) {
                continue;
            }
            if ($i == 0) {
                $this->courseName = $row[0];
                $this->timestamp = date('Y-m-d H:i:s', strtotime($row[1]));
            } elseif ($row[0] == 'Player') {
                $scores = true;
                foreach ($row as $columnNumber => $key) {
                    if (strpos($key, 'H') === 0 && (int) substr($key, 1) > 0) {
                        $hole = (int) substr($key, 1);
                        $holeMap[$columnNumber] = $hole;
                        $this->holes[$hole] = ''; // this would be par if we had it
                    }
                }
            } elseif ($scores) { // we are on one player score row
                $scoreArr = array();
                foreach ($holeMap as $columnNumber => $holeNumber) {
                    $scoreArr[$holeNumber] = $row[$columnNumber];
                }
                $this->players[] = $row[0];
                $this->scores[$row[0]] = $scoreArr;
                $ok = true;
            }
            $i++;
        }
        return $ok;
    }

    /**
     * Parses course and round information from the given html data.
     * Structure seems to be that the course information is in a table
     * and then we have an inner table with the holes and scores.
     * @return boolean Whether parsing succeeded.
     */
    private function parseHtmlData()
    {
        $ok = false;
        $data = $this->htmlData;
        // if we cannot find scorecard title we bail
        if (strpos($data, 'Scorecard') === false) {
            return false;
        }
        // find first table tag
        $outerTableStart = strpos($data, '<table');
        $data = substr($data, $outerTableStart);

        // find position of next table tag
        $innerTableStart = strpos($data, '<table', 6); // skip the outer table tag
        $courseInfo = substr($data, 0, $innerTableStart);
        $courseInfo = strip_tags($courseInfo, '<br/><br>'); // leave br for exploding
        $courseInfo = str_replace('<br/>', '<br>', $courseInfo); // standardize possible breaks
        $courseInfoArr = explode('<br>',$courseInfo);
        foreach ($courseInfoArr as $row) {
            $info = explode("&nbsp;", $row);
            if (strpos($info[0], 'Course') !== false) {
                $this->courseName = $this->decodeAndToUtf8($info[1]);
            } elseif (strpos($info[0], 'Start Time') !== false) {
                $playTime = strtotime(str_replace(array(',', '/'), array('', '-'), $info[1]));
                if (false === $playTime) { // no proper time found, default to current time 
                    $playTime = time();
                }
                $this->timestamp = date('Y-m-d H:i:s', $playTime);
            } else {
                $this->courseInfo[$info[0]] = $this->decodeAndToUtf8($info[1]);
            }
        }
        $innerTableEnd = strpos($data, '</table>');
        // find inner table end
        $data = substr($data, $innerTableStart, $innerTableEnd);
        // get the holes
        $rows = explode('</tr>', $data);
        $holeMap = array(); // map with column number indexing hole number
        $prevHeader = '';
        foreach ($rows as $rowStr) {
            $separator = strpos($rowStr, '<th') !== false ? 'th' : 'td';
            $cleanRow = strip_tags($rowStr, "<$separator>");
            $columns = explode("</$separator>", $cleanRow);
            $columns = array_map('strip_tags', $columns);
            if (strpos($columns[0], 'HOLE') !== false) {
                $prevHeader = 'HOLE';
                foreach ($columns as $columnNumber => $holeNumber) {
                    if (is_numeric($holeNumber)) {
                        $holeMap[$columnNumber] = $holeNumber;
                        $this->holes[$holeNumber] = '';
                    }
                }
            } elseif (strpos($columns[0], 'PAR') !== false) {
                $prevHeader = 'PAR';
                foreach ($holeMap as $columnNumber => $holeNumber) {
                    $this->holes[$holeNumber] = $columns[$columnNumber];
                }
                $this->knownPar = true;
            } elseif (strpos($columns[0], 'PUTT') !== false) {
            	$prevHeader = 'PUTT';
            } elseif (strpos($columns[0], 'PENALTY') !== false) {
            	$prevHeader = 'PENALTY';
            } elseif ($separator == 'td'
                && in_array($prevHeader, array('HOLE', 'PAR'))
            ) { // player score row follows HOLE or PAR header row
                if (count($columns) < count($this->holes)) {
                    continue; // we skip the row if we don't have enough columns
                }
                $player = trim($this->decodeAndToUtf8($columns[0]));
                $this->players[] = $player;
                $scores = array();
                foreach ($holeMap as $columnNumber => $holeNumber) {
                    $scores[$holeNumber] = $columns[$columnNumber];
                }
                $this->scores[$player] = $scores;
                $ok = true; // found some player scores
            } elseif ($separator == 'td' && $prevHeader == 'PUTT') {
            	if (count($columns) < count($this->holes)) {
            		continue; // we skip the row if we don't have enough columns
            	}
            	$player = trim($this->decodeAndToUtf8($columns[0]));
            	$putts = array();
            	foreach ($holeMap as $columnNumber => $holeNumber) {
                    $putts[$holeNumber] = $columns[$columnNumber];
                }
                $this->putts[$player] = $putts;
            } elseif ($separator == 'td' && $prevHeader == 'PENALTY') {
            	if (count($columns) < count($this->holes)) {
            		continue; // we skip the row if we don't have enough columns
            	}
            	$player = trim($this->decodeAndToUtf8($columns[0]));
            	$penalties = array();
            	foreach ($holeMap as $columnNumber => $holeNumber) {
                    $penalties[$holeNumber] = $columns[$columnNumber];
                }
                $this->penalty[$player] = $penalties;
            } else {
                $prevHeader = $columns[0];
            }
        }
        return $ok;
    }

    /**
     * Html entity decodes and converts given value to utf-8.
     * Charset variable in this instance is used.
     * @param string $value
     * @return string UTF-8 string.
     */
    private function decodeAndToUtf8($value)
    {
        if ($this->charset != '' && strtolower($this->charset) != 'utf-8') {
            $value = iconv($this->charset, 'utf-8', $value);
        }
        $value = html_entity_decode($value, null, 'utf-8');
        return $value;
    }

    /**
     * Returns the names of the players as an array.
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Returns the name of the course.
     * @return string
     */
    public function getCourseName()
    {
        return $this->courseName;
    }

    /**
     * Returns the round start time as SQL timestamp.
     * @return string
     */
    public function getRoundStart()
    {
        return $this->timestamp;
    }

    /**
     * Returns the scores of the players, where a player name
     * indexes the following array with following indexes:
     * - HX Hole result
     * - 'Player' player name
     * - HCP handicap
     * -
     * @return array
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Returns the holes as keys in the array. If we know the pars of the
     * holes then they are as values in the array.
     * @return array
     */
    public function getHoles()
    {
        return $this->holes;
    }

    /**
     * Returns putt information for this round.
     *
     * @return array Player name indexes a result array with all holes
     * we have putt information for.
     */
    public function getPutts()
    {
    	return $this->putts;
    }

    /**
     * Returns penalty information for this round.
     *
     * @return array Array with player name indexing an array with hole
     * number indexing penalty amount.
     */
    public function getPenalty()
    {
    	return $this->penalty;
    }

    /**
     * Returns round information.
     * @return array
     */
    public function getRoundInformation()
    {
        return $this->courseInfo;
    }

    /**
     * @see Lupo\Discgolf\Course.ParsedCourseInterface::hasParInformation()
     */
    public function hasParInformation()
    {
        return $this->knownPar;
    }
}
