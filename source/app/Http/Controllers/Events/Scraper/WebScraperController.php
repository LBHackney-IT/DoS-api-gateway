<?php

namespace App\Http\Controllers\Events\Scraper;

use App\Component\EventStream\KafkaEventStream;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Class WebScraperController
 *
 * @package App\Http\Controllers\Events\Scraper
 */
class WebScraperController extends Controller
{

    /**
     * A Kafka event stream utility object.
     *
     * @var \App\Component\EventStream\KafkaEventStream
     */
    protected $eventStream;

    /**
     * Make a Hackney iCare hello request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function iCareHello()
    {
        $data = [
            'package' => 'icare_webpage_scraper_package',
            'operation' => 'hello',
            'parameters' => [],
        ];

        $this->eventStream = new KafkaEventStream();
        $this->eventStream->getQueue()->push('App\\Jobs\\ProcessWebPageScrapeJob', $data, 'scraper');
        $build = [
            'data' => $data,
            'queue' => 'scraper',
            'correlationId' => $this->eventStream->getQueue()->getCorrelationId(),
        ];

        return response()->json($build);
    }


    /**
     * Make a Hackney iCare service page scraper request.
     *
     * @param string $id - Service ID string.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function iCareService($id)
    {
        $build = [];
        return response()->json($build);
    }
}
