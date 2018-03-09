<?php

namespace MicroCMS\Domain;

class Comment
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Auteur
     *
     * @var string
     */
    private $author;

    /**
     * Date
     *
     * @var string
     */
    private $date;


    /**
     * Contenu
     *
     * @var integer
     */
    private $content;


    /**
     * Article associé à un commentaire
     *
     * @var \microcms\Domain\Article
     */
    private $article;


    /**
     * Commentaire parent 
     *
     * @var integer
     */
    private $familyParent;


    /**
     * Danger
     *
     * @var string : yes or NULL
     */
    private $danger;



    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor( $author) {
         $this->author = $author;
         return $this;
     }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getArticle() {
        return $this->article;
    }

    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }

    public function getFamilyParent() {
        return $this->familyParent;
    }

    public function setFamilyParent($familyParent) {
        $this->familyParent = $familyParent;
        return $this;
    }

    public function getDanger() {
        return $this->danger;
    }

    public function setDanger($danger) {
        $this->danger = $danger;
        return $this;
    }

}
