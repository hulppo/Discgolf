<?php
namespace Lupo\Discgolf\Command;

use Lupo\Discgolf\Entity\Round;

use Lupo\Discgolf\Entity\Course;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Lupo\Discgolf\Round\RoundParser;

use Lupo\Discgolf\Mail\MailParser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParsemailCommand extends ContainerAwareCommand {
	protected function configure()
	{
		$this->setName('discgolf:parsemail')
		    ->setDescription('Parse round data from mail')
		    ->addArgument('filename', InputArgument::OPTIONAL,
		        'Do you want to parse from a file? Default is stdin.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$filename = $input->getArgument('filename');
		if ($filename) {
		    $mailStr = file_get_contents($filename);
		} else {
		    $mailStr = file_get_contents('php://stdin');
		}
		$quiet = false;
		if ($input->getOption('quiet')) {
		    $quiet = true;
		}

		$mimeDecoder = new \Mail_mimeDecode($mailStr); // no namespace

		$mailParser = new MailParser();

		$mailParser->setMail($mailStr);
		$mailParser->setMimeDecoder($mimeDecoder);

		$roundAmount = $mailParser->parse();

		if (! $quiet) {
		    echo "FOUND $roundAmount rounds in mail.";
		}

		$rounds = $mailParser->getRounds();

		$container = $this->getContainer();
		/* @var Lupo\Discgolf\Course\CourseManager $courseManager */
		$courseManager = $this->getContainer()->get('coursemanager');
		/* @var Lupo\Discgolf\Round\RoundManager $roundManager */
		$roundManager = $this->getContainer()->get('roundmanager');

		/* @var Discgolf\Round\Mail $round */
		foreach ($rounds as $round) {
		    $roundParser = new RoundParser();
		    if ($round->getDataType() == 'csv') {
		        $roundParser->setCsvData($round->getData(), $round->getCharset());
		    } elseif ($round->getDataType() == 'html') {
		        $roundParser->setHtmlData($round->getData(), $round->getCharset());
		    }
		    if ($roundParser->parse()) {
		        $course = $courseManager->getCourseForParsing($roundParser);
		        $roundManager->createNewRound($course, $roundParser);
		    }
		}
		if (! $quiet) {
		    print_r($rounds);
		}
	}
}
