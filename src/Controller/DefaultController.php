<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movie;
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

    public function homepage(EntityManagerInterface $em)
    {

        $repo = $em->getRepository(Serie::class);
        $series = $repo->findAll();
        $repo = $em->getRepository(Movie::class);
        $movies = $repo->findAll();
        return $this->render("Home/home.html.twig", ["shows" => $series, "movies" => $movies]);
    }
    /**
     * @Route("/formShows ", name="formulario")
     */

    public function formpage(EntityManagerInterface $em, Request $req)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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

            return $this->redirectToRoute("shows");
        }

        return $this->render("Form/form.html.twig");
    }
    /**
     * @Route("/shows ", name="shows")
     */

    public function dbtestpage(EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Serie::class);
        $series = $repo->findAll();

        return $this->render("Shows/shows.html.twig", ["shows" => $series]);
    }
    /**
     * @Route("/shows/{id}", name="show")
     */
    public function getShow(Serie $serie)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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
        $this->denyAccessUnlessGranted('ROLE_USER');
        $serie = $em->getRepository(Serie::class)->find($id);
        $em->remove($serie);
        $em->flush();
        return $this->redirectToRoute("shows");
    }
    /**
     * @Route("/movies ", name="movies")
     */

    public function moviesPage(EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Movie::class);
        $movies = $repo->findAll();

        return $this->render("Movies/movies.html.twig", ["movies" => $movies]);
    }
    /**
     * @Route("/movies/{id}", name="movie")
     */
    public function getMovie(Movie $movie)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render(
            "Movies/movie.html.twig",
            ['movie' => $movie]
        );
    }
    /**
     * @Route("/formMovies ", name="formulario2")
     */

    public function formpage2(EntityManagerInterface $em, Request $req)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $title = $req->request->get("title");
        $duration = $req->request->get("duration");
        $description = $req->request->get("description");
        $image = $req->request->get("image");
        $background = $req->request->get("background");
        $link = $req->request->get("link");

        if ($title) {
            $newMovie = new Movie();
            $newMovie->setTitle($title);
            $newMovie->setDescription($description);
            $newMovie->setDuration($duration);
            $newMovie->setImage($image);
            $newMovie->setBackground($background);
            $newMovie->setLink($link);

            $em->persist($newMovie);
            $em->flush();

            return $this->redirectToRoute("movies");
        }

        return $this->render("Form/form2.html.twig");
    }
    /**
     * @Route("/database/deletemovie/{id}", name="deletePageMovie")
     */
    public function deleteMovie($id, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $movie = $em->getRepository(Movie::class)->find($id);
        $em->remove($movie);
        $em->flush();
        return $this->redirectToRoute("movies");
    }
    /**
     * @Route("/favmovies/{id}", name="favmovies")
     */
    public function favMovies($id, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Movie::class);
        $movie = $repo->find($id);
        $user = $this->getUser();
        $user->addFav($movie);
        $em->flush();
        return $this->redirectToRoute("favs");
    }
    /**
     * @Route("/favshows/{id}", name="favshows")
     */
    public function favShows($id, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Serie::class);
        $serie = $repo->find($id);
        $user = $this->getUser();
        $user->addFavShow($serie);
        $em->flush();
        return $this->redirectToRoute("favs");
    }
    /**
     * @Route("/watchlist", name="favs")
     */
    public function favsPage()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $favs = $user->getFavShow();
        $favsMovies = $user->getFav();
        return $this->render("Favs/favs.html.twig", ["favs" => $favs, "favMovies" => $favsMovies, "user" => $user]);
    }
    /**
     * @Route("/deletefavshow/{id}", name="deletefavshow")
     */
    public function removeFavShow(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Serie::class);
        $serie = $repo->find($id);
        $user = $this->getUser();
        $user->removeFavShow($serie);
        $em->flush();
        return $this->redirectToRoute("favs");
    }
    /**
     * @Route("/deletefavmovie/{id}", name="deletefavmovie")
     */
    public function removeFavMovie(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repo = $em->getRepository(Movie::class);
        $movie = $repo->find($id);
        $user = $this->getUser();
        $user->removeFav($movie);
        $em->flush();
        return $this->redirectToRoute("favs");
    }
}
