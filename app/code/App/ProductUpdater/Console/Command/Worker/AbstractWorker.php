<?php

namespace App\ProductUpdater\Console\Command\Worker;

use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use App\RabbitMq\Model\Service\AbstractService;

/**
 * Class AbstractWorker
 *
 */
class AbstractWorker extends Command
{
    /**
     * @var State $state
     */
    protected $state;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var AbstractService $service
     */
    protected $service;

    /**
     * @var null|InputInterface $input
     */
    protected $input;

    /**
     * @var null|OutputInterface $output
     */
    protected $output;

    /**
     * AbstractWorker constructor.
     *
     * @param State $state
     */
    public function __construct(State $state) {
        $this->state = $state;

        parent::__construct();
    }

    /**
     * IInitialize execute method
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function initExecute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Command ' . $this->getName() . ' invoked.');

        $this->input = $input;
        $this->output = $output;

        $this->setAreaCode();
    }

    /**
     * Just to ensure, that area code is properly set
     *
     * @return void;
     */
    protected function setAreaCode()
    {
        try {
            $this->state->setAreaCode(Area::AREA_ADMIN);
        } catch (\Exception $e) {
            // In this case the area code should be already set, so just do nothing and continue
        }
    }

    /**
     * Prints info message
     *
     * @param string $message
     *
     * @return void
     */
    protected function printInfo($message)
    {
        $this->output->writeln('<info>' . $message . '</info>');
    }

    /**
     * Prints error message
     *
     * @param string $message
     *
     * @return void
     */
    protected function printError($message)
    {
        $this->output->writeln('<error>' . $message . '</error>');
    }
}
