<?php namespace MaxLGGit\ImageSearchAlgolia\Classes\Helpers;


use Lovata\Shopaholic\Models\Product;

use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Likelihood;

use function Amp\async;
use function Amp\Future\await;
use function Amp\Future\awaitAll;

use Carbon\Carbon;


class GoogleApiRequestHelper{

    // protected array $productsIds;

    protected ImageAnnotatorClient $client;

    public function __construct(){
        // $this->productsIds = $productsIds;

        $this->client = new ImageAnnotatorClient();
    }

    public function getImagesDescriptions(array $productsIds):array{
        $imagesDescriptions = [];

        $products = Product::whereIn('id', $productsIds)->get();
        $start = microtime(true);

        try {
            $annotatins = $this->getProductsImageAnnotations($products, $this->client);
            Log::info('Await_responce: '.json_encode($annotatins));
        } catch (\Exception $e) {
            // If any one of the requests fails the combo will fail
            Log::error(json_encode($e) );
        }

        $finish = microtime(true);
        $duration = round($finish - $start, 4);

        echo "finished web request in $duration\n";
        return $imagesDescriptions;
    }



protected function getProductPreviewImage($product){
    return file_get_contents($product->preview_image->getPath());
}

protected  function getProductsImageAnnotations($products, $client){


    $responses = awaitAll($products->map(function ($product) use ($client){
        return async(function() use ($product, $client){
            $imageData = $this->getProductPreviewImage($product);
            $imageObject = $client->createImageObject($imageData);
            try {
                $response = $client->annotateImage($imageObject, [TYPE::LABEL_DETECTION]);
            }catch(\Exception $exception){
                // continue to the next iteration
                Log::error(json_encode($exception));
            }

            $annotations = $response->getLabelAnnotations();

            $labelsArr = [];
            foreach ($annotations as $annotation) {
                if ($annotation->getScore() > 0.5){
                    $labelsArr [] = array ('description' => $annotation->getDescription(), 'score' => $annotation->getScore());
                }
            }
            return array('imageURL' => $product->preview_image->getPath(), 'objectID' => $product->id , 'labels' =>  $labelsArr  );

        });
    }));

    if (is_array($responses[0]) && count($responses[0]) > 0) {
        Log::error('Error getting annotations from google image annotator');
        return [];
    }
    return $responses[1];
}



}
