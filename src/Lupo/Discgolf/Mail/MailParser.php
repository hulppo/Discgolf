<?php
namespace Lupo\Discgolf\Mail;

/**
 * Parser for extacting stuff from the mails sent to the disc golf application.
 *
 * @author tommy
 *
 */
class MailParser
{
	/**
	 * String with all the mail data.
	 * @var string
	 */
	private $mailData;

	/**
	 * Mail mime decoder.
	 * @var Mail_mimeDecode
	 */
	private $mimeDecoder;

	/**
	 * Array with Discgolf\Round\Mail objects, i.e. recognized round data from
	 * mail.
	 * @var array
	 */
	private $mailRounds = array();

	/**
	 * Sets the mail string to be parsed.
	 * @param string $mailData
	 */
	public function setMail($mailData)
	{
		$this->mailData = $mailData;
	}

	/**
	 * Sets the mime decoder to be used.
	 * @param object $decoder PEAR Mail_mimeDecode object.
	 */
	public function setMimeDecoder($decoder)
	{
		$this->mimeDecoder = $decoder;
	}

	/**
	 * Parses the set mail. Returns amount of found rounds in
	 * the mail.
	 * @return int Amount of rounds found in mail.
	 */
	public function parse()
	{
		$params = array();
		$params['include_bodies'] = true;
		$params['decode_bodies']  = true;
		$params['decode_headers'] = true;
		$params['input'] = $this->mailData;
		// object returned with following variables:
		// headers - array of associative array with header indexing value
		// ctype_primary
		// ctype_secondary
		// ctype_parameters
		// disposition
		// d_parameters
		// body - possible body, mime mails have all in parts
		// parts - array of the same object as this
		$decodedMail = $this->mimeDecoder->decode($params);
		// we need to do recursive parsing to support forwarding of mails
		$mailInfo = $this->extractMailInfo($decodedMail);
		$this->roundParse($decodedMail, $mailInfo);
		$numOfFoundRows = count($this->mailRounds);
		return $numOfFoundRows;
	}

	/**
	 * Returns an array of
	 * @return array Array of Mail objects.
	 */
	public function getRounds()
	{
		return $this->mailRounds;
	}


	/**
	 * Parses possible mail rounds from the mail mime object.
	 * Recursively checks the parts for forwarded round mails and such.
	 * @param object $mimeObject
	 * @param array $mailInfo
	 * @return boolean Whether a round was found in the highest level
	 * of the mime object.
	 */
	private function roundParse($mimeObject, $mailInfo, $prevCharset = '')
	{
	    $roundFound = false;
		$roundType = $this->getRoundDataType($mimeObject);
		if ($roundType != '') {
			$round = new Round();
			$round->setSender($mailInfo['from']);
			$round->setData($roundType, $mimeObject->body);
			$round->setMailSent($mailInfo['timestamp']);
			foreach ($mailInfo['receivers'] as $receiver) {
				$round->addReceiver($receiver);
			}
			if ($prevCharset != '') {
			    $round->setCharset($prevCharset);
			} else {
			    $round->setCharset($this->getCharset($mimeObject));
			}
			$this->mailRounds[] = $round;
			$roundFound = true;
		} else { // recursively check for more rounds
			if (isset($mimeObject->parts)) {
			    $thisCharset = $this->getCharset($mimeObject);
			    $charset = $thisCharset != '' ? $thisCharset : $prevCharset;
				// check if we have new mailInfo
				foreach ($mimeObject->parts as $innerMimeObject) {
				    // We should check these also for charsets, because they affect the following attachment
					// override possible mail info from new part
					$newMailInfo = array_merge($mailInfo,
							$this->extractMailInfo($innerMimeObject));
					// If we didn't find a round, let's use the found charset for subsequent parts
					if(! $this->roundParse($innerMimeObject, $newMailInfo, $charset)) {
					    $thisCharset = $this->getCharset($innerMimeObject);
					    $charset = $thisCharset != '' ? $thisCharset : $charset;
					}
				}
			}
		}
		return $roundFound;
	}

	/**
	 * Grabs the charset from given mime object.
	 * @param object $mimeObject
	 * @return string
	 */
	private function getCharset($mimeObject)
	{
	    $charset = '';
	    if (isset($mimeObject->ctype_parameters)) {
	        if (isset($mimeObject->ctype_parameters['charset'])) {
	            $charset = $mimeObject->ctype_parameters['charset'];
	        }
	    }
	    return $charset;
	}

	/**
	 * Returns an array with information about the mail in the mime object.
	 * @param object $mimeObject
	 * @return array
	 */
	private function extractMailInfo($mimeObject)
	{
		$mailInfo = array();
		if (isset($mimeObject->headers)) {
			foreach ($mimeObject->headers as $header => $value) {
				if ($header == 'from') {
					$mailInfo['from'] = $value;
				} elseif ($header == 'to') {
					$mailInfo['receivers'] = explode(',', $value);
				} elseif ($header == 'date') {
					$mailInfo['timestamp'] = date('Y-m-d H:i:s',
							strtotime($value));
				}
			}
		}
		return $mailInfo;
	}

	/**
	 * Checks whether the given mime object is round data. Returns in this
	 * case also the type of the data.
	 * @param object $mimeObject
	 * @return string Type of data. Empty string means non round data.
	 */
	private function getRoundDataType($mimeObject)
	{
		$type = '';
		if (isset($mimeObject->d_parameters)
				&& is_array($mimeObject->d_parameters)
				&& isset($mimeObject->d_parameters['filename'])
		) {
			$filename = $mimeObject->d_parameters['filename'];
			if (strpos($filename, 'scorecard') !== false) {
				$dotPos = strrpos($filename, '.');
				$type = substr($filename, $dotPos + 1);
				if (! in_array($type, array('html', 'csv'))) {
					$type = '';
				}
			}
		}
		return $type;
	}
}

