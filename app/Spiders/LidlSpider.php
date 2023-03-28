<?php

namespace App\Spiders;

use App\Repository\ProductRepository;
use App\Services\ProductService;
use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class LidlSpider extends BasicSpider
{
    public array $startUrls = [
        'https://lidl.rs'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    public $items = [];

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $productRepository = new ProductRepository();
        $response->filter('.ret-o-card__link')->each(function ($node, $i) use($productRepository) {
            $name = $node->filter('.ret-o-card__headline')->text();
            $price = $node->filter('.lidl-m-pricebox__price')->text();

            $product = $productRepository->updateOrCreate(['name' => $name], [
                'name' => $name,
                'price' => $price,
            ]);
            // 6 is lidl
            $product->stores()->sync(array_merge($product->stores->pluck('id')->toArray(), [6]));
        });

        yield  $this->item([]);

    }
}
