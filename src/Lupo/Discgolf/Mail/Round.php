<?php
namespace Lupo\Discgolf\Mail;
/**
 * A round mail representation. Contains the sender of the
 * mail and possibly receivers. Also contains the type of
 * the round data in the mail: csv or html.
 * Contains the round data in a variable.
 * @author tommy
 *
 */
class Round
{
	private $sender;

	private $receivers = array();

	private $dataType;

	private $data;

	private $mailSent;

	private $charset;

	/**
	 * Set the sender of the mail.
	 * @param string $sender
	 */
	public function setSender($sender)
	{
		$this->sender = $sender;
	}

	/**
	 * Retrieve sender of the mail.
	 * @return string
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * Add a receiver of the mail.
	 * @param string $receiver
	 */
	public function addReceiver($receiver)
	{
		$this->receivers[] = $receiver;
	}

	/**
	 * Retrieve receivers of the mail.
	 * @return array
	 */
	public function getReceivers()
	{
		return $this->receivers;
	}

	/**
	 * Set the datatype and data of the round information.
	 * @param string $dataType csv or html
	 * @param string $data
	 */
	public function setData($dataType, $data)
	{
		$this->dataType = $dataType;
		$this->data = $data;
	}

	/**
	 * Retrieve datatype of the round data.
	 * @return string
	 */
	public function getDataType()
	{
		return $this->dataType;
	}

	/**
	 * Get round data of the mail.
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Set the timestamp when mail was sent.
	 * @param string $timestamp SQL timestamp.
	 */
	public function setMailSent($timestamp)
	{
		$this->mailSent = $timestamp;
	}

	/**
	 * Retrieve timestamp of when mail was sent.
	 * @return string SQL timestamp.
	 */
	public function getMailSent()
	{
		return $this->mailSent;
	}

	/**
	 * Set the charset encoding of the round mail.
	 * @param string $charset
	 */
	public function setCharset($charset)
	{
	    $this->charset = $charset;
	}

	/**
	 * Retrieve the charset encoding of the round mail.
	 * @return string
	 */
	public function getCharset()
	{
	    return $this->charset;
	}

}
