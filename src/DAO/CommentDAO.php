<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Comment;

class CommentDAO extends DAO
{
  /**
  * @var \microcms\DAO\ArticleDAO
  */
  private $articleDAO;

  public function setArticleDAO(ArticleDAO $articleDAO) {
    $this->articleDAO = $articleDAO;
  }
  
  /**
  * Créer un commentaire basé sur la BDD
  *
  * @param array $row The DB row containing Comment data.
  * @return \microcms\Domain\Comment
  */
  protected function buildDomainObject(array $row) {
    $comment = new Comment();
    $comment->setId($row['com_id']);
    $comment->setAuthor($row['com_author']);
    $comment->setDate($row['com_date']);
    $comment->setContent($row['com_content']);
    $comment->setDanger($row['com_danger']);
    $comment->setFamilyParent($row['com_family_id']);
    if (array_key_exists('art_id', $row)) {
      // Find and set the associated article
      $articleId = $row['art_id'];
      $article = $this->articleDAO->find($articleId);
      $comment->setArticle($article);
    }
    return $comment;
  }
  
  /**
  * Enregistre un commentaire dans la BDD 
  *
  * @param \microcms\Domain\Comment $comment The comment to save
  */
  public function save(Comment $comment) {
    $commentData = array(
      'art_id' => $comment->getArticle()->getId(),
      'com_author' => $comment->getAuthor(),
      'com_date' => $comment->getDate(),
      'com_content' => $comment->getContent(),
      'com_family_id' => $comment->getFamilyParent(),
      'com_danger' => $comment->getDanger(),
    );

    if ($comment->getId()) {
      // The comment has already been saved : update it
      $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
    } else {
      // The comment has never been saved : insert it
      $this->getDb()->insert('t_comment', $commentData);
      // Get the id of the newly created comment and set it on the entity.
      $id = $this->getDb()->lastInsertId();
      $comment->setId($id);
    }
  }
     
    
  /**
  * Retourne un commentaire correspondant à son id
  *
  * @param integer $id
  *
  * @return \alaska\Domain\Comment|throws an exception if no matching article is found
  */
  public function find($id) {
    $sql = "select * from t_comment where com_id=?";
    $row = $this->getDb()->fetchAssoc($sql, array($id));

    if ($row)
        return $this->buildDomainObject($row);
    else
        throw new \Exception("No comment matching id " . $id);
  }

    /**
  * Retourne une liste des commentaires pour un article
  *
  * @param integer $articleId The article id.
  *
  * @return array A list of all comments for the article.
  */
  public function findAllByArticle($articleId) {
    // The associated article is retrieved only once
    $article = $this->articleDAO->find($articleId);
    // art_id is not selected by the SQL query
    // The article won't be retrieved during domain objet construction
    $sql = "select com_id, com_author, com_date, com_content, com_family_id, com_danger from t_comment where art_id=? order by com_date";
    $result = $this->getDb()->fetchAll($sql, array($articleId));
    // Convert query result to an array of domain objects
    $comments = array();
    foreach ($result as $row) {
      $comId = $row['com_id'];
      $comment = $this->buildDomainObject($row);
      // The associated article is defined for the constructed comment
      $comment->setArticle($article);
      $comments[$comId] = $comment;
    }
    return $comments;
  }

       // modération commentaire
    public function Signal($comment)
    {
        $comment->setDanger(true);
        $this->save($comment);
    }
    //Modification modération commentaire
    public function modifSignal($comment)
    {
        $comment->setDanger(null);
        $this->save($comment);
    }

    // Trouve tous les commentaires signalés

  public function findAllBySignal(){
        $sql = "select * from t_comment order by com_danger desc, com_id desc";
        $result = $this->getDb()->fetchAll($sql, array());
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            // $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }
    
  /**
  * Supprime un commentaire de la base de données 
  *
  * @param @param integer $id The comment id
  */
 public function delete($id) {
     // Find the child of comment
     $sql = "select * from t_comment where com_family_id=?";
     $child = $this->getDb()->fetchAssoc($sql, array($id));
     if ($child) {
     $IDChild = $child['com_id'];
     // Find the child of the child comment
     $sql = "select * from t_comment where com_family_id=?";
     $childChild = $this->getDb()->fetchAssoc($sql, array($IDChild));
     if ($childChild) {
       $IDChildChild = $childChild['com_id'];
       $this->getDb()->delete('t_comment', array('com_id' => $IDChildChild));
     }
       $this->getDb()->delete('t_comment', array('com_family_id' => $id));
  }

     $this->getDb()->delete('t_comment', array('com_id' => $id));
  }

  /**
  * Supprime tous les commentaires pour un article 
  *
  * @param $articleId The id of the article
  */
  public function deleteAllByArticle($articleId) {
    $this->getDb()->delete('t_comment', array('art_id' => $articleId));
  }

}
