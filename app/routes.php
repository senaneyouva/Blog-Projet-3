<?php									

//Blog écrivain billet simple pour l'Alaska 

// Page accueil du site 
$app->get('/', "MicroCMS\Controller\HomeController::indexAction")
->bind('home');

// Page d'rticle
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::ArticleAction")
->bind('article');

// Ajouter un commentaire à un commentaire
$app->post('article/comment/{id}/comment', "MicroCMS\Controller\HomeController::ImbriqueAction")
    ->bind('comment_comment');

    // Signaler un commentaire
$app->match('/comment/{id}/signal', "MicroCMS\Controller\HomeController::signalAction")
    ->bind('comment_signal');

 // Page profil
$app->get('/about', "MicroCMS\Controller\HomeController::aboutAction")
    ->bind('about');

//Mentiosn légales
$app->get('/droits', "MicroCMS\Controller\HomeController::droitsAction")
    ->bind('droits');

// Login
$app->get('/login', "MicroCMS\Controller\HomeController::loginAction")
->bind('login');


// Administration

//accueil administartion 
$app->get('/admin', "MicroCMS\Controller\AdminController::indexAction")
->bind('admin');

//Articles

// Ajouter un nouvel article
$app->match('/admin/article/add', "MicroCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Editer un article
$app->match('/admin/article/{id}/edit', "MicroCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Supprimer un article
$app->get('/admin/article/{id}/delete', "MicroCMS\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');


//Commentaires

// Editer un commentaire
$app->match('/admin/comment/{id}/edit', "MicroCMS\Controller\AdminController::editCommentAction")
->bind('admin_comment_edit');


//Valider un commentaire 
$app->match('/admin/comment/{id}/modif', "MicroCMS\Controller\AdminController::modifCommentAction")
    ->bind('admin_comment_modif');

// Supprimer un commentaire
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controller\AdminController::deleteCommentAction")
->bind('admin_comment_delete');


// UTILISATEURS

// Ajouter un utilisateur
$app->match('/admin/user/add', "MicroCMS\Controller\AdminController::addUserAction")
->bind('admin_user_add');

// Edition d'un utilisateur
$app->match('/admin/user/{id}/edit', "MicroCMS\Controller\AdminController::editUserAction")
->bind('admin_user_edit');

// Supprimer un utilisateur
$app->get('/admin/user/{id}/delete', "MicroCMS\Controller\AdminController::deleteUserAction")
->bind('admin_user_delete');


