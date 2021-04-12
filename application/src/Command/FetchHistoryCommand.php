<?php

declare(strict_types=1);

namespace App\Command;

use App\Document\Pair;
use App\Service\HistoryService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchHistoryCommand extends Command
{
    protected static $defaultName = 'app:fetch-history';
    private DocumentManager $dm;
    private HistoryService $historyService;

    public function __construct(DocumentManager $dm, HistoryService $historyService)
    {
        $this->dm = $dm;
        $this->historyService = $historyService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pairs = $this->dm->getRepository(Pair::class)->findAll();
        foreach ($pairs as $pair) {
            if (!$this->historyService->updateMarketHistory($pair)) {
                $output->writeln(sprintf('Pair %s was not processed. See logs for details.', $pair->getSymbol()));
                return Command::FAILURE;
            }
            $output->writeln(sprintf('Pair %s fetched.', $pair->getSymbol()));
        }

        return Command::SUCCESS;
    }
}
