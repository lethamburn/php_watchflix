<?php

namespace App\Controller;

use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */

    public function homepage()
    {
        return $this->render("Home/home.html.twig");
    }
    /**
     * @Route("/form ", name="formulario")
     */

    public function formpage(EntityManagerInterface $em, Request $req)
    {
        $title = $req->request->get("title");
        $seasons = $req->request->get("seasons");
        $chapters = $req->request->get("chapters");
        $description = $req->request->get("description");
        $image = $req->request->get("image");
        $background = $req->request->get("background");
        $link = $req->request->get("link");

        if ($title) {
            $newSerie = new Serie();
            $newSerie->setTitle($title);
            $newSerie->setSeasons($seasons);
            $newSerie->setDescription($description);
            $newSerie->setChapters($chapters);
            $newSerie->setImage($image);
            $newSerie->setBackground($background);
            $newSerie->setLink($link);

            $em->persist($newSerie);
            $em->flush();
        }

        return $this->render("Form/form.html.twig");
    }
    /**
     * @Route("/shows ", name="shows")
     */

    public function dbtestpage(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Serie::class);
        $series = $repo->findAll();

        return $this->render("Shows/shows.html.twig", ["shows" => $series]);
    }
     /**
     * @Route("/shows/{id}", name="show")
     */
    public function getShow(Serie $serie)
    {

        return $this->render(
            "Shows/show.html.twig",
            ['serie' => $serie]
        );
    }

    /**
     * @Route("/database/delete/{id}", name="deletePage")
     */
    public function deleteShow($id, EntityManagerInterface $em)
    {

        $serie = $em->getRepository(Serie::class)->find($id);
        $em->remove($serie);
        $em->flush();
        return $this->redirectToRoute("shows");
    }
}
