<?php
/**
 * @package  App\ProductUpdater
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace App\ProductUpdater\Console\Command\Worker;

use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\RabbitMq\Model\Service\AbstractService;
use App\ProductUpdater\Model\Service;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class ProductUpdateWorker
 */
class ProductUpdateWorker extends AbstractWorker
{
    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * @var Service $service
     */
    protected $service;

    /**
     * ProductWorkerCommand constructor.
     *
     * @param State        $state
     * @param Logger       $logger
     * @param Service      $service
     */
    public function __construct(State $state, Logger $logger, Service $service)
    {
        $this->service = $service;
        $this->logger  = $logger;

        parent::__construct($state);
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('rabbitmq:worker:updater')
            ->setDescription('Consumes messages from queue');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void;
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initExecute($input, $output);
        $this->service->getConsumer()->setPrefetchCount($this->prefetchCount);

        $exitCode = $this->service->getConsumer()->consume();

        switch ($exitCode) {
            case AbstractService::EXIT_CODE_SUCCESS:
                $this->printInfo('Order cancel export worker executed successfully.');
                break;
            case AbstractService::EXIT_CODE_TIMEOUT:
                $this->printInfo('Consumer exceeded max execution time.');
                break;
            case AbstractService::EXIT_CODE_ERROR:
            default:
                $this->printError('An error occurred during consumer execution.');
                break;
        }
    }
}
