<?php namespace MaxLGGit\ImageSearchAlgolia\Classes\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Queue;

use function Amp\async;
use function Amp\Future\await;

use Lovata\Shopaholic\Models\Product;

use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Likelihood;


class DescriptionsUpdaterHelper{

    public function __construct(){

    }

    public static function run(){



        // make jobs for all products devided by packs of 100 products in each pack

        //$products = Product::where(['active' => 1])->limit(3)->get();


        $productsIds = Product::whereHas('preview_image', function ($query) {
            $query->where('updated_at', '>', Carbon::now()->subDays(20));
        })->limit(10)->pluck('id')->all();


        // $products = Product::whereHas('preview_image', function ($query) {
        //     $query->where('updated_at', '>', Carbon::now()->subDays(20));
        // })->limit(10)->get();

        // ->limit(3)




        $productId = 18078;
        // 7940
        // 18078
        // 000000


        //Log::info(count($products));
        $productsIdsChunks = array_chunk($productsIds, 3);


        // $productsChunks = $products->chunk(3);

        // для каждого chunk create job
        // в job по каждому product выполнить запрос в google
        // отправлять результат в algolia


        // DUMMY
        // this code is from job itself

        $gHelper =  new GoogleApiRequestHelper(); //
       //  $algHelper =  new AlgoliaApiRequestHelper(); //


        foreach ($productsIdsChunks as $productsIdsChunk){
            $annotatins = $gHelper->getImagesDescriptions($productsIdsChunk);

            foreach ($annotatins as $annotatin){
                echo 'Responce:'.PHP_EOL;
                echo '<pre>';
                print_r($annotatin);
                echo '/<pre>';
                //break;
            }
            //$algHelper->setDescriptions($imagesDescriptions);
        }

        return;


        $minMultiplier = 0;
        foreach ($productsIdsChunks as $productsIdsChunk){
            // $time = Carbon::now()->addMinutes($minMultiplier*5);
            $time = Carbon::now()->addSeconds($minMultiplier*15);
            Queue::later($time, 'ImagesClassificationUpdateJob', ['productsIds' => $productsIdsChunk]);
            $minMultiplier++;
        }


        return;


    }

}

