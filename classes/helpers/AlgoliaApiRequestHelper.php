<?php namespace MaxLGGit\ImageSearchAlgolia\Classes\Helpers;


class AlgoliaApiRequestHelper{

    protected array $imagesDescriptions;

    public function __construct(array $imagesDescriptions){
        $this->imagesDescriptions = $imagesDescriptions;
    }

    public function getImagesDescriptions():array{
        $imagesDescriptions = [];

        return $imagesDescriptions;
    }

}
