<?php
namespace Lupo\Discgolf\Command;

use Lupo\Discgolf\Mail\MailParser;
use Lupo\Discgolf\Round\RoundParser;

use Symfony\Component\Finder\Finder;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParsemaildirCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $descr = 'Check given maildir for new mail.'
            . ' And parse new mail for discgolf rounds.'
            . ' Marks parsed mail non-new according to maildir structure.';
        $this->setName('discgolf:parsemaildir')
            ->setDescription($descr)
            ->addArgument('maildir', InputArgument::REQUIRED,
		        'Path to maildir directory to check.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maildir = $input->getArgument('maildir');
        if (! file_exists($maildir)) {
        	$output->writeln("<error>Non-existent maildir $maildir given.</error>");
            return 1;
        }
        if (substr($maildir, -1, 1) != '/') {
            $maildir .= '/'; // append slash if not set
        }
        $newMaildir = $maildir . 'new/';
        $readMaildir = $maildir . 'cur/';
        if (! file_exists($newMaildir)) {
        	$output->writeln("<error>No new mail directory found $newMaildir.</error>");
            return 2;
        }
        if (! file_exists($readMaildir)) {
            $output->writeln("<error>No read mail directory found $readMaildir.</error>");
            return 3;
        }
        $container = $this->getContainer();
        /* @var Lupo\Discgolf\Course\CourseManager $courseManager */
        $courseManager = $this->getContainer()->get('coursemanager');
        /* @var Lupo\Discgolf\Round\RoundManager $roundManager */
        $roundManager = $this->getContainer()->get('roundmanager');

        /* @var Lupo\Discgolf\User\UserManager $userManager */
        $userManager = $this->getContainer()->get('usermanager');

        $finder = new Finder();
        $finder->in($newMaildir);
        foreach ($finder->files() as $file) {
            $filename = $file->getFilename();
            $mailStr = file_get_contents($newMaildir . $filename);
            $mimeDecoder = new \Mail_mimeDecode($mailStr); // no namespace

            $mailParser = new MailParser();
            $mailParser->setMail($mailStr);
            $mailParser->setMimeDecoder($mimeDecoder);
            $roundAmount = $mailParser->parse();
            $rounds = $mailParser->getRounds();

            /* @var Discgolf\Round\Mail $round */
            foreach ($rounds as $round) {
                $sender = $round->getSender();
                if ($round->getCharset() != '') {
                    $sender = iconv($round->getCharset(), 'utf-8', $sender);
                    $round->setSender($sender);
                }
                $user = $userManager->getUserForSender($sender);
                $roundParser = new RoundParser();
                if ($round->getDataType() == 'csv') {
                    $roundParser->setCsvData($round->getData(), $round->getCharset());
                } elseif ($round->getDataType() == 'html') {
                    $roundParser->setHtmlData($round->getData(), $round->getCharset());
                }
                if ($roundParser->parse()) {
                    // print_r($roundParser); die();
                    $course = $courseManager->getCourseForParsing($roundParser);
                    if (! $roundManager->createNewRound($course, $roundParser, $user)) {
                        echo "ERROR: Round creation failed, probably exists already."
                            . " Course " . $course->getName()
                            . ", round with timestamp " . $roundParser->getRoundStart()
                            . ", sender " . $round->getSender()
                            . "\n";
                    }
                }
            }
            // move file when done parsing and stuff
            rename($newMaildir . $filename, $readMaildir . $filename);
        }
    }
}
