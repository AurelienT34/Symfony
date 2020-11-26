<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function home(ArticleRepository  $articleRepository): Response
    {
        $articles = $articleRepository->findBy(array(),array('createAt'=>'DESC'),5,0);

        return $this->render('Blog/index.html.twig', [
            'articles'=>$articles
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository  $articleRepository, Request $request): Response
    {
        /**
         * $article = $repo->find(12); // Article numéro 12
         *  $article = $repo->findOneByTitle('Titre de l\'article');
         *  $articles = $repo->findByTitle('Titre de l\'article');
         */

        $articles = $articleRepository->findAll();

        $form = $this->createForm(SearchArticleType::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Recherche d'article avec les mots-clés
            $articles = $articleRepository->search($search->get('mots')->getData(),
            $search->get('categorie')->getData());
        }


        return $this->render('Blog/blog.html.twig',[
            'articles'=>$articles,
            'formSearch'=>$form->createView()
        ]);
    }

    /**
     * @Route("/blog/article/{id}", name="singleArticle")
     * @param Article $article
     * @param Request $request
     * @param ManagerRegistry $manager
     * @return Response
     */
    public function showSingleArticle(Article $article, Request $request, ManagerRegistry  $manager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->getManager()->persist($comment);
            $manager->getManager()->flush();
            return  $this->redirectToRoute('singleArticle',['id'=>$article->getId()]);
        }
        return $this->render('Blog/singleArticle.html.twig',[
            'article'=>$article,
            'commentForm'=>$form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/article/remove/{id}", name="removeSingleArticle")
     * @param Article $article
     * @param ManagerRegistry $manager
     * @return Response
     */
    public function removeSingleArticle(Article $article, ManagerRegistry $manager): Response
    {
        $manager->getManager()->remove($article);
        $manager->getManager()->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/blog/creationarticle"), name="creation_article")
     * @Route("/blog/creationarticle/{id}/edit", name="modification_article")
     * @param Article|null $article
     * @param Request $request
     * @param ManagerRegistry $manager
     * @return Response
     */
    public function formManager(?Article $article,Request $request, ManagerRegistry  $manager): Response
    {
        if(!$article) {
            $article = new Article();
        }

        if($article->getId() !== null) {
            $article->setTitle($article->getTitle())
                    ->setContent($article->getContent())
                    ->setImage($article->getImage());
        }

        /**
        $form = $this->createFormBuilder($article)
                        ->add('title')
                        ->add('content')
                        ->add('image')
                        ->getForm();
        */

        $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!$article->getId()) {
                $article->setCreateAt(new \DateTime());
            }

            $manager->getManager()->persist($article);
            $manager->getManager()->flush();
            return  $this->redirectToRoute('singleArticle',['id'=>$article->getId()]);
        }

        return $this->render('Blog/creationArticle.html.twig',[
            'formArticle' => $form->createView(),
            'editMode'=> $article->getId() !== null]);
    }
}