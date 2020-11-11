<?php

namespace App\Listener;

use App\Entity\Article;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Doctrine\ORM\Event\LifecycleEventArgs as EventLifecycleEventArgs;

class ImageCacheSubscriber implements EventSubscriber{
    

    private $em;    
 
    private $cacheManager;

    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper, EntityManagerInterface $em)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
        $this->em = $em;
    }
    
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    public function preRemove(EventLifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Article){
            return;
        }

        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
    }

    public function preUpdate(EventLifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Article){
            return;
        }

        if($entity->getImageFile() instanceof UploadedFile){
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
    }
}