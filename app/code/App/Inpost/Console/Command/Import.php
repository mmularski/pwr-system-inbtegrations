<?php

namespace App\Inpost\Console\Command;

use Magento\Framework\Exception\CouldNotSaveException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Inpost\Api\PointRepositoryInterface;
use App\Inpost\Logger\Logger;
use App\Inpost\Model\Http\Client;
use App\Inpost\Model\Point;
use App\Inpost\Model\PointFactory;
use App\Inpost\Model\ResourceModel\Point as PointResource;

/**
 * Class Import
 */
class Import extends Command
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var PointFactory
     */
    protected $pointFactory;

    /**
     * @var PointRepositoryInterface
     */
    protected $pointRepository;

    /**
     * @var PointResource
     */
    protected $pointResource;

    /**
     * Import constructor.
     *
     * @param Client $client
     * @param Logger $logger
     * @param PointFactory $pointFactory
     * @param PointRepositoryInterface $pointRepository
     * @param PointResource $pointResource
     */
    public function __construct(
        Client $client,
        Logger $logger,
        PointFactory $pointFactory,
        PointRepositoryInterface $pointRepository,
        PointResource $pointResource
    ) {
        parent::__construct();

        $this->client = $client;
        $this->logger = $logger;
        $this->pointFactory = $pointFactory;
        $this->pointRepository = $pointRepository;
        $this->pointResource = $pointResource;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('inpost:import')
            ->setDescription('Import information about shipping points');
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->logger->info('InPost Import has just been started');
            $this->logger->info('Checking connection with API...');

            if (!$this->client->checkConnection()) {
                $this->logger->error('Connection could not be established. Import aborted.');
            }

            $this->logger->info('Connected. Starting import...');
            $data = $this->client->importPointsMeta();
            $this->logger->info(
                sprintf(
                    'Found %d points divided in %d pages by %d elements. Saving in database...',
                    $data['count'],
                    $data['total_pages'],
                    $data['per_page']
                )
            );

            //Set old points to delete
            $this->pointResource->getConnection()->update('inpost_points', ['to_delete' => true]);

            for ($i = 1; $i <= $data['total_pages']; $i++) {
                $this->logger->info(sprintf('Processing page %d/%d', $i, $data['total_pages']));

                $data = $this->client->importPointsData($i);
                $this->processPointsToDb($data['items']);
            }

            //Clear rest 'to delete' points
            $this->pointResource->getConnection()->delete('inpost_points', ['to_delete = ?' => true]);
            $this->logger->info('Import finished successfully');
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
        }
    }

    /**
     * @param array $data
     *
     * @return void
     */
    private function processPointsToDb($data = [])
    {
        foreach ($data as $point) {
            /** @var Point $model */
            $model = $this->pointFactory->create();

            $model->setName($point['name'])
                ->setType(serialize($point['type']))
                ->setStatus($point['status'])
                ->setLatitude($point['location']['latitude'])
                ->setLongitude($point['location']['longitude'])
                ->setOpeningHours($point['opening_hours'])
                ->setCity($point['address_details']['city'])
                ->setProvince($point['address_details']['province'])
                ->setPostCode($point['address_details']['post_code'])
                ->setStreet($point['address_details']['street'])
                ->setBuildingNumber($point['address_details']['building_number'])
                ->setFlatNumber($point['address_details']['flat_number'])
                ->setPointDescription($point['payment_point_descr'])
                ->setLocationDescription($point['location_description'])
                ->setIsPaymentAvailable($point['payment_available'])
                ->setPaymentType(serialize($point['payment_type']))
                ->setIsToDelete(false);

            try {
                $this->pointRepository->save($model);
            } catch (CouldNotSaveException $e) {
                $this->logger->error(sprintf('Could not save point with name: %s', $point['name']));

                continue;
            }

            //Delete same old existing point
            $this->pointResource->getConnection()->delete(
                'inpost_points',
                [
                    'name = ?' => $point['name'],
                    'to_delete = ?' => true,
                ]
            );
        }
    }
}
