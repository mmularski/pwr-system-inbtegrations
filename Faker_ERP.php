<?php

echo 'Set interval (in seconds): ';
$interval = trim(fgets(STDIN, 1024));

while (!is_numeric($interval)) {
    echo "\n You set wrong value. Interval must be a number \n";
    echo 'Set interval (in seconds): ';
    $interval = trim(fgets(STDIN, 1024));
}

//Send requests and print response
while (true) {
    $sku = (new Helper())->getRandomProductSku();

    $diff = rand(-3, 3);
    //Avoid 0
    while (0 === $diff) {
        $diff = rand(-3, 3);
    }

    echo "Chosen SKU: $sku \n";
    echo "Difference: $diff \n";

    $ch = curl_init('http://magento2.local/rest/V1/product/updater');
    curl_setopt_array(
        $ch,
        [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode(
                [
                    'object' => [
                        'productSku' => $sku,
                        'diff' => $diff,
                    ],
                ]
            ),
        ]
    );

    $response = curl_exec($ch);

    echo "Response from Magento:  $response \n";

    curl_close($ch);
    sleep($interval);
}

class Helper
{
    public function getRandomProductSku()
    {
        $connection = new PDO('mysql:host=localhost;dbname=magento2', 'magento2', 'ci8Uega1');
        $value = $connection->query('SELECT sku FROM catalog_product_entity ORDER BY RAND() LIMIT 1')->fetch();

        return $value['sku'];
    }
}
