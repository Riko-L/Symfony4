<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * Class AccountController
 * @package App\Controller
 * @Route("/photo")
 *
 */
class PhotoController extends Controller
{


    /**
     * @Route("/{ads}/new/", name="photo_new", methods="GET|POST")
     *
     */
    public function new(Request $request, Ads $ads , EntityManagerInterface $entityManager): Response
    {


        $photo = new Photo();

        $person = $this->getUser();

        $photo->setAds($ads);

        $form = $this->createForm(PhotoType::class, $photo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $photo->getFile();



            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('upload_photo'),
                $fileName
            );
            $photo->setFile($fileName);




            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('account_show');
        }

        return $this->render('ads/new.html.twig', [
            'ads' => $ads,
            'photo' => $photo,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/showone/{ad}", name="photo_show_one")
     * @param Photo $photo
     * @return BinaryFileResponse
     */
    public function show(PhotoRepository $photoRepository , Ads $ad){

        $photo = $ad->getPhotos();

        return new BinaryFileResponse(
            $this->getParameter("upload_photo").$photo[0]->getFile()
        );
    }

    /**
     * @Route("/showall/{photo}", name="photo_show_all")
     * @param Photo $photo
     * @return BinaryFileResponse
     */
    public function showAll(Photo $photo){

        return new BinaryFileResponse(
            $this->getParameter("upload_photo").$photo->getFile()
        );
    }


    /**
     * @Route("/{photo}" , name="photo_delete" ,methods="DELETE")
     */
    public function delette(Request $request ,EntityManagerInterface $entityManager ,Photo $photo)
    {
        $fileSystem = new Filesystem();

        if ($this->isCsrfTokenValid('delete' . $photo->getId(), $request->request->get('_token'))) {

            $file = $photo->getFile();
            $path = $this->getParameter('upload_photo');

            if($fileSystem->exists($path.$file)) {

                $fileSystem->remove($path.$file);
            }

          $entityManager->remove($photo);


            $entityManager->flush();
            return $this->redirectToRoute('account_show');

        }
    }
}
