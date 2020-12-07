<?php

namespace App\Controller\user;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/profile/picture")
 */
class PictureController extends AbstractController{

    /**
     * @Route("/{id}", name="picture.delete", methods="DELETE")
     */
    public function delete(Picture $picture, Request $request){
        
        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'. $picture->getId(), $data['_token'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();
            return new JsonResponse(['success'=> 1]);

        }

        return new JsonResponse(['error' => 'Token invalide'], 400);

    }
    
}