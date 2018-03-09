<?php

namespace MicroCMS\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Article;
use MicroCMS\Domain\User;
use MicroCMS\Form\Type\ArticleType;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\UserType;

class AdminController {

    /**
     * Controller de l'index
     *
     * @param Application $app Silex application
     */
  public function indexAction(Application $app)
    {

        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAllBySignal();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'users' => $users,
            'comments' => $comments));
    }
    
    // CONTROLLER ARTICLE
    
    /**
     * Controller ajout d'articles 
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
  public function addArticleAction(Request $request, Application $app) {
      $article = new Article();
      $dateNow = date('Y-m-d H:i:s');
      $article->setDate($dateNow);
      $articleForm = $app['form.factory']->create(ArticleType::class, $article);
      $articleForm->handleRequest($request);
      if ($articleForm->isSubmitted() && $articleForm->isValid()) {
          $app['dao.article']->save($article);
          $app['session']->getFlashBag()->add('success','L\'article a été publié');
          return $app->redirect($app['url_generator']->generate('admin'));
      }
      return $app['twig']->render('article_form.html.twig', array(
          'title' => 'New article',
          'articleForm' => $articleForm->createView()));
  }

    /**
     * Controller édition d'articles 
     *
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editArticleAction($id, Request $request, Application $app) {
      $article = $app['dao.article']->find($id);
      $articleForm = $app['form.factory']->create(ArticleType::class, $article);
      $articleForm->handleRequest($request);
      if ($articleForm->isSubmitted() && $articleForm->isValid()) {
        $app['dao.article']->save($article);
        $app['session']->getFlashBag()->add('success', 'L\'article a été modifié.');
        return $app->redirect($app['url_generator']->generate('admin'));
      }
      return $app['twig']->render('article_form.html.twig', array(
        'title' => 'Edit article',
        'articleForm' => $articleForm->createView()));
    }

    /**
     * Controller suppression d'articles 
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     */
    public function deleteArticleAction($id, Application $app) {
      // Delete all associated comments
      $app['dao.comment']->deleteAllByArticle($id);
      // Delete the article
      $app['dao.article']->delete($id);
      $app['session']->getFlashBag()->add('success', 'L\'article a été supprimé.');
      // Redirect to admin home page
      return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Controlleur éditer un commentaire 
     *
     * @param integer $id Comment id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editCommentAction($id, Request $request, Application $app) {
      $comment = $app['dao.comment']->find($id);
      $commentForm = $app['form.factory']->create(CommentType::class, $comment);
      $commentForm->handleRequest($request);
      if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire a été modifié');
          return $app->redirect($app['url_generator']->generate('admin'));
      }
      return $app['twig']->render('comment_form.html.twig', array(
        'title' => 'Edit comment',
        'commentForm' => $commentForm->createView()));
    }
    
   
    //Modification modération
    public function modifCommentAction($id, Request $request, Application $app)
    {
        $comment = $app['dao.comment']->find($id);
        $app['dao.comment']->modifSignal($comment);
        $app['session']->getFlashBag()->add('success', 'Le commentaire est affiché comme non signalé .');
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Controller effacer un commentaire 
     *
     * @param integer $id Comment id
     * @param Application $app Silex application
     */
    public function deleteCommentAction($id, Application $app) {
    $app['dao.comment']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le commentaire a été supprimé');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
    }
    
    // CONTROLLER UTILISATEUR
    
    /**
     * Controller ajouter un utilisateur 
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addUserAction(Request $request, Application $app) {
      $user = new User();
      $userForm = $app['form.factory']->create(UserType::class, $user);
      $userForm->handleRequest($request);
      if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.bcrypt'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été ajouté.');
        return $app->redirect($app['url_generator']->generate('admin'));
      }
      return $app['twig']->render('user_form.html.twig', array(
        'title' => 'New user',
        'userForm' => $userForm->createView()));
    }

    /**
     * Controller éditer un utilisateur
     *
     * @param integer $id User id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editUserAction($id, Request $request, Application $app) {
      $user = $app['dao.user']->find($id);
      $userForm = $app['form.factory']->create(UserType::class, $user);
      $userForm->handleRequest($request);
      if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été modifié.');
        return $app->redirect($app['url_generator']->generate('admin'));
      }
      return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Edit user',
        'userForm' => $userForm->createView()));
    }
    /**
     * Controller effacer un utilisateur
     *
     * @param integer $id User id
     * @param Application $app Silex application
     */
    public function deleteUserAction($id, Application $app) {
      // Delete the user
      $app['dao.user']->delete($id);
      $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été supprimé.');
      // Redirect to admin home page
      return $app->redirect($app['url_generator']->generate('admin'));
    }
}
