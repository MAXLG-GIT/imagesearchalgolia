<?php namespace MaxLGGit\ImageSearchAlgolia\Jobs;

use Log;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;



use MaxLGGit\ImageSearchAlgolia\Classes\Helper\GoogleApiRequestHelper;

/**
 * ImagesClassificationUpdateJob Job
 */
class ImagesClassificationUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * __construct a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * handle the job.
     */
    public function handle(): void
    {
        $productsIds = $this->data['productsIds'];

        if (!is_array($productsIds) ||  count($productsIds) < 1 )  return;

        $gHelper =  new GoogleApiRequestHelper(); //
        $algHelper =  new AlgoliaApiRequestHelper(); //

        $imagesDescriptions = $gHelper->getImagesDescriptions($productsIds);

        $algHelper->setDescriptions($imagesDescriptions);


    }
}
