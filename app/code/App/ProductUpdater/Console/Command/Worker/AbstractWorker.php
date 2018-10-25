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
     * Prefetch count param name
     * This param defines maximum number of unconsumed messages dispatched to the consumer at the same time
     */
    const INPUT_PARAM_PREFETCH_COUNT = 'prefetch_count';

    /**
     * Default prefetch count
     */
    const DEFAULT_PREFETCH_COUNT = 5;

    /**
     * Minimum prefetch count
     */
    const MIN_PREFETCH_COUNT = 1;

    /**
     * Maximum prefetch count
     */
    const MAX_PREFETCH_COUNT = 50;

    /**
     * @var int $prefetchCount
     */
    protected $prefetchCount = self::DEFAULT_PREFETCH_COUNT;

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
     * This flag minimizes worker log output
     *
     * @var bool
     */
    protected $quietMode = true;

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
     * @param int $count
     *
     * @return $this
     */
    public function setPrefetchCount($count)
    {
        $this->prefetchCount = $count;

        return $this;
    }

    /**
     * Returns Magento Cli script path
     *
     * @return string
     */
    protected function getCliScript()
    {
        return BP . '/bin/magento';
    }

    /**
     * Add default configuration for all workers.
     *
     * @return void
     */
    protected function configure()
    {
        $this->addArgument(
            self::INPUT_PARAM_PREFETCH_COUNT,
            InputArgument::OPTIONAL,
            'Number (from ' . self::MIN_PREFETCH_COUNT . ' to ' . self::MAX_PREFETCH_COUNT . ') of messages '
            . 'which can be dispatched to single consumer. ',
            self::DEFAULT_PREFETCH_COUNT
        );

        parent::configure();
    }

    /**
     * Returns help information string for current command
     *
     * @return string
     */
    protected function getHelpString()
    {
        $command = 'php -f ' . $this->getCliScript() . ' ' . $this->getName() . ' -h';

        return shell_exec(
            $command
        );
    }

    /**
     * Checks default input parameters for all workers
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function checkInput()
    {
        $prefetchCount = $this->input->getArgument(self::INPUT_PARAM_PREFETCH_COUNT);

        if (!$this->validatePCount($prefetchCount)) {
            $this->printError(
                'Incorrect value for parameter: \'' . self::INPUT_PARAM_PREFETCH_COUNT . '\''
            );
            $this->printInfo(
                $this->getHelpString()
            );

            exit(0);
        }
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
        if (!$this->quietMode) {
            $this->logger->info('Command ' . $this->getName() . ' invoked.');
        }

        $this->input = $input;
        $this->output = $output;

        $this->setAreaCode();
        $this->checkInput();
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
     * Validates prefetch caount
     *
     * @param int $pCount
     *
     * @return bool
     */
    protected function validatePCount($pCount)
    {
        return (bool) ($pCount >= self::MIN_PREFETCH_COUNT) && ($pCount <= self::MAX_PREFETCH_COUNT);
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
