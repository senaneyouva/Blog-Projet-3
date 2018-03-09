<?php

namespace MicroCMS\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\DAO\CommentDAO;
use MicroCMS\Form\Type\CommentType;

class HomeController {

  /**
  * Controller de la page d'accueil
  *
  * @param Application $app Silex application
  */
  public function indexAction(Application $app) {
    $articles = $app['dao.article']->findAll();
    return $app['twig']->render('index.html.twig', array('articles' => $articles));
  }

//Controller modération 
  public function signalAction(  $id,  Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $app['dao.comment']->Signal($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a été signalé ');
        return $app->redirect($app['url_generator']->generate('article', ['id' => $comment->getArticle()->getId()]));
    }

  /**
  * Controller article
  *
  * @param integer $id Article id
  * @param Request $request Incoming request
  * @param Application $app Silex application
  */
  public function ArticleAction($id, Request $request, Application $app) {
    $article = $app['dao.article']->find($id);
    $commentFormView = null;
    $comment = new Comment();
    $comment->setArticle($article);
    $dateNow = date('Y-m-d H:i:s'); 
    $comment->setDate($dateNow);
    $commentForm = $app['form.factory']->create(CommentType::class, $comment );
    $commentForm->handleRequest($request);
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
      $app['dao.comment']->save($comment);
      $app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');
    }
    $commentFormView = $commentForm->createView();
    $comments = $app['dao.comment']->findAllByArticle($id);
    return $app['twig']->render('article.html.twig', array(
      'article' => $article,
      'comments' => $comments,
      'commentForm' => $commentFormView));
    }

      /**
      * Controller login
      *
      * @param Request $request Incoming request
      * @param Application $app Silex application
      */
    public function loginAction(Request $request, Application $app) {
      return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
      ));
    }
    
    /**
    * Controller commentaires imbriqués 
    *
    * @param Request $request
    * @param Application $app
    */
    public function ImbriqueAction($id, Request $request, Application $app){
      $comment = new Comment();
      $comment->setAuthor($request->get("senane_auteur"));
      $comment->setContent($request->get("senane_message"));
      $comment->setFamilyParent($request->get("senane_parent_id"));
      $dateNow = date('Y-m-d H:i:s'); 
      $comment->setDate($dateNow);
      $article = $app['dao.article']->find($id);
      $comment->setArticle($article);
      $app['dao.comment']->save($comment);
      $app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');
      return $app->redirect($app['url_generator']->generate('article' ,array(
        'id' => $id)));
      }

    //Controller page profil
       public function aboutAction(Application $app) {
        return $app['twig']->render('about.html.twig');
    }

    //Controller page mentions legales
      public function droitsAction(Application $app) {
        return $app['twig']->render('droits.html.twig');
    }

}
   